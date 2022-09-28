<template>
    <div class="dealer-request-form">

        <h2 class="dealer-request-form__title">
            <translated-text code="DEALER_REQUEST.FORM.TITLE"/>
        </h2>
        <h3 class="dealer-request-form__subtitle">
            <translated-text code="DEALER_REQUEST.FORM.SUB_TITLE"/>
        </h3>

        <v-alert v-if="invalid && globalError" type="error" dense text>
            <translated-text :code="globalError"/>
        </v-alert>
        <v-form @submit.prevent="submitDealerRequestForm" :class="{'progress': busy}">

            <v-progress-circular v-if="busy" indeterminate color="orange" class="spinner" size="70"/>

            <v-text-field v-model="contactName"
                          :error="null !== errors.contactName"
                          :error-messages="errors.contactName"
                          hide-details="auto"
                          outlined
            >
                <template v-slot:label>
                    <translated-link prefix="DEALER_REQUEST.FORM.LABEL.CONTACT_NAME" suffix="*"/>
                </template>
            </v-text-field>

            <v-text-field v-model="email"
                          :error="null !== errors.email"
                          :error-messages="errors.email"
                          hide-details="auto"
                          outlined
            >
                <template v-slot:label>
                    <translated-link prefix="DEALER_REQUEST.FORM.LABEL.EMAIL" suffix="*"/>
                </template>
            </v-text-field>

            <v-text-field v-model="phone"
                          :error="null !== errors.phone"
                          :error-messages="errors.phone"
                          hide-details="auto"
                          outlined
            >
                <template v-slot:label>
                    <translated-link prefix="DEALER_REQUEST.FORM.LABEL.PHONE"/>
                </template>
            </v-text-field>

            <v-text-field v-model="address"
                          :error="null !== errors.address"
                          :error-messages="errors.address"
                          hide-details="auto"
                          outlined
            >
                <template v-slot:label>
                    <translated-link prefix="DEALER_REQUEST.FORM.LABEL.ADDRESS"/>
                </template>
            </v-text-field>

            <v-textarea v-model="message"
                        :error="null !== errors.message"
                        :error-messages="errors.message"
                        hide-details="auto"
                        outlined
                        :placeholder="init ? $tc('DEALER_REQUEST.FORM.MESSAGE_PLACEHOLDER') : null ">
                <template v-slot:label>
                    <translated-link prefix="DEALER_REQUEST.FORM.LABEL.MESSAGE"/>
                </template>
            </v-textarea>

            <v-checkbox v-model="sendCopy"
                        :error="null !== errors.sendCopy"
                        :error-messages="errors.sendCopy"
                        hide-details
            >
                <template v-slot:label>
                    <translated-text code="DEALER_REQUEST.FORM.LABEL.SEND_COPY"/>
                </template>
            </v-checkbox>

                <div class="dealer-request-form__required">
                <translated-text code="GENERAL.REQUIRED_FIELDS"/>
            </div>

            <v-btn type="submit" :disabled="busy"
                       block large depressed color="orange">
                <translated-text code="DEALER_REQUEST.FORM.BUTTON.SUBMIT"/>
            </v-btn>

        </v-form>

    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import DealerRequestStore from "./store";
    import WishListStore from "../wish-list/store";
    import AlertMessageStore from "../alert-message/store";
    import TranslatedText from "../translated-text/TranslatedText";
    import TranslatedLink from "../translated-text/TranslatedLink";

    export default {

        name: "DealerRequestForm",

        props: {},

        components: {TranslatedLink, TranslatedText},

        data() {
            return {
                // form
                contactName: null,
                email: null,
                phone: null,
                address: null,
                message: null,
                sendCopy: true,
            };
        },

        computed: {
            ...mapState("global", ["init"]),
            ...mapState("global", ["dealer"]),
            ...mapState("wishList", ["wishList"]),
            ...mapState("dealerRequest", {
                done: state => state.done,
                busy: state => state.busy,
                invalid: state => state.invalid,
                globalError: state => state.globalError,
                fieldErrors: state => state.fieldErrors,
            }),

            errors() {
                return {
                    contactName: this.fieldErrors.hasOwnProperty('contactName') ? this.fieldErrors.contactName : null,
                    email: this.fieldErrors.hasOwnProperty('email') ? this.fieldErrors.email : null,
                    phone: this.fieldErrors.hasOwnProperty('phone') ? this.fieldErrors.phone : null,
                    address: this.fieldErrors.hasOwnProperty('address') ? this.fieldErrors.address : null,
                    message: this.fieldErrors.hasOwnProperty('message') ? this.fieldErrors.message : null,
                    sendCopy: this.fieldErrors.hasOwnProperty('sendCopy') ? this.fieldErrors.sendCopy : null,
                }
            },
        },

        watch: {},

        methods: {
            ...mapActions("dealerRequest", [
                "submitDealerRequest",
            ]),
            ...mapActions("alertMessage", [
                "addInfoAlertMessage"
            ]),

            resetForm() {
                this.contactName = null;
                this.email = null;
                this.phone = null;
                this.address = null;
                this.message = null;
                this.sendCopy = true;
            },

            async submitDealerRequestForm() {
                await this.submitDealerRequest({
                    dealerUid: this.dealer.uid,
                    wishListUid: this.wishList.uid,
                    contactName: this.contactName,
                    email: this.email,
                    phone: this.phone,
                    address: this.address,
                    message: this.message,
                    sendCopy: this.sendCopy,
                });

                if (this.done) {
                    this.$emit("done", this.sendCopy);
                    this.resetForm();
                }
            },

            clickClose() {
                this.$emit("close");
                this.resetForm();
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("dealerRequest")) {
                this.$store.registerModule("dealerRequest", DealerRequestStore);
            }
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
            if (!this.$store.hasModule("alertMessage")) {
                this.$store.registerModule("alertMessage", AlertMessageStore);
            }
        },
        created() {
            this.resetForm();
        }
    }
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .dealer-request-form {
        &__title {
            color: #485c74;
            text-align: center;
            @include normal-font(28px, 32px);
            font-family: $fontSomfySansLight;
            padding: 0;
            @include justify-content(center);
            margin-bottom: 10px;
        }

        &__subtitle {
            color: #485c74;
            text-align: center;
            font-family: $fontSomfySansLight;
            @include normal-font(22px, 32px);
            margin-bottom: 42px;
        }

        &__required {
            @include normal-font(12px, 14px);
            font-family: $fontSomfySansLight;
            color: #485C74;
            margin-bottom: 15px;
        }

        .v-input {
            padding: 0!important;
            margin-bottom: 31px;

            &::v-deep {
                .v-text-field__details {
                    position: absolute;
                    bottom: -18px;
                }
            }

            &.v-textarea {
                min-height: 85px;

                &::v-deep {
                    textarea {
                        height: 85px;
                    }

                    .v-text-field__details {
                        bottom: -34px;
                    }
                }
            }

            &.v-input--checkbox {
                margin-bottom: 16px;
            }
        }

        .dealer-request-form__required {
            margin-bottom: 10px;
        }
    }

</style>
