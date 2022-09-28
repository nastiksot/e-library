"use strict";

import HttpClient from "../../modules/HttpClient";
import ObjectToFilterGroupFactory from "../../models/filter-group/object-to-filter-group-factory";
import Vue from "vue";
import _forEach from "lodash/forEach";
import FilterGroup from "../../models/filter-group";

let state = {
    filterGroups: [],
    selectedFilters: {}
};

let mutations = {
    setFilterGroups(state: Object, filterGroups: Array<FilterGroup>): void {
        state.filterGroups = filterGroups;
    },

    /**
     * @param {Object} state
     * @param {Number} filterGroupId
     * @param {Number} filterId
     */
    toggleFilter(state: Object, {filterGroupId, filterId}): void {
        if (state.selectedFilters[filterGroupId] === undefined) {
            Vue.set(state.selectedFilters, filterGroupId, []);
        }

        let indexOf = state.selectedFilters[filterGroupId].indexOf(filterId);

        if (-1 !== indexOf) {
            state.selectedFilters[filterGroupId].splice(indexOf, 1);
        } else {
            state.selectedFilters[filterGroupId].push(filterId);
        }
    },

    /**
     * @param {Object} state
     * @param {Number|null} filterGroupId
     */
    resetFilters(state: Object, {filterGroupId = null}): void {
        if (null === filterGroupId) {
            _forEach(state.selectedFilters, (item, key) => {
                state.selectedFilters[key].splice(0, state.selectedFilters[key].length);
            });
        } else {
            if (state.selectedFilters[filterGroupId] !== undefined) {
                state.selectedFilters[filterGroupId].splice(0, state.selectedFilters[filterGroupId].length);
            }
        }
    }
};

let actions = {
    /**
     * @param {Function} commit
     */
    loadFilters({commit}): void {
        HttpClient
            .get("api.filter_collection")
            .then(response => {
                let filterGroups = response.dataCollection.items.map(item => ObjectToFilterGroupFactory.create(item));

                commit("setFilterGroups", filterGroups);
            }, error => {
            });
    },

    /**
     * @param {Function} commit
     * @param {Number} filterGroupId
     * @param {Number} filterId
     */
    toggleFilter({commit}, {filterGroupId, filterId}): void {
        commit("toggleFilter", {filterGroupId, filterId});
    },

    /**
     * @param {Function} commit
     * @param {Number|null} filterGroupId
     */
    resetFilters({commit}, filterGroupId: ?Number = null): void {
        commit("resetFilters", {filterGroupId});
    }
};

let getters = {
    filtersApplied(state: Object): Array {
        let result = [];

        _forEach(state.selectedFilters, (item, key) => {
            result.push(...state.selectedFilters[key]);
        });

        return result;
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
