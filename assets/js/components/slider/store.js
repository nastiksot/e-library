"use strict";

import {generateActions, generateGetters, generateMutations} from "../../modules/VuexMappers";
import HttpClient from "../../modules/HttpClient";
import ErrorResponse from "../../models/transport-response/error-response";
import SuccessResponse from "../../models/transport-response/success-response";
import {camelizeArrayKeys} from "../../helpers/arrayHelper";
import _keys from "lodash/keys";
import _forEach from "lodash/forEach";
import Slider from "../../models/slider";
import ObjectToSliderFactory from "../../models/slider/object-to-slider-factory";
import RouteGenerator from "../../modules/RouteGenerator";

let state = {
    // general
    done: false,
    busy: false,
    invalid: false,
    ready: false,

    // form
    /** @var Slider */
    slider: null,

    // errors
    globalError: null,
    errors: {}

};

const items = _keys(state);

let mutations = {
    ...generateMutations(...items),

    setSlider(state: Object, slider: Slider) {
        state.slider = slider;
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
    loadFirstSlider({state, commit, dispatch}) {
        state.busy = true;

        HttpClient
            .get('api.slider_first')
            .then((response: SuccessResponse) => {
                let slider = ObjectToSliderFactory.create(response.data);
                commit('setSlider', slider);
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
