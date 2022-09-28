"use strict";

import HttpClient from "../../modules/HttpClient";
import {camelizeArrayKeys} from "../../helpers/arrayHelper";
import ErrorResponse from "../../models/transport-response/error-response";
import SuccessResponse from "../../models/transport-response/success-response";

let state = {
    // general
    done: false,
    busy: false,
    invalid: false,

    // errors
    globalError: null,
    fieldErrors: {}
};

let mutations = {
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

    // mutations errors
    setGlobalError(state, globalError: String) {
        state.globalError = globalError;
    },

    setFieldErrors(state, fieldErrors: Object) {
        state.fieldErrors = fieldErrors;
    },
};

let actions = {

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Boolean} done
     */
    setDone({state, commit, dispatch}, done: Boolean) {
        commit("setDone", done);
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {String} dealerUid
     * @param {String} wishListUid
     * @param {String|undefined} contactName
     * @param {String|undefined} email
     * @param {String|undefined} phone
     * @param {String|undefined} address
     * @param {String|undefined} message
     * @param {boolean|undefined} sendCopy
     *
     * @return {Promise}
     */
    submitDealerRequest({state, commit, dispatch}, {
        dealerUid,
        wishListUid,
        contactName,
        email,
        phone,
        address,
        message,
        sendCopy
    }) {
        commit("setBusy", true);

        // prepare data to post
        let postData = {
            contact_name: contactName,
            email: email,
            phone: phone,
            address: address,
            message: message,
            send_copy: sendCopy,
        };

        return HttpClient
            .post("api.dealers.requests_post", {dealerUid: dealerUid, wishListUid: wishListUid}, postData)
            .then((response: SuccessResponse) => {
                commit("setDone", true);
                commit("setInvalid", false);
                commit("setGlobalError", null);
                commit("setFieldErrors", {});
            }, (error: ErrorResponse) => {
                // resolve global error
                let globalError = null !== error.generalError ? error.generalError.message : null;
                commit("setInvalid", true);
                commit("setGlobalError", globalError);

                // resolve field errors
                let fieldErrors = null !== error.formError ? camelizeArrayKeys(error.formError.fieldErrors) : {};
                commit("setFieldErrors", fieldErrors);
            })
            .finally(() => {
                commit("setBusy", false);
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
