<template>
    <span class="translated">
        <template v-if="!init">
            <v-skeleton-loader type="text" :width="width"></v-skeleton-loader>
        </template>
        <template v-else-if="asHtml">
            <span v-html="$t(code, params)"></span>
        </template>
        <template v-else>
            {{ $t(code, params) }}
        </template>
    </span>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "TranslatedText",

        props: {
            code: {
                type: String,
                required: true
            },

            params: {
                type: Object,
                required: false,
                default: () => {
                }
            },

            width: {
                type: String,
                default: "75px"
            },

            asHtml: {
                type: Boolean,
                required: false,
            },
        },

        components: {},

        data() {
            return {};
        },

        computed: {
            ...mapState("global", ["init"])
        },
    };
</script>

<style scoped lang="scss">
    .translated::v-deep {
        .v-skeleton-loader__text {
            margin: 0;
        }
    }
</style>
