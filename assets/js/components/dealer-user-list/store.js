"use strict";

import HttpClient from "../../modules/HttpClient";
import SuccessResponse from "../../models/transport-response/success-response";
import ObjectToDealerUserFactory from "../../models/dealer-user/object-to-dealer-user-factory";
import DealerUser from "../../models/dealer-user/index";
import _keys from 'lodash/keys';
import RouteGenerator from "../../modules/RouteGenerator";
import UserAccount from "../../models/user-account";

let state = {
    // general
    busy: false,
    ready: false,

    /** @param Array<DealerUser> */
    dealerUsers: [],
};

const items = _keys(state);

let mutations = {
    setBusy(state: Object, busy: Boolean) {
        state.busy = busy;
    },

    setReady(state: Object, ready: Boolean) {
        state.ready = ready;
    },

    setDealerUsers(state: Object, dealerUsers: Array<DealerUser>) {
        state.dealerUsers = dealerUsers;
    },
};

let actions = {
    setReady({state, commit, dispatch}, ready) {
        commit("setReady", ready);
    },

    setBusy({state, commit, dispatch}, busy) {
        commit("setBusy", busy);
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {String} dealerUid
     */
    loadDealerUsers({state, commit, dispatch}, dealerUid: String) {
        commit("setBusy", true);

        HttpClient
            .get('api.dealerUser.collection', {dealerUid: dealerUid})
            .then((response: SuccessResponse) => {
                let dealerUsers = response.dataCollection.items.map(item => ObjectToDealerUserFactory.create(item));

                commit("setDealerUsers", dealerUsers);

                setTimeout(() => {
                    commit("setReady", true);
                }, 500);
            }, error => {
            })
            .finally(() => {
                commit("setBusy", false);
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Number} dealerUserId
     * @param {UserAccount} me
     */
    dealerUserDelete({state, commit, dispatch}, {dealerUserId, me}) {
        if (!me.dealerUid) {
            return;
        }

        commit("setBusy", true);

        HttpClient
            .delete("api.dealerUser.account.delete", {dealerUid: me.dealerUid, id: dealerUserId})
            .then((response: SuccessResponse) => {
                if (dealerUserId === me.id) {
                    // authorized user deleted himself
                    window.location = RouteGenerator.generate('web.account');
                } else {
                    dispatch("loadDealerUsers",  me.dealerUid);
                }
            }, error => {
            });
    },
};

let getters = {};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
