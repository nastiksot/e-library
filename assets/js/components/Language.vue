<template>
    <div class="language">
        <v-menu
            offset-y v-if="availableLocalesQty"
            z-index="20"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    v-if="activeLocaleData"
                    text
                    v-bind="attrs"
                    v-on="on"
                >
                    {{ activeLocaleData.title }}
                    <v-icon>chevron-down-icon</v-icon>
                </v-btn>
            </template>
            <v-list>
                <v-list-item v-for="(locale) in availableLocales" @click="changeLocale(locale)">
                  {{locale.title}}
                </v-list-item>
            </v-list>
        </v-menu>
    </div>
</template>

<script>
    import {mapState, mapActions} from "vuex";

    export default {
        name: "Language",

        props: {},

        components: {},

        data() {
            return {
                dialog: false
            };
        },

        computed: {
            ...mapState("global", ["currentLocale", "availableLocales"]),

            availableLocalesQty() {
                return this.availableLocales.length;
            },
            activeLocaleData() {
                let activeLocaleData;

                for (let i = 0; i < this.availableLocalesQty; i++) {
                    if (this.availableLocales[i].locale === this.currentLocale) {
                        activeLocaleData = this.availableLocales[i];
                        break;
                    }
                }

                return activeLocaleData;
            },
        },

        watch: {},

        methods: {
            ...mapActions("global", ["setCurrentLocale"]),

            changeLocale(locale) {
                if (locale.prefix !== this.activeLocaleData.prefix) {
                    this.setCurrentLocale(locale.locale);
                    // redirect to the same page in selected locale. Eg "/de-de/page" => "/en-gb/page"
                    let currentUrl = window.location.href.replace(window.location.origin, ''),
                        regex = /\/[a-z]{2}-[a-z]{2}/ig;
                    window.location.href = currentUrl.replace(regex, '/' + this.activeLocaleData.prefix);
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../scss/base";

    .language{
        padding-left: 10px;
        position: relative;

        .v-btn{
            height: 36px;
            padding: 0;

            .v-icon{
                font-size: 8px;
                margin-left: 10px;
                margin-top: 3px;
            }
        }

        &:before{
            content: "";
            width: 1px;
            height: 21px;
            position: absolute;
            background: #ced4da;
            left: 0;
            top: 0;
            bottom: 0;
            margin: auto;
        }

        @media (max-width: 500px) {
            display: none;
        }

        .v-list {
            &-item {
                cursor: pointer;
            }
        }
    }
</style>
