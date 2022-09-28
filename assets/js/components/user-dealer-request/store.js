'use strict';

import _keys from 'lodash/keys';
import HttpClient from "../../modules/HttpClient";
import SuccessResponse from "../../models/transport-response/success-response";
import ObjectToDealerRequestFactory from "../../models/dealer-request/object-to-dealer-request-factory";
import ObjectToDealerRequestCommentFactory from "../../models/dealer-request-comment/object-to-dealer-request-comment-factory";
import DealerRequest from "../../models/dealer-request/index";
import ErrorResponse from "../../models/transport-response/error-response";
import {camelizeArrayKeys} from "../../helpers/arrayHelper";

let state = {
    // general
    doneDelete: false,
    doneArchive: false,
    busy: false,
    ready: false,
    globalError: null,

    dealerUid: null,
    lang: null, // eg "en"
    isArchivedRequestsPage: null,
    /** @param Array<DealerRequest> */
    dealerRequests: [],
    totalRequestsQty: 0,
    activeStatusKeys: [],
    tableListOptions: {
        itemsPerPage: 10,
    },
};

const items = _keys(state);

let mutations = {
    setDoneDelete(state, doneDelete: Boolean) {
        state.doneDelete = doneDelete;
    },

    setDoneArchive(state, doneArchive: Boolean) {
        state.doneArchive = doneArchive;
    },

    setBusy(state: Object, busy: Boolean) {
        state.busy = busy;
    },

    setReady(state: Object, ready: Boolean) {
        state.ready = ready;
    },

    setGlobalError(state, globalError: String) {
        state.globalError = globalError;
    },

    setDealerUid(state: Object, dealerUid: String) {
        state.dealerUid = dealerUid;
    },

    setLang(state: Object, lang: String) {
        state.lang = lang;
    },

    setIsArchivedRequestsPage(state: Object, isArchivedRequestsPage: Boolean) {
        state.isArchivedRequestsPage = isArchivedRequestsPage;
    },

    setDealerRequests(state: Object, dealerRequests: Array<DealerRequest>) {
        state.dealerRequests = dealerRequests;
    },

    setTotalRequestsQty(state: Object, totalRequestsQty: Number) {
        state.totalRequestsQty = totalRequestsQty;
    },

    setActiveStatusKeys(state: Object, activeStatusKeys: Array) {
        state.activeStatusKeys = activeStatusKeys;
    },

    setTableListOptions(state: Object, tableListOptions: Object) {
        state.tableListOptions = tableListOptions;
    },

    /**
     * @param {Object} state
     * @param {String} activeStatusKey
     */
    toggleStatusFilter(state: Object, activeStatusKey): void {
        let indexOf = state.activeStatusKeys.indexOf(activeStatusKey);

        if (-1 !== indexOf) {
            state.activeStatusKeys.splice(indexOf, 1);
        } else {
            state.activeStatusKeys.push(activeStatusKey);
        }
    },

    /**
     * @param {Object} state
     */
    resetStatusFilters(state: Object): void {
        state.activeStatusKeys.splice(0, state.activeStatusKeys.length);
    },
};

let resolveError = function (error: ErrorResponse, commit) {
    let fieldErrors = null !== error.formError ? camelizeArrayKeys(error.formError.fieldErrors) : {},
        errorsArr = [];

    for (let field in fieldErrors) {
        errorsArr.push(fieldErrors[field]);
    }

    let globalError;

    if (errorsArr.length) {
        globalError = errorsArr.join('; ');
    } else if (error.response.statusText) {
        globalError = error.response.statusText;
    }

    let generalError = null !== error.generalError ? error.generalError.message : null;

    if (generalError) {
        globalError = generalError + (globalError ? ': ' + globalError : '');
    }

    if (globalError) {
        commit("setGlobalError", globalError + '.');

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }
};

