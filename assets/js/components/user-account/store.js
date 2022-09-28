"use strict";

import {generateActions, generateGetters} from "../../modules/VuexMappers";
import HttpClient from "../../modules/HttpClient";
import _forEach from 'lodash/forEach';
import _keys from 'lodash/keys';
import {camelizeArrayKeys} from "../../helpers/arrayHelper";
import UserAccount from "../../models/user-account";
import ErrorResponse from "../../models/transport-response/error-response";
import SuccessResponse from "../../models/transport-response/success-response";
import WishList from "../../models/wish-list";
import ObjectToWishListFactory from "../../models/wish-list/object-to-wish-list-factory";
import RouteGenerator from "../../modules/RouteGenerator";

let state = {
    // general
    ready: false,
    done: false,
    doneUpdateWishList: false,
    busy: false,
    invalid: false,

    // form
    firstName: null,
    lastName: null,
    email: null,
    password: null,
    acceptNews: false,
    acceptProcessPersonalData: false,
    acceptPrivacyPolicy: false,

    /** @param Array<WishList> */
    wishLists: [],

    // errors
    globalError: null,
    commonFieldErrors: {},
    errors: {
        firstName: null,
        lastName: null,
        email: null,
        password: null,
        acceptNews: null,
        acceptProcessPersonalData: null,
        acceptPrivacyPolicy: null,
    }
};

const items = _keys(state);

let mutations = {
    //...generateMutations(...items),

    // mutations general
    setReady(state: Object, ready: Boolean) {
        state.ready = ready;
    },

    setDone(state, done: Boolean) {
        state.done = done;
    },

    setDoneUpdateWishList(state, doneUpdateWishList: Boolean) {
        state.doneUpdateWishList = doneUpdateWishList;
    },

    setBusy(state: Object, busy: Boolean) {
        state.busy = busy;
    },

    setInvalid(state: Object, invalid: Boolean) {
        state.invalid = invalid;
    },

    // mutations forms

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

    setAcceptNews(state: Object, acceptNews: Boolean) {
        state.acceptNews = acceptNews;
    },

    setAcceptProcessPersonalData(state: Object, acceptProcessPersonalData: Boolean) {
        state.acceptProcessPersonalData = acceptProcessPersonalData;
    },

    setAcceptPrivacyPolicy(state: Object, acceptPrivacyPolicy: Boolean) {
        state.acceptPrivacyPolicy = acceptPrivacyPolicy;
    },

    setWishLists(state: Object, wishLists: Array<WishList>) {
        state.wishLists = wishLists;
    },

    // mutations errors

    setGlobalError(state, globalError: String) {
        state.globalError = globalError;
    },

    setCommonFieldErrors(state, commonFieldErrors: Object) {
        state.commonFieldErrors = commonFieldErrors;
    },

    setErrors(state, errors: Object) {
        state.errors = errors;
    },
};

let getWishListUrl = function (uid) {
    return RouteGenerator.generate('web.wishlist.details', {wishListUid: uid});
};

