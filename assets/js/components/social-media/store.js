"use strict";

import {generateActions, generateGetters, generateMutations} from "../../modules/VuexMappers";
import HttpClient from "../../modules/HttpClient";
import ErrorResponse from "../../models/transport-response/error-response";
import SuccessResponse from "../../models/transport-response/success-response";
import {camelizeArrayKeys} from "../../helpers/arrayHelper";
import _keys from "lodash/keys";
import _forEach from "lodash/forEach";
import GeneralSettings from "../../models/general-settings";
import ObjectToGeneralSettingsFactory from "../../models/general-settings/object-to-general-settings-factory";

let state = {
    // general
    done: false,
    busy: false,
    invalid: false,
    ready: false,

    // form
    /** @var GeneralSettings */
    generalSettings: null,

    // errors
    globalError: null,
    errors: {},
};

const items = _keys(state);

let mutations = {
    ...generateMutations(...items),

    setGeneralSettings(state: Object, generalSettings: GeneralSettings) {
        state.generalSettings = generalSettings;
    },
};

let actions = {
    ...generateActions(...items),
    setReady({state, dispatch}, ready) {
        state.ready = ready;
    },
    setDone({state, dispatch}, done) {
        state.done = done;
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @return {Promise}
     */
    loadGeneralSettings({state, commit, dispatch}) {
        state.busy = true;

        HttpClient
            .get('api.general_settings')
            .then((response: SuccessResponse) => {
                let generalSettings = ObjectToGeneralSettingsFactory.create(response.data);
                commit('setGeneralSettings', generalSettings);

                state.invalid = false;
                state.globalError = null;
            }, (error: ErrorResponse) => {
                state.invalid = true;
               console.error('General settings error = ', error);
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
