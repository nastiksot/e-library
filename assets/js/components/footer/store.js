"use strict";

import {generateActions, generateGetters, generateMutations} from "../../modules/VuexMappers";
import HttpClient from "../../modules/HttpClient";
import _keys from 'lodash/keys';
import ErrorResponse from "../../models/transport-response/error-response";
import SuccessResponse from "../../models/transport-response/success-response";
import {camelizeArrayKeys} from "../../helpers/arrayHelper";
import _forEach from "lodash/forEach";
import ObjectToPartnerFactory from "../../models/partner/object-to-partner-factory";
import Partner from "../../models/partner";

let state = {
    // general
    done: false,
    busy: false,
    invalid: false,
    ready: false,

    // form
    /** @type Array<Partner> */
    partners: [],

    // errors
    globalError: null,
    errors: {}

};

const items = _keys(state);

let mutations = {
    ...generateMutations(...items),
    setPartners(state: Object, partners: Array<Partner>): void {
        state.partners = partners;
    },


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

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @return {Promise}
     */
    loadPartners({state, commit, dispatch}) {
        state.busy = true;

        HttpClient
            .get('api.partner_list')
            .then((response: SuccessResponse) => {
                let partners = response.dataCollection.items.map(item => ObjectToPartnerFactory.create(item));
                commit("setPartners", partners);
                state.invalid = false;
                state.globalError = null;

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
                if (!state.ready) {
                    state.ready = true;
                }
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
