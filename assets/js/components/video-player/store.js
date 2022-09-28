"use strict";

import youtubeParser from "../../helpers/youtubeParser";

let state = {
    videoUrl: null,
    videoCode: null,
};

let mutations = {
    setVideoUrl(state: Object, url: ?String) {
        state.videoUrl = url;
        state.videoCode = url ? (youtubeParser(url) || null) : null;
    },
};

let actions = {
    /**
     * @param {Function} commit
     * @param {String} url
     */
    setVideoUrl({commit}, url: ?String) {
        commit("setVideoUrl", url);
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
