"use strict";

import Vue from "vue";
import VueI18n from "vue-i18n";
import HttpClient from "./HttpClient";
import {EventBus, events} from "./EventBus";

Vue.use(VueI18n);

export const i18n = new VueI18n({
});

const loadedLocales = [];

function setI18nLocale(locale) {
    if (i18n.locale !== locale) {
        EventBus.emit(events.LOCALE_CHANGE, locale);
    }

    i18n.locale = locale;
}

export function loadLocaleAsync(locale) {
    return new Promise((resolve, reject) => {
        if (loadedLocales.includes(locale)) {
            setI18nLocale(locale);
            resolve();
        } else {
            HttpClient
                .get("api.translation_collection", {_locale: locale})
                .then(response => {
                    i18n.setLocaleMessage(locale, ...response.dataCollection.items);
                    loadedLocales.push(locale);
                    setI18nLocale(locale);
                    resolve()
                });
        }
    });
}
