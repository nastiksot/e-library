'use strict';

import _forEach from "lodash/forEach";

let state = {
    notification: null,
};

let requiredNotificationProperties = ['message'];

let mutations = {
    setNotification(state: Object, notification: Object) {
        let absentProperties = [];

        _forEach(requiredNotificationProperties, (propertyName) => {
                if (!notification[propertyName]) {
                    absentProperties.push(propertyName);
                }
        });

        let absentPropertiesQty = absentProperties.length;

        if (absentPropertiesQty) {
            console.info('Error: Following required properties ' + (absentPropertiesQty === 1 ? 'is' : 'are') + ' missing: ' + absentProperties.join(', ') + '.');
            console.info('notification = ', notification);
            return;
        }

        state.notification = notification;
    },

    clearNotification(state: Object) {
        state.notification = null;
    },
};

let actions = {
    setNotification({commit}, notification: ?Object) {
        commit('setNotification', notification);
    },

    clearNotification({commit}, notification: ?Object) {
        commit('clearNotification', notification);
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
