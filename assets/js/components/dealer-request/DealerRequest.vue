<template>
    <div class="dealer-request">
        <v-dialog
            v-model="dialog"
            width="500"
            overlay-color="#000"
            overlay-opacity="0.65"
            content-class="buy-now-dialog"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn class="buy-now__btn--open"
                       depressed
                       large
                       color="orange"
                       v-bind="attrs"
                       v-on="on">
                    <v-icon>location-icon</v-icon>
                    <translated-text code="DEALER_REQUEST.FORM.BUTTON.OPEN"/>
                </v-btn>
            </template>

            <v-card>
                <v-card-title>
                    <v-btn icon small class="v-dialog__close" @click="dialog = false">
                        <v-icon>close-icon</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text>
                    <dealer-request-form @close="dialog = false"
                                         @done="dealerRequestFormDone"
                    />
                </v-card-text>

            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import DealerRequestStore from "./store";
    import DealerRequestForm from "./DealerRequestForm";
    import TranslatedText from "../translated-text/TranslatedText";
    import AlertMessageStore from "../alert-message/store";

    export default {

        name: "DealerRequest",

        props: {},

        components: {DealerRequestForm, TranslatedText},

        data() {
            return {
                dialog: false
            };
        },

        computed: {
            ...mapState("global", ["me"]),
        },

        watch: {

            dialog(newValue, oldValue) {

                // dialog open
                if (true === newValue) {
                    this.clearAlertMessages();
                }

                // dialog close
                if (false === newValue) {
                    // reset done
                    this.setDone(false);
                }
            },
        },

        methods: {
            ...mapActions("dealerRequest", [
                "setDone",
            ]),
            ...mapActions("alertMessage", [
                "clearAlertMessages",
                "addInfoAlertMessage"
            ]),

            dealerRequestFormDone(sendCopy) {
                this.dialog = false;
                let message = sendCopy
                    ? this.$t('DEALER_REQUEST.MESSAGE.DONE_AND_COPY')
                    : this.$t('DEALER_REQUEST.MESSAGE.DONE');

                this.addInfoAlertMessage(message);
            }
        },

        beforeCreate() {
            if (!this.$store.hasModule("dealerRequest")) {
                this.$store.registerModule("dealerRequest", DealerRequestStore);
            }
            if (!this.$store.hasModule("alertMessage")) {
                this.$store.registerModule("alertMessage", AlertMessageStore);
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../../scss/base";
    @import "../../../scss/components/dialog";

    .dealer-request {
        width: 100%;

        .v-btn {
            width: 100%;
        }
    }

</style>
