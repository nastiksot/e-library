'use strict';

import minifyTheme from 'minify-css-string';
import Vue from 'vue';
import Vuetify, {
    VApp,
    VAppBar,
    VContainer,
    VFooter,
    VMain,
    VNavigationDrawer,
} from 'vuetify/lib';

Vue.use(Vuetify, {
    components: {
        VApp,
        VAppBar,
        VContainer,
        VFooter,
        VMain,
        VNavigationDrawer,
    },
});

const opts = {
    icons: {
        iconfont: 'mdiSvg',
    },
    theme: {
        dark: false,
        default: 'light',
        options: {minifyTheme},
        themes: {
            light: {
                primary: '#0093dd',
                secondary: '#424242',
                accent: '#82b1ff',
                error: '#ff5252',
                info: '#2196f3',
                success: '#4caf50',
                warning: '#fb8c00',
            },
        },
    },
    breakpoint: {
        mobileBreakpoint: 992,
    },
};

export default new Vuetify(opts);
