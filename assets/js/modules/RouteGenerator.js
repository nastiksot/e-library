import RouteGenerator from "/vendor/friendsofsymfony/jsrouting-bundle/Resources";
import {EventBus, events as EVENTS} from "./EventBus";

EventBus.on(EVENTS.LOCALE_CHANGE, function (locale) {
    RouteGenerator.setLocale(locale);
});

export default {
    /**
     * @param {String} name
     * @param {Object<String, String>} params
     * @param {Boolean} absolute
     * @return {String}
     */
    generate(name, params = {}, absolute = false) {
        let oldLocale = undefined;

        if (params._locale) {
            oldLocale = RouteGenerator.getLocale();
            RouteGenerator.setLocale(params._locale);
            delete params['_locale'];
        }

        let url = RouteGenerator.generate(name, params, absolute);

        if (undefined !== oldLocale) {
            RouteGenerator.setLocale(oldLocale);
        }

        return url;
    },

    setLocale(locale) {
        RouteGenerator.setLocale(locale);
    },

    async loadRoutes() {
        const response = await fetch("/data/routes.json");
        const routes = await response.json();

        RouteGenerator.setRoutingData(routes);
    }
};
