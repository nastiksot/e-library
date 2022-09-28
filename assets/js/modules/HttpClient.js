'use strict';

import Axios from 'axios';
import ErrorResponse from '../models/transport-response/error-response';
import SuccessResponse from '../models/transport-response/success-response';
import {EventBus, events} from './EventBus';
import RouteGenerator from './RouteGenerator';

let currentLocale = 'de_DE';

const HttpClient = Axios.create({
    baseURL: '/',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Cache-Control': 'no-cache',
    },
    timeout: 30000,
    // adapter: throttleAdapterEnhancer(cacheAdapterEnhancer(Axios.defaults.adapter)),
    // adapter: throttleAdapterEnhancer(Axios.defaults.adapter),
});

EventBus.on(events.LOGIN_SUCCESS, function (token) {
    HttpClient.defaults.headers.common['Authorization'] = 'Bearer ' + token;
});

EventBus.on(events.LOGOUT_SUCCESS, function () {
    EventBus.emit(events.LOGIN_REQUIRED);
});

EventBus.on(events.LOGIN_REQUIRED, function () {
    delete HttpClient.defaults.headers.common['Authorization'];
    console.log('login required');
    // window.location.href = '/auth/logout';
});

EventBus.on(events.LOCALE_CHANGE, function (locale) {
    currentLocale = locale;
});

HttpClient.interceptors.response.use(response => {
    return new SuccessResponse(response);
}, error => {
    if (error.response.status === 401) {
        EventBus.emit(events.LOGIN_REQUIRED);
    }

    throw new ErrorResponse(error.response);
});

export default {
    setLocale(locale) {
        currentLocale = locale;
    },

    /**
     * @param {String} routeName
     * @param {Object} routeParams
     * @returns {Promise<SuccessResponse>}
     */
    get(routeName, routeParams = {}) {
        return HttpClient.get(RouteGenerator.generate(routeName, routeParams), {
            headers: {
                'Accept-Language': currentLocale,
            },
        });
    },

    /**
     * @param {String} routeName
     * @param {Object} routeParams
     * @param {Object} data
     * @returns {Promise<SuccessResponse>}
     */
    post(routeName, routeParams, data) {
        return HttpClient.post(RouteGenerator.generate(routeName, routeParams), data, {
            headers: {
                'Accept-Language': currentLocale,
            },
        });
    },

    /**
     * @param {String} routeName
     * @param {Object} routeParams
     * @param {Object} data
     * @returns {Promise<SuccessResponse>}
     */
    put(routeName, routeParams, data) {
        return HttpClient.put(RouteGenerator.generate(routeName, routeParams), data, {
            headers: {
                'Accept-Language': currentLocale,
            },
        });
    },

    /**
     * @param {String} routeName
     * @param {Object} routeParams
     * @returns {Promise<SuccessResponse>}
     */
    delete(routeName, routeParams) {
        return HttpClient.delete(RouteGenerator.generate(routeName, routeParams), {
            headers: {
                'Accept-Language': currentLocale,
            },
        });
    },

    /**
     * @param {String} routeName
     * @param {Object} routeParams
     * @param {Object} data
     * @returns {Promise<SuccessResponse>}
     */
    patch(routeName, routeParams, data) {
        return HttpClient.patch(RouteGenerator.generate(routeName, routeParams), data, {
            headers: {
                'Accept-Language': currentLocale,
            },
        });
    },
};
