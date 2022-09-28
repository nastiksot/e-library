import _upperFirst from "lodash/upperFirst";

let mapComputed = function (namespace, ...names) {
    let result = {};

    names.forEach(function (item) {
        result[item] = {
            get() {
                return this.$store.state[namespace][item];
            },
            set(value) {
                if (item !== 'errors' && item !== 'globalError') {
                    this.$store.state[namespace].errors[item] = null;
                    this.$store.state[namespace].globalError = null;
                }
                this.$store.commit(namespace + "/set" + _upperFirst(item), value);
            },
        };
    });

    return result;
};

let generateMutations = function (...names) {
    let result = {};

    names.forEach(function (item) {
        result["set" + _upperFirst(item)] = function (state, value) {
            state[item] = (value === "" ? null : value);
        };
    });

    return result;
};

let generateActions = function (...names) {
    let result = {};

    names.forEach(function (item) {
        result["set" + _upperFirst(item)] = function ({commit}, data) {
            commit("set" + _upperFirst(item), data);
        };
    });

    return result;
};

let generateGetters = function (...names) {
    let result = {};

    names.forEach(function (item) {
        result["get" + _upperFirst(item)] = function (state, getters, rootState) {
            return function () {
                return state[item];
            };
        };
    });

    return result;
};

export {
    mapComputed,
    generateMutations,
    generateActions,
    generateGetters,
};

export default {
    mapComputed: mapComputed,
    generateMutations: generateMutations,
    generateActions: generateActions,
    generateGetters: generateGetters,
};
