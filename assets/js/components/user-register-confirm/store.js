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
    password: null,
    confirmPassword: null,

    // errors
    globalError: null,
    errors: {
        password: null,
        confirmPassword: null,
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
    setToken({state, dispatch}, token) {
        state.token = token;
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     */
    submitUserRegisterConfirm({state, commit, dispatch}) {
        state.busy = true;

        // prepare state data to post
        let postData = {
            password: state.password,
            confirm_password: state.confirmPassword,
        };

        HttpClient
            .patch('api.auth.register_confirm', {token: state.token}, postData)
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
