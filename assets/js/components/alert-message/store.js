"use strict";

import AlertMessage from "../../models/alert-message/index";
import ObjectToAlertMessageFactory from "../../models/alert-message/object-to-alert-message-factory";
import _delay from "lodash/delay";

let state = {

    /** @type Array<AlertMessage> */
    alertMessages: [],
};

let mutations = {

    // mutations messages
    setAlertMessages(state: Object, alertMessages: Array<AlertMessage>) {
        state.alertMessages = alertMessages;
    },
    addAlertMessage(state: Object, alertMessage: AlertMessage) {
        state.alertMessages.push(alertMessage);
    },

    removeAlertMessage(state: Object, index: Number) {
        if (state.alertMessages[index]) {
            state.alertMessages.splice(index, 1);
        }
    },

    removeFirstAlertMessage(state: Object, index: Number) {
        if (state.alertMessages.length > 0) {
            state.alertMessages.splice(0, 1);
        }
    },
};

let actions = {

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Number} delay
     */
    setDelay({state, commit, dispatch}, delay: Number) {
        commit("setDelay", delay);
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Array<AlertMessage>} alertMessages
     */
    setAlertMessages({state, commit, dispatch}, {alertMessages}) {
        commit("setAlertMessages", alertMessages);
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     */
    clearAlertMessages({state, commit, dispatch}) {
        commit("setAlertMessages", []);
    },

    removeAlertMessage({state, commit, dispatch}, index: Number) {
        commit("removeAlertMessage", index);
    },

    addAlertMessage({state, commit, dispatch}, alertMessage: AlertMessage) {
        commit("addAlertMessage", alertMessage);
    },

    addInfoAlertMessage({state, commit, dispatch}, alertMessage: String) {
        dispatch("addAlertMessage", ObjectToAlertMessageFactory.createInfo(alertMessage));
    },

    addSuccessAlertMessage({state, commit, dispatch}, alertMessage: String) {
        dispatch("addAlertMessage", ObjectToAlertMessageFactory.createSuccess(alertMessage));
    },

    addWarningAlertMessage({state, commit, dispatch}, alertMessage: String) {
        dispatch("addAlertMessage", ObjectToAlertMessageFactory.createWarning(alertMessage));
    },

    addErrorAlertMessage({state, commit, dispatch}, alertMessage: String) {
        dispatch("addAlertMessage", ObjectToAlertMessageFactory.createError(alertMessage));
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


(function updater() {
    // remove the first AlertMessage
    if (state.alertMessages.length > 0) {
        _delay(() => {
            if (state.alertMessages.length > 0) {
                state.alertMessages.splice(0, 1);
            }
        }, 2500);
    }
    _delay(updater, 2500);
})();
