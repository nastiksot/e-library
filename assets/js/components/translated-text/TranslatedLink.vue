<template>

    <span class="translated-link">

        <template v-if="!init">
            <v-skeleton-loader type="text" :width="width"></v-skeleton-loader>
        </template>

        <template v-else>

            <slot name="prefix">
                {{ prefixText }}
            </slot>

            <v-tooltip bottom :content-class="contentClass">
                <template v-slot:activator="{ on }">
                    <a target="_blank"
                       :href="href"
                       @click.stop.prevent="click"
                       v-on="on"
                    >
                        <slot>
                            {{ labelText }}
                        </slot>
                    </a>
                </template>

                <slot name="title">
                    {{ titleText }}
                </slot>

            </v-tooltip>

            <slot name="suffix">
                {{ suffixText }}
            </slot>

        </template>
    </span>

</template>

<script>
    import {mapState} from "vuex";
    import RouteGenerator from "../../modules/RouteGenerator";

    export default {
        name: "TranslatedLink",

        props: {
            route: {
                type: String,
                required: false,
                default: null
            },

            routeParams: {
                type: Object,
                required: false,
                default: () => {}
            },

            link: {
                type: String,
                required: false,
                default: null
            },

            prefix: {
                type: String,
                required: false,
                default: null
            },

            label: {
                type: String,
                required: false,
                default: null
            },

            title: {
                type: String,
                required: false,
                default: null
            },

            suffix: {
                type: String,
                required: false,
                default: null
            },

            width: {
                type: String,
                default: "75px"
            },

        },

        components: {},

        data() {
            return {};
        },

        computed: {

            ...mapState("global", ["init"]),

            href() {

                if (this.route) {
                    return RouteGenerator.generate(this.route, this.routeParams);
                }

                if (this.link) {
                    return this.link;
                }

                return '#';
            },

            prefixText() {
                return this.prefix ? this.$tc(this.prefix) : null
            },

            labelText() {
                return this.label ? this.$tc(this.label) : null
            },

            titleText() {
                return this.title ? this.$tc(this.title) : null
            },

            suffixText() {
                return this.suffix ? this.$tc(this.suffix) : null
            },

            contentClass() {
                return null === this.title ? 'translated-link__no-title' : null;
            }
        },

        methods: {
            click() {
                this.$emit('click');
            }
        }

    };
</script>

<style scoped lang="scss">

    .translated-link {
        &__no-title {
            display: none !important;
        }
    }

    .translated-link::v-deep {
        .v-skeleton-loader__text {
            margin: 0;
        }
    }
</style>
