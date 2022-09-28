"use strict";

import {generateActions, generateGetters, generateMutations} from "../../modules/VuexMappers";
import HttpClient from "../../modules/HttpClient";
import _forEach from 'lodash/forEach';
import _keys from 'lodash/keys';
import {camelizeArrayKeys} from "../../helpers/arrayHelper";
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
    email: null,
    acceptNews: false,
    acceptProcessPersonalData: false,
    acceptPrivacyPolicy: false,

    // errors
    globalError: null,
    errors: {
        email: null,
        acceptNews: null,
        acceptProcessPersonalData: null,
        acceptPrivacyPolicy: null,
    }
};

const items = _keys(state);

let mutations = {
    ...generateMutations(...items),

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

};

let actions = {
    ...generateActions(...items),

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {String|undefined} uid
     * @param {Boolean|undefined} isDealerMode
     */
    submitUserRegister({state, commit, dispatch}, {uid, isDealerMode}) {
        commit("setBusy", true);
        commit("setWishListSaved", false);

        // prepare state data to post
        let postData = {
            email: state.email,
            accept_news: Boolean(state.acceptNews),
            accept_process_personal_data: Boolean(state.acceptProcessPersonalData),
            accept_privacy_policy: Boolean(state.acceptPrivacyPolicy),
        };

        if (uid) {
            postData.uid = uid;
        }

        if (isDealerMode) {
            postData.is_dealer_mode = 1;
        }

        HttpClient
            .post('api.auth.register', {}, postData)
            .then((response: SuccessResponse) => {
                if (uid) {
                    commit("setWishListSaved", true);
                }

                commit("setDone", true);

            }, (error: ErrorResponse) => {
                commit("setInvalid", true);

                let globalError = null !== error.generalError ? error.generalError.message : null;
                commit("setGlobalError", globalError);

                // resolve errors
                let errors = state.errors;
                let fieldErrors = null !== error.formError ? camelizeArrayKeys(error.formError.fieldErrors) : {};
                _forEach(state.errors, (value, key) => {
                    errors[key] = null;
                    if (fieldErrors.hasOwnProperty(key)) {
                        errors[key] = fieldErrors[key];
                    }
                });
                commit("setErrors", errors);

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
