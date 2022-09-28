<template>
    <div class="alert-message">

        <v-alert v-for="(alertMessage, index) in alertMessages"
                 :key="index"
                 :type="alertMessage.type"
                 :color="alertMessage.color"
                 dismissible
                 tile
                 dense
        >

            <v-icon class="check-icon">round-check-icon</v-icon>
            <div v-html="alertMessage.message"></div>

            <template v-slot:close="{ toggle }">
                <v-btn @click="closeAlertMessage(index)">
                    <v-icon>close-icon</v-icon>
                </v-btn>
            </template>
        </v-alert>

    </div>
</template>

<script>
    import {mapState, mapActions} from "vuex";
    import AlertMessageStore from "./store";

    export default {

        name: "AlertMessage",

        props: {},

        components: {},

        data() {
            return {};
        },

        computed: {
            ...mapState("alertMessage", [
                "alertMessages"
            ]),
        },

        watch: {},

        methods: {
            ...mapActions("alertMessage", [
                "removeAlertMessage",
            ]),

            closeAlertMessage(index) {
                this.removeAlertMessage(index);
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("alertMessage")) {
                this.$store.registerModule("alertMessage", AlertMessageStore);
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .alert-message {
        .v-alert {
            padding: 20px 10px;
            position: relative;

            &::v-deep {
                .v-icon {
                    display: none;
                }

                .v-alert__wrapper {
                    justify-content: center;
                    padding-right: 20px;
                }

                .v-alert__content {
                    flex: 0 0 auto;
                    font-family: $fontSomfySansMedium;
                    font-size: 14px;
                    line-height: 19px;
                    display: flex;
                    align-items: center;

                    .v-icon {
                        display: block;
                        color: #009845;
                        margin-right: 5px;
                        font-size: 24px;

                        &:before {
                            background: #fff;
                            border-radius: 50%;
                        }
                    }
                }
            }
        }
        .v-btn {
            background: no-repeat;
            box-shadow: none;
            padding: 0;
            min-width: auto;
            height: auto;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
        }
    }
</style>