let actions = {
    //...generateActions(...items),

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {UserAccount} userAccount
     */
    setUserAccount({state, commit, dispatch}, userAccount: UserAccount) {
        commit("setFirstName", userAccount.firstName);
        commit("setLastName", userAccount.lastName);
        commit("setEmail", userAccount.email);
        commit("setEmail", userAccount.email);
        commit("setPassword", null);
        commit("setAcceptNews", userAccount.acceptNews);
        commit("setAcceptProcessPersonalData", userAccount.acceptProcessPersonalData);
        commit("setAcceptPrivacyPolicy", userAccount.acceptPrivacyPolicy);
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     */
    submitUserAccount({state, commit, dispatch}) {
        commit("setDone", false);
        commit("setBusy", true);
        commit("setInvalid", false);

        // prepare state data to post
        let postData = {
            first_name: state.firstName,
            last_name: state.lastName,
            email: state.email,
            password: state.password,
            accept_news: Boolean(state.acceptNews),
            accept_process_personal_data: Boolean(state.acceptProcessPersonalData),
            accept_privacy_policy: Boolean(state.acceptPrivacyPolicy),
        };

        HttpClient
            .patch("api.me.profile", {}, postData)
            .then((response: SuccessResponse) => {
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

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     */
    deleteCurrentUser({state, commit, dispatch}) {
        commit("setBusy", true);
        commit("setInvalid", false);
        commit("setGlobalError", null);

        HttpClient
            .delete("api.me.profile.delete")
            .then((response: SuccessResponse) => {
                window.location = RouteGenerator.generate('web.account');
            }, (error: ErrorResponse) => {
                commit("setInvalid", true);

                let errorMsg;

                if (error._dataError && error._dataError.message) {
                    errorMsg = error._dataError.message;
                } else if (error.response && error.response.statusText) {
                    errorMsg = error.response.statusText;
                }

                if (errorMsg) {
                    commit("setGlobalError", errorMsg);
                }
            }).finally(() => {
                commit("setBusy", false);
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     */
    loadWishLists({state, commit, dispatch}) {
        commit("setBusy", true);
        commit("setGlobalError", null);
        commit("setErrors", {});

        HttpClient
            .get('api.user.wishlist.collection')
            .then((response: SuccessResponse) => {
                let wishLists = response.dataCollection.items.map(item => ObjectToWishListFactory.createUserWishList(item));
                commit("setWishLists", wishLists);
                setTimeout(() => {
                    commit("setReady", true);
                }, 500);
            }, (error: ErrorResponse) => {
                commit("setInvalid", true);

                // set global error
                let globalError = null !== error.generalError ? error.generalError.message : null;
                commit("setGlobalError", globalError);

                // set errors
                let errors = null !== error.formError ? camelizeArrayKeys(error.formError.fieldErrors) : {};
                _forEach(errors, (message, key) => {
                    if (state.errors.hasOwnProperty(key)) {
                        errors[key] = message;
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
     * @param {string} uid
     */
    wishListDetails({state, commit, dispatch}, uid: String) {
        window.open(getWishListUrl(uid), '_blank').focus();
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Number} wishListId
     */
    wishListDelete({state, commit, dispatch}, wishListId: Number) {
        commit("setBusy", true);
        HttpClient
            .delete("api.user.wishlist.delete", {wishListId: wishListId})
            .then((response: SuccessResponse) => {
                dispatch("loadWishLists");
            }, error => {
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {String} wishListUid
     * @param {String} wishListName
     *
     * @return {Promise}
     */
    updateUserWishList({state, commit, dispatch}, {
        wishListUid,
        wishListName,
    }) {
        commit("setBusy", true);
        commit("setDoneUpdateWishList", false);

        return HttpClient
            .patch("api.user.wishlist.update", {wishListUid: wishListUid}, {wishListName: wishListName})
            .then((response: SuccessResponse) => {
                commit("setDoneUpdateWishList", true);
                commit("setReady", true);
                commit("setInvalid", false);
                commit("setCommonFieldErrors", {});
            }, (error: ErrorResponse) => {
                commit("setInvalid", true);

                // resolve field errors
                let fieldErrors = null !== error.formError ? camelizeArrayKeys(error.formError.fieldErrors) : {};
                commit("setCommonFieldErrors", fieldErrors);

                // resolve global error
                let globalError = null;

                if (null !== error.generalError) {
                    globalError = error.generalError.message;
                } else if (Object.keys(fieldErrors).length === 0 && error.response && error.response.statusText) {
                    globalError = error.response.statusText;
                }

                commit("setGlobalError", globalError);
            })
            .finally(() => {
                commit("setBusy", false);
            });
    },

};

let getters = {
    ...generateGetters(...items),

    wishListUrl() {
        return (uid) => {
            return getWishListUrl(uid);
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
