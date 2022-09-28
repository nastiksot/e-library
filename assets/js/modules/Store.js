'use strict';

import Vue from 'vue';
import Vuex, {Store} from 'vuex';
import global from '../components/global/store';
import VueCookies from 'vue-cookies'

Vue.use(Vuex);
Vue.use(VueCookies)

export default new Store({
    modules: {
        global,
    },
});
