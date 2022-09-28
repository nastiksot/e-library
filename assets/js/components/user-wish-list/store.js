"use strict";

//import {generateActions, generateGetters, generateMutations} from "../../modules/VuexMappers";
import HttpClient from "../../modules/HttpClient";
import _forEach from 'lodash/forEach';
import _keys from 'lodash/keys';
import ErrorResponse from "../../models/transport-response/error-response";
import SuccessResponse from "../../models/transport-response/success-response";
import ObjectToWishListFactory from "../../models/wish-list/object-to-wish-list-factory";
import WishList from "../../models/wish-list/index";
import RouteGenerator from "../../modules/RouteGenerator";
import {camelizeArrayKeys} from "../../helpers/arrayHelper";

let state = {
    // general
    busy: false,
    ready: false,
    invalid: false,

    /** @param Array<WishList> */
    wishLists: [],

    // errors
    globalError: null,
    errors: {
    }

};

const items = _keys(state);

let mutations = {
    //...generateMutations(...items),

    setBusy(state: Object, busy: Boolean) {
        state.busy = busy;
    },

    setReady(state: Object, ready: Boolean) {
        state.ready = ready;
    },

    setInvalid(state: Object, invalid: Boolean) {
        state.invalid = invalid;
    },

    setWishLists(state: Object, wishLists: Array<WishList>) {
        state.wishLists = wishLists;
    },

    setGlobalError(state: Object, globalError: String) {
        state.globalError = globalError;
    },

    setErrors(state: Object, errors: Object) {
        state.errors = errors;
    },
};

let actions = {
    // ...generateActions(...items),
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
     */
    loadWishLists({state, commit, dispatch}) {
        commit("setBusy", true);
        commit("setGlobalError", null);
        commit("setErrors", {});

        HttpClient
            .get('api.user.wishlist.collection')
            .then((response: SuccessResponse) => {
                let wishLists = response.dataCollection.items.map(item => ObjectToWishListFactory.createUserWishList(item));
                commit("setWishLists", wishLists);
                setTimeout(() => {
                    commit("setReady", true);
                }, 500);
            }, (error: ErrorResponse) => {
                commit("setInvalid", true);

                // set global error
                let globalError = null !== error.generalError ? error.generalError.message : null;
                commit("setGlobalError", globalError);

                // set errors
                 let errors = null !== error.formError ? camelizeArrayKeys(error.formError.fieldErrors) : {};
                _forEach(errors, (message, key) => {
                    if (state.errors.hasOwnProperty(key)) {
                        errors[key] = message;
                    }
                });
                commit("setErrors", errors);

            })
            .finally(() => {
                commit("setBusy", false);
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {string} uid
     */
    wishListDetails({state, commit, dispatch}, uid: String) {
        window.open(RouteGenerator.generate('web.wishlist.details', {wishListUid: uid}), '_blank').focus();
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Number} wishListId
     */
    wishListDelete({state, commit, dispatch}, wishListId: Number) {
        commit("setBusy", true);
        HttpClient
            .delete("api.user.wishlist.delete", {wishListId: wishListId})
            .then((response: SuccessResponse) => {
                dispatch("loadWishLists");
            }, error => {
            });
    },

};

let getters = {
    //...generateGetters(...items),
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