let actions = {
    setDoneDelete({state, commit, dispatch}, doneDelete: Boolean) {
        commit("setDoneDelete", doneDelete);
    },

    setDoneArchive({state, commit, dispatch}, doneArchive: Boolean) {
        commit("setDoneArchive", doneArchive);
    },

    setReady({state, commit, dispatch}, ready) {
        commit("setReady", ready);
    },

    setBusy({state, commit, dispatch}, busy) {
        commit("setBusy", busy);
    },

    setDealerUid({state, commit, dispatch}, dealerUid) {
        commit('setDealerUid', dealerUid);

        dispatch("loadRequests");
    },

    setLang({state, commit, dispatch}, lang) {
        commit('setLang', lang);
    },

    setIsArchivedRequestsPage({state, commit, dispatch}, isArchivedRequestsPage) {
        commit('setIsArchivedRequestsPage', isArchivedRequestsPage);
    },

    setTableListOptions({state, commit, dispatch}, tableListOptions) {
        commit('setTableListOptions', tableListOptions);
    },

    /**
     * @param {Function} commit
     * @param {String} activeStatusKey
     */
    toggleStatusFilter({commit}, activeStatusKey): void {
        commit("toggleStatusFilter", activeStatusKey);
    },

    /**
     * @param {Function} commit
     */
    resetStatusFilters({commit}): void {
        commit("resetStatusFilters");
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     */
    loadRequests({state, commit, dispatch}) {
        commit('setBusy', true);

        let params = {
            dealerUid: state.dealerUid,
        };

        if (state.activeStatusKeys.length) {
            params.statuses = state.activeStatusKeys.join('-');
        }

        if (state.isArchivedRequestsPage) {
            params.isArchivedRequestsPage = 1;
        }

        const {sortBy, sortDesc, page, itemsPerPage} = state.tableListOptions;
        params.p = page ? page : 1;

        if (itemsPerPage) {
            params.ipp = itemsPerPage;
        }

        if (sortBy && sortBy.length) {
            params.sb = sortBy[0];
        }

        if (sortDesc && sortDesc.length) {
            params.st = sortDesc[0] ? 'desc' : 'asc';
        }

        let isSkipFinally = false;

        HttpClient
            .get('api.dealerRequest.collection', params)
            .then((response: SuccessResponse) => {
                let dealerRequests = response.dataCollection.items.map(item => ObjectToDealerRequestFactory.create(item)),
                    totalRequestsQty = response.dataCollection.paginator.itemsCount;

                if (dealerRequests.length === 0 && totalRequestsQty > 0 && page > 1) {
                    // there is no one request for current page => check for the last page
                    let lastPage = itemsPerPage ? Math.ceil(totalRequestsQty / itemsPerPage) : 1;
                    commit('setTableListOptions', Object.assign(state.tableListOptions, {page: lastPage}));
                    dispatch("loadRequests");
                    isSkipFinally = true;
                    return;
                }

                commit("setDealerRequests", dealerRequests);
                commit("setTotalRequestsQty", totalRequestsQty);

                setTimeout(() => {
                    commit("setReady", true);
                }, 500);
            }, error => {
            })
            .finally(() => {
                if (!isSkipFinally) {
                    commit("setBusy", false);
                }
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {DealerRequest} dealerRequest
     * @param {String} oldStatusKey
     */
    updateRequestStatus({state, commit, dispatch}, {dealerRequest, oldStatusKey}) {
        commit('setBusy', true);
        commit("setGlobalError", null);

        let routeParams = {
                dealerUid: state.dealerUid,
                dealerRequestId: dealerRequest.id,
            },
            params = {status: dealerRequest.status.value};

        let isSkipFinally = false;

        HttpClient
            .patch('api.dealerRequest.item.updateStatus', routeParams, params)
            .then((response: SuccessResponse) => {
                // new status was saved successfully => reload Requests list
                isSkipFinally = true;
                dispatch("loadRequests");
            }, (error: ErrorResponse) => {
                // new status wasn't saved
                resolveError(error, commit);

                let index = state.dealerRequests.indexOf(dealerRequest);

                if (index > -1) {
                    // reverse new status to previous value
                    dealerRequest.status = oldStatusKey;
                    state.dealerRequests.splice(index, 1, dealerRequest);
                }
            })
            .finally(() => {
                if (!isSkipFinally) {
                    commit("setBusy", false);
                }
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Number} dealerRequestId
     */
    loadComments({state, commit, dispatch}, dealerRequestId) {
        commit('setBusy', true);

        let params = {
            dealerUid: state.dealerUid,
            dealerRequestId: dealerRequestId,
        };

        HttpClient
            .get('api.dealerRequest.item.comment.collection', params)
            .then((response: SuccessResponse) => {
                let comments = response.dataCollection.items.map(item => ObjectToDealerRequestCommentFactory.create(item));

                let dealerRequest = state.dealerRequests.find(object => {
                        return object.id === dealerRequestId;
                    }),
                    index = state.dealerRequests.indexOf(dealerRequest);

                if (index > -1) {
                    dealerRequest.comments = comments;
                    state.dealerRequests.splice(index, 1, dealerRequest);
                }

                setTimeout(() => {
                    commit("setReady", true);
                }, 500);
            }, error => {
            })
            .finally(() => {
                commit("setBusy", false);
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Number} dealerRequestId
     * @param {Number|null} dealerRequestCommentId
     * @param {String} commentText
     */
    saveComment({state, commit, dispatch}, {dealerRequestId, dealerRequestCommentId, commentText}) {
        commit('setBusy', true);
        commit("setGlobalError", null);

        let routeParams = {
            dealerUid: state.dealerUid,
            dealerRequestId: dealerRequestId,
        };

        let params = {
            commentText: commentText,
        };

        let httpClient;

        if (!dealerRequestCommentId) {
            // add new comment
            httpClient = HttpClient.post('api.dealerRequest.item.comment.add', routeParams, params);
        } else {
            // update existing comment
            routeParams.dealerRequestCommentId = dealerRequestCommentId;
            httpClient = HttpClient.patch('api.dealerRequest.item.comment.update', routeParams, params);
        }

        let isSkipFinally = false;

        httpClient
            .then((response: SuccessResponse) => {
                isSkipFinally = true;
                // load updated comments list by dealerRequestId
                dispatch("loadComments", dealerRequestId);
            }, (error: ErrorResponse) => {
                //comment wasn't saved
                resolveError(error, commit);
            })
            .finally(() => {
                if (!isSkipFinally) {
                    commit("setBusy", false);
                }
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Number} dealerRequestId
     * @param {Number} dealerRequestCommentId
     */
    deleteComment({state, commit, dispatch}, {dealerRequestId, dealerRequestCommentId}) {
        commit('setBusy', true);
        commit("setGlobalError", null);

        let routeParams = {
            dealerUid: state.dealerUid,
            dealerRequestId: dealerRequestId,
            dealerRequestCommentId: dealerRequestCommentId,
        };

        let isSkipFinally = false;

        HttpClient
            .delete('api.dealerRequest.item.comment.delete', routeParams)
            .then((response: SuccessResponse) => {
                isSkipFinally = true;
                // load updated comments list by dealerRequestId
                dispatch("loadComments", dealerRequestId);
            }, (error: ErrorResponse) => {
                //comment wasn't saved
                resolveError(error, commit);
            })
            .finally(() => {
                if (!isSkipFinally) {
                    commit("setBusy", false);
                }
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Array} dealerRequestIds
     * @param {String} action
     *
     * @return {Promise}
     */
    actionOnRequests({state, commit, dispatch}, {dealerRequestIds, action}) {
        commit('setBusy', true);
        commit("setGlobalError", null);

        let routeParams = {
            dealerUid: state.dealerUid,
            strDealerRequestIds: dealerRequestIds.join('-'),
        };

        let isSkipFinally = false;
        let http,
            setDoneFunctName = 'setDone';

        if (action === 'delete') {
            http = HttpClient.delete('api.dealerRequest.item.delete', routeParams);
            setDoneFunctName += 'Delete';
        } else if (action === 'archive') {
            setDoneFunctName += 'Archive';
            http = HttpClient.patch('api.dealerRequest.item.updateArchived', routeParams, {isArchived: 1});
        } else if (action === 'restore') {
            setDoneFunctName += 'Archive';
            http = HttpClient.patch('api.dealerRequest.item.updateArchived', routeParams);
        }

        if (!http) {
            return;
        }

        commit(setDoneFunctName, false);

        return http
            .then((response: SuccessResponse) => {
                isSkipFinally = true;
                commit(setDoneFunctName, true);
                // load updated requests list
                dispatch("loadRequests");
            }, (error: ErrorResponse) => {
                //comment wasn't saved
                resolveError(error, commit);
            })
            .finally(() => {
                if (!isSkipFinally) {
                    commit("setBusy", false);
                }
            });
    },
};

let getters = {
    dateTimeFormat(state: Object) {
        return (dateTimeString: String) => {
            // Date time format is "1 Dec, 2021, 10:27 AM"
            let dateTimeObject = new Date(dateTimeString),
                dayMonth = dateTimeObject.toLocaleString(state.lang, {day: 'numeric', month: 'short'}),
                yearPlusTime = dateTimeObject.toLocaleString(state.lang, {
                    year: 'numeric',
                    hour: "numeric",
                    minute: "2-digit",
                    hour12: true
                });

            return dayMonth + ', ' + yearPlusTime;
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
