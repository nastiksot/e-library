"use strict";

import {generateActions, generateGetters, generateMutations} from "../../modules/VuexMappers";
import HttpClient from "../../modules/HttpClient";
import _forEach from 'lodash/forEach';
import _keys from 'lodash/keys';
import {camelizeArrayKeys} from "../../helpers/arrayHelper";
import ErrorResponse from "../../models/transport-response/error-response";
import SuccessResponse from "../../models/transport-response/success-response";

let state = {
    // general
    done: false,
    busy: false,
    invalid: false,
    ready: false,

    // form
    email: null,

    // errors
    globalError: null,
    errors: {
        email: null,
    }
};

const items = _keys(state);

let mutations = {
    ...generateMutations(...items),
};

let actions = {
    ...generateActions(...items),
    setReady({state, dispatch}, ready) {
        state.ready = ready;
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @return {Promise}
     */
    submitUserForgotPassword({state, commit, dispatch}) {
        state.busy = true;

        // prepare state data to post
        let postData = {
            email: state.email,
        };

        HttpClient
            .post('api.auth.forgot_password', {}, postData)
            .then((response: SuccessResponse) => {
                state.done = true;
            }, (error: ErrorResponse) => {
                state.invalid = true;
                state.globalError = null !== error.generalError ? error.generalError.message : null;
                let errors = null !== error.formError ? camelizeArrayKeys(error.formError.fieldErrors) : {};
                _forEach(errors, (message, key) => {
                    if (state.errors.hasOwnProperty(key)) {
                        state.errors[key] = message;
                    }
                });
            })
            .finally(() => {
                state.busy = false;
            });
    },

};

let getters = {
    ...generateGetters(...items),
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
