'use strict';

import HttpClient from '../../modules/HttpClient';
import UserAccount from "../../models/user-account";
import ObjectToUserAccountFactory from "../../models/user-account/object-to-user-account-factory";
import ObjectToLocaleFactory from "../../models/locale/object-to-locale-factory";
import Locale from "../../models/locale/index";
import SuccessResponse from "../../models/transport-response/success-response";
import ObjectToDealerFactory from "../../models/dealer/object-to-dealer-factory";
import Dealer from "../../models/dealer";
import sha256 from 'js-sha256';

let state = {
    init: false,
    loading: false,

    /** @type UserAccount */
    me: {},
    currentLocale: null,
    isDealerMode: false,

    /** @type Array<Locale> */
    availableLocales: [],

    /** @type Dealer */
    dealer: {},
};

let mutations = {
    setInit(state: Object, init: Boolean) {
        state.init = init;
    },

    setLoading(state: Object, loading: Boolean) {
        state.loading = loading;
    },

    setMe(state: Object, me: UserAccount) {
        state.me = me;
    },

    setCurrentLocale(state: Object, currentLocale: ?String) {
        state.currentLocale = currentLocale;
    },

    setIsDealerMode(state: Object, isDealerMode: Boolean) {
        state.isDealerMode = isDealerMode;
    },

    setAvailableLocales(state: Object, availableLocales: Array<Locale>) {
        state.availableLocales = availableLocales;
    },

    setDealer(state: Object, dealer: Dealer) {
        state.dealer = dealer;
    },
};

let addLocaleDataToTC = function (state, availableLocales) {
    if (window.tc_vars === undefined || typeof (tC) === 'undefined') {
        return;
    }

    for (let i in availableLocales) {
        let curLocale = availableLocales[i];

        if (curLocale.locale === state.currentLocale) {
            window.tc_vars.env_country = curLocale.enCountry;
            window.tc_vars.env_language = state.currentLocale.toUpperCase().replace('_', '-');
            tC.container.reload();
            break;
        }
    }
};

let actions = {
    /**
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {Boolean} isLoading
     */
    setLoading({commit, dispatch}, {isLoading}) {
        commit('setLoading', isLoading);
    },

    loadMe({commit}): Promise {
        return HttpClient
            .get('api.me')
            .then((response: SuccessResponse) => {
                let userAccount = ObjectToUserAccountFactory.create(response.data);
                commit('setMe', userAccount);

                if (userAccount.id && window.tc_vars !== undefined && typeof(tC) !== 'undefined') {
                    // add user data to TC Data Layer
                    window.tc_vars.user_id = userAccount.id;
                    window.tc_vars.user_email_sha256 = sha256(userAccount.email);
                    tC.container.reload();
                }
            });
    },

    loadDealer({commit}, dealerUid): Promise {
        return HttpClient
            .get('api.dealers.details', {dealerUid: dealerUid})
            .then((response: SuccessResponse) => {
                let dealer = ObjectToDealerFactory.create(response.data);
                commit('setDealer', dealer);
            });
    },

    setCurrentLocale({commit}, currentLocale: ?String) {
        commit('setCurrentLocale', currentLocale);
    },

    setIsDealerMode({commit}, isDealerMode: Boolean) {
        commit('setIsDealerMode', isDealerMode);
    },

    loadAvailableLocales({commit}): Promise {
        return HttpClient
            .get('api.dictionary.locales-list')
            .then((response: SuccessResponse) => {
                let availableLocales = response.dataCollection.items.map(item => ObjectToLocaleFactory.create(item));
                commit('setAvailableLocales', availableLocales);

                addLocaleDataToTC(state, availableLocales);
            });
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
