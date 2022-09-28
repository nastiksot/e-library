import PortalVue from 'portal-vue';
import Vue from 'vue';
import VueFullPage from 'vue-fullpage.js';
import FooterPartners from '../components/footer/FooterPartners';
import SocialMedia from '../components/social-media/SocialMedia';
import HttpClient from '../modules/HttpClient';
import RouteGenerator from '../modules/RouteGenerator';
import Store from '../modules/Store';
import {i18n, loadLocaleAsync} from '../modules/VueI18n';
import Vuetify from '../modules/Vuetify';

Vue.use(VueFullPage);
Vue.use(PortalVue);

export default function createApp(components) {
    let locale = document.querySelector('html').getAttribute('lang');
    let dealerUid = document.querySelector('html').dataset.dealerUid;

    RouteGenerator.loadRoutes()
        .then(() => {
            RouteGenerator.setLocale(locale);
            HttpClient.setLocale(locale);

            new Vue({
                el: '#wrapper',
                store: Store,
                vuetify: Vuetify,
                i18n,

                components: {
                    FooterPartners,
                    SocialMedia,
                    ...components,
                },

                created() {
                    this.$store.commit('global/setInit', false);

                    this.$store.dispatch('global/setCurrentLocale', locale);
                    this.$store.dispatch('global/loadAvailableLocales');

                    if (dealerUid) {
                        this.$store.dispatch('global/setIsDealerMode', true);
                        this.$store.dispatch('global/loadDealer', dealerUid);
                    }

                    Promise
                        .all([
                            this.$store.dispatch('global/loadMe'),
                            loadLocaleAsync(locale),
                        ])
                        .then(() => {
                            this.$store.commit('global/setInit', true);
                        });
                },
            });
        });
}
