"use strict";

import {generateActions, generateGetters} from "../../modules/VuexMappers";
import HttpClient from "../../modules/HttpClient";
import _forEach from 'lodash/forEach';
import _keys from 'lodash/keys';
import {camelizeArrayKeys} from "../../helpers/arrayHelper";
import ErrorResponse from "../../models/transport-response/error-response";
import SuccessResponse from "../../models/transport-response/success-response";
import ObjectToDealerUserFactory from "../../models/dealer-user/object-to-dealer-user-factory";
import RouteGenerator from "../../modules/RouteGenerator";
import USER_ROLE from "../../dictionary/user-role";

let state = {
    // general
    ready: false,
    done: false,
    busy: false,
    invalid: false,

    // form
    dealerUserId: null,
    firstName: null,
    lastName: null,
    email: null,
    password: null,
    active: false,
    role: null,

    // errors
    globalError: null,
    errors: {
        firstName: null,
        lastName: null,
        email: null,
        password: null,
        active: null,
        role: null,
    }
};

const items = _keys(state);

let mutations = {
    // mutations general
    setReady(state: Object, ready: Boolean) {
        state.ready = ready;
    },

    setDone(state, done: Boolean) {
        state.done = done;
    },

    setBusy(state: Object, busy: Boolean) {
        state.busy = busy;
    },

    setInvalid(state: Object, invalid: Boolean) {
        state.invalid = invalid;
    },

    setDealerUserId(state: Object, dealerUserId: Number) {
        state.dealerUserId = dealerUserId;
    },

    setFirstName(state: Object, firstName: String) {
        state.firstName = firstName;
    },

    setLastName(state: Object, lastName: String) {
        state.lastName = lastName;
    },

    setEmail(state: Object, email: String) {
        state.email = email;
    },

    setPassword(state: Object, password: String) {
        state.password = password;
    },

    setActive(state: Object, active: Boolean) {
        state.active = active;
    },

    setRole(state: Object, role: String) {
        state.role = role;
    },

    setGlobalError(state, globalError: String) {
        state.globalError = globalError;
    },

    setErrors(state, errors: Object) {
        state.errors = errors;
    },
};

/**
 *
 * @param {Number} dealerUserId
 * @param {UserAccount} me
 *
 * @returns {boolean}
 */
let isAuthorizedUser = function (dealerUserId, me) {
    return me.id && dealerUserId === me.id;
};

let actions = {
    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {String} dealerUid
     * @param {Number} dealerUserId
     */
    loadDealerUser({state, commit, dispatch}, {dealerUid, dealerUserId}) {
        commit("setDealerUserId", dealerUserId);

        HttpClient
            .get('api.dealerUser.account.details', {dealerUid: dealerUid, id: dealerUserId})
            .then(response => {
                let dealerUser = ObjectToDealerUserFactory.create(response.data);

                commit("setFirstName", dealerUser.firstName);
                commit("setLastName", dealerUser.lastName);
                commit("setEmail", dealerUser.email);
                commit("setPassword", null);
                commit("setActive", dealerUser.active);
                commit("setRole", dealerUser.role);
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {UserAccount} me
     */
    submitDealerUserAccount({state, commit, dispatch}, me) {
        if (!me.dealerUid) {
            return;
        }

        commit("setDone", false);
        commit("setBusy", true);
        commit("setInvalid", false);

        // prepare state data to post
        let isActive = Boolean(state.active),
            postData = {
            first_name: state.firstName,
            last_name: state.lastName,
            email: state.email,
            password: state.password,
            role: state.role,
            active: isActive,
        };

        let isCreateRequest = state.dealerUserId === null,
            httpClient;

        if (isCreateRequest) {
            httpClient = HttpClient.post('api.dealerUser.account.create', {dealerUid: me.dealerUid}, postData);
        } else {
            httpClient = HttpClient.patch('api.dealerUser.account.update', {dealerUid: me.dealerUid, id: state.dealerUserId}, postData);
        }

        httpClient
            .then((response: SuccessResponse) => {
                commit("setDone", true);

                if (isCreateRequest) {
                    // redirect to Users list after Create action
                    window.location = RouteGenerator.generate('web.account.dealer.users');
                } else if (isAuthorizedUser(state.dealerUserId, me)) {
                    // authorized user has edited own data
                    if (!isActive || USER_ROLE.ROLE_DEALER_EMPLOYEE === state.role) {
                        // current user doesn't have access to Employee Management => the system will log out him
                        window.location = RouteGenerator.generate('web.account');
                    }
                }
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

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {UserAccount} me
     */
    deleteDealerUserAccount({state, commit, dispatch}, me) {
        if (!me.dealerUid) {
            return;
        }

        commit("setDone", false);
        commit("setBusy", true);
        commit("setInvalid", false);

        HttpClient
            .delete("api.dealerUser.account.delete", {dealerUid: me.dealerUid, id: state.dealerUserId})
            .then((response: SuccessResponse) => {
                let routeName = isAuthorizedUser(state.dealerUserId, me)
                    ? 'web.account'      // authorized user deleted himself
                    : 'web.account.dealer.users';
                window.location = RouteGenerator.generate(routeName);
            }, error => {
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
