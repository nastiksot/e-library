"use strict";

import {generateActions, generateGetters, generateMutations} from "../../modules/VuexMappers";
import HttpClient from "../../modules/HttpClient";
import _keys from 'lodash/keys';
import ErrorResponse from "../../models/transport-response/error-response";
import SuccessResponse from "../../models/transport-response/success-response";
import RouteGenerator from "../../modules/RouteGenerator";

let state = {
    // general
    done: false,
    busy: false,
    invalid: false,
    wishListSaved: false,

    // form

    // errors
    globalError: null,
};

const items = _keys(state);

let mutations = {
    //...generateMutations(...items),

    // mutations general
    setDone(state, done: Boolean) {
        state.done = done;
    },

    setBusy(state: Object, busy: Boolean) {
        state.busy = busy;
    },

    setInvalid(state: Object, invalid: Boolean) {
        state.invalid = invalid;
    },

    setWishListSaved(state: Object, wishListSaved: Boolean) {
        state.wishListSaved = wishListSaved;
    },

    setGlobalError(state: Object, globalError: String) {
        state.globalError = globalError;
    },

};

let actions = {
    ...generateActions(...items),

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Boolean} wishListSaved
     */
    setWishListSaved({state, commit, dispatch}, wishListSaved: Boolean) {
        commit("setWishListSaved", wishListSaved);
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {String|undefined} uid
     * @param {Boolean} isDealerMode
     */
    saveWishListConfiguration({state, commit, dispatch}, {uid, isDealerMode}) {
        commit("setBusy", true);
        commit("setWishListSaved", false);

        // prepare state data to post
        let postData = {
            uid: uid
        };

        if (isDealerMode) {
            postData.is_dealer_mode = 1;
        }

        HttpClient
            .post('api.user.wishlist.add', {}, postData)
            .then((response: SuccessResponse) => {
                commit("setWishListSaved", true);
                commit("setDone", true);
                commit("setInvalid", false);
                commit("setGlobalError", null);

            }, (error: ErrorResponse) => {
                commit("setInvalid", true);

                let globalError = null !== error.generalError ? error.generalError.message : null;
                commit("setGlobalError", globalError);
            })
            .finally(() => {
                commit("setBusy", false);
            });
    },

};

let getters = {
    ...generateGetters(...items),
    privacyPolicyUrl() {
        return RouteGenerator.generate('web.cms', {slug: 'privacy-policy'});
    },

};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
