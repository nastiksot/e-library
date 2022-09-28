"use strict";

import {generateActions, generateGetters, generateMutations} from "../../modules/VuexMappers";
import HttpClient from "../../modules/HttpClient";
import ErrorResponse from "../../models/transport-response/error-response";
import SuccessResponse from "../../models/transport-response/success-response";
import ObjectToDecisionFactory from "../../models/decision/object-to-decision-factory";
import Decision from "../../models/decision/index";
import {camelizeArrayKeys} from "../../helpers/arrayHelper";
import DecisionAnswer from "../../models/decision-answer";
import ObjectToDecisionAnswerFactory from "../../models/decision-answer/object-to-decision-answer-factory";
import _keys from "lodash/keys";
import _forEach from "lodash/forEach";
import _slice from "lodash/slice";
import _find from "lodash/find";
import _findIndex from "lodash/findIndex";

let state = {
    // general
    done: false,
    busy: false,
    invalid: false,
    ready: false,
    isDealerMode: false,

    // form
    /** @type Decision current decision */
    decision: null,
    /** @type Array<Decision> */
    decisions: [],
    /** @type Array<DecisionAnswer> */
    answers: [],

    // errors
    globalError: null,
    errors: {}

};

const items = _keys(state);

let mutations = {
    ...generateMutations(...items),

    setDone(state, done: Boolean) {
        state.done = done;
    },

    setBusy(state, busy: Boolean) {
        state.busy = busy;
    },

    setIsDealerMode(state, isDealerMode: Boolean) {
        state.isDealerMode = isDealerMode;
    },

    setInvalid(state, invalid: Boolean) {
        state.invalid = invalid;
    },

    setReady(state, ready: Boolean) {
        state.ready = ready;
    },

    setDecision(state, decision: Decision) {
        state.decision = decision;
    },

    setDecisions(state, decisions: Array<Decision>) {
        state.decisions = decisions;
    },

    setAnswers(state, answers: Array<DecisionAnswer>) {
        state.answers = answers;
    },

    setGlobalError(state, globalError: String) {
        state.globalError = globalError;
    },

    setErrors(state, errors: Object) {
        state.errors = errors;
    },

    /**
     * @param {Object} state
     * @param {Decision} decision
     * @param {Number|undefined} parentId
     */
    addDecision(state: Object, {decision, parentId}) {
        let index = _findIndex(state.decisions, {id: parentId});
        // add the first decision
        if (-1 === index) {
            state.decisions.push(decision);
        } else {
            // remove all decisions after selected index
            // add decision
            state.decisions = _slice(state.decisions, 0, index + 1);
            state.decisions.push(decision);
        }
    },

    /**
     * @param {Object} state
     * @param {DecisionAnswer} answer
     */
    addAnswer(state: Object, {answer}) {
        // all answers have parentId as question decision
        if (null === answer.parentId) {
            return;
        }

        let index = _findIndex(state.answers, {parentId: answer.parentId});
        // add the first answer
        if (-1 === index) {
            state.answers.push(answer);
        } else {
            // remove all answers after selected index
            // add answer
            state.answers = _slice(state.answers, 0, index);
            state.answers.push(answer);
        }

    },
};

let actions = {
    ...generateActions(...items),
    setReady({state, commit, dispatch}, ready) {
        commit("setReady", ready);
    },

    setDone({state, commit, dispatch}, done) {
        commit("setDone", done);
    },

    setIsDealerMode({state, commit, dispatch}, isDealerMode) {
        commit("setIsDealerMode", isDealerMode);
    },

    setStartDecision({state, commit, dispatch}, decisionId: Number) {
        dispatch('resetDecisions', decisionId);
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Number} decisionId
     */
    resetDecisions({state, commit, dispatch}, decisionId) {
        commit("setDone", false);
        commit("setInvalid", false);
        commit("setReady", false);
        commit("setDecision", null);
        commit("setDecisions", []);
        commit("setAnswers", []);
        commit("setGlobalError", null);
        commit("setErrors", {});

        dispatch("loadDecisionData", ObjectToDecisionAnswerFactory.create({id: decisionId}));
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {DecisionAnswer} answer
     * @return {Promise}
     */
    loadDecisionData({state, commit, dispatch}, answer: DecisionAnswer) {
        commit("setBusy", true);

        let queryParams = {id: answer.id};

        if (state.isDealerMode) {
            queryParams.is_dealer_mode = 1;
        }

        return HttpClient
            .get('api.decision_data', queryParams)
            .then((response: SuccessResponse) => {
                let decision = ObjectToDecisionFactory.create(response.data);
                commit("addDecision", {decision: decision, parentId: answer.parentId});
                commit("setDecision", decision);
                commit("addAnswer", {answer: answer});

                commit("setInvalid", false);
                commit("setGlobalError", null);
                commit("setErrors", {});

            }, (error: ErrorResponse) => {
                commit("setInvalid", true)

                let globalError = null !== error.generalError ? error.generalError.message : null;
                commit("setGlobalError", globalError);

                // resolve errors
                let errors = {};
                let fieldErrors = null !== error.formError ? camelizeArrayKeys(error.formError.fieldErrors) : {};
                _forEach(fieldErrors, (value, key) => {
                    errors[key] = null;
                    if (fieldErrors.hasOwnProperty(key)) {
                        errors[key] = fieldErrors[key];
                    }
                });
                commit("setErrors", errors);
            })
            .finally(() => {
                if (!state.ready) {
                    commit("setReady", true);
                }
                commit("setBusy", false);
            });
    },
};

let getters = {
    ...generateGetters(...items),

    /**
     * @return Array<DecisionAnswer>
     */
    answers(state: Object) {
        return state.answers;
    },

    /**
     * @return {Decision|undefined}
     */
    getDecisionById(state: Object) {
        return (id: Number) => {
            return _find(state.decisions, {id: id});
        }
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
