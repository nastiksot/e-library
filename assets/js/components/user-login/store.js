"use strict";

import {generateActions, generateGetters, generateMutations} from "../../modules/VuexMappers";
import HttpClient from "../../modules/HttpClient";
import _keys from 'lodash/keys';
import RouteGenerator from "../../modules/RouteGenerator";
import ErrorResponse from "../../models/transport-response/error-response";
import SuccessResponse from "../../models/transport-response/success-response";

let state = {
    // general
    done: false,
    busy: false,
    invalid: false,
    ready: false,

    // form
    username: null,
    password: null,

    // errors
    globalError: null,
    errors: {
        username: null,
        password: null,
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

    setErrorMessage({state, commit, dispatch}, error) {
        commit('setGlobalError', error);
        commit('setInvalid', !!error);
    },

    setLastUsername({state, commit, dispatch}, username) {
        commit('setUsername', username);
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Boolean} redirectAfterLogin
     */
    submitUserLogin({state, commit, dispatch}, redirectAfterLogin: Boolean) {
        state.busy = true;

        // prepare state data to post
        let postData = {
            username: state.username ?? '',
            password: state.password ?? '',
        };

        HttpClient
            .post('api.login_check', {}, postData)
            .then((response: SuccessResponse) => {
                state.done = true;

                if (redirectAfterLogin) {
                    window.location.href = response.data.hasOwnProperty('target_url') && response.data.target_url
                        ? response.data.target_url
                        : RouteGenerator.generate('web.account');
                }

            }, (error: ErrorResponse) => {
                state.invalid = true;
                state.globalError = typeof (error.response.data.error) !== 'undefined' && error.response.data.error ? error.response.data.error : null;
            })
            .finally(() => {
                state.busy = false;
            });
    },

};

let getters = {
    ...generateGetters(...items),
    loginCheckUrl() {
        return RouteGenerator.generate('web.login_check');
    },

};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
