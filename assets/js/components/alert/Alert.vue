<template>
    <div class="alert-component">
        <v-alert
            :type="type"
            :icon="icon"
            :color="color"
            dismissible
            tile
            dense
        >
            <div v-html="message"></div>

            <template v-slot:close="{ toggle }">
                <v-btn @click="close">
                    <v-icon>close-icon</v-icon>
                </v-btn>
            </template>
        </v-alert>
        </div>
</template>

<script>

import {mapActions} from "vuex";
import AlertStore from "../../components/alert/store";

export default {
    name: "Alert",

    props: {
        type: {
            type: String | null,
            required: false,
            default: 'info',
            validator: (value) => {
                return ['info', 'success', 'warning', 'error'].includes(value.toLowerCase());
            },
        },
        icon: {
            type: String | null,
            required: false,
            default: false,
        },
        color: {
            type: String | null,
            required: false,
            default: '#fcac22',
        },
        message: {
            type: String,
            required: true,
        },

    },

    components: {},

    data() {
        return {};
    },

    computed: {},

    watch: {},

    methods: {
        ...mapActions("alert", ["clearNotification"]),

        close() {
            this.clearNotification();
        }
    },

    beforeCreate() {
        if (!this.$store.hasModule("alert")) {
            this.$store.registerModule("alert", AlertStore);
        }
    }
}
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .alert-component {
        .v-alert {
            margin: 0;
            padding: 10px 15px;
            text-align: center;
            color: #fff;
            box-shadow: none !important;
            font-size: 14px;
            border-radius: 0;

            &::v-deep {
                a {
                    color: #fff;
                    text-decoration: underline;

                    &:hover {
                        text-decoration: none;
                    }
                }
            }

            .v-btn {
                background: transparent;
                height: auto !important;
                min-width: auto;
                padding: 0;
                box-shadow: none;

                .v-icon {
                    margin: 0;
                }
            }

            @media (max-width: 991px) {
                right: 0;
            }
        }
    }
</style>
