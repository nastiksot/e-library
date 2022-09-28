<template>
    <div class="user-register">

        <template v-if="done">
            <h2 class="user-register__title">{{ doneMessage }}</h2>

            <v-alert type="success" dense text>
                <translated-text code="USER.REGISTER.MESSAGE.SUCCESS"/>
            </v-alert>
        </template>

        <template v-else>

            <h2 class="user-register__title"><translated-text :code="titleMessage"/></h2>
            <h3 class="user-register__subtitle"><translated-text :code="subTitleMessage"/></h3>

            <v-alert v-if="invalid && globalError" type="error" dense text>
                <translated-text :code="globalError"/>
            </v-alert>
            <v-form @submit.prevent="submitUserRegisterForm" :class="{'progress': busy}">

                <v-progress-circular v-if="busy" indeterminate color="orange" class="spinner" size="70"/>

                <v-text-field v-model="email"
                              :error="Boolean(errors.email)"
                              :error-messages="errors.email"
                              hide-details="auto"
                              outlined
                >
                    <template v-slot:label>
                        <translated-link prefix="USER.LABEL.EMAIL" suffix="*"/>
                    </template>
                </v-text-field>

                <v-checkbox v-model="acceptPrivacyPolicy"
                            :error="Boolean(errors.acceptPrivacyPolicy)"
                            :error-messages="errors.acceptPrivacyPolicy"
                            hide-details
                            class="mt-1"
                >
                    <template v-slot:label>
                        <translated-link @click="clickAcceptPrivacyPolicy"
                                         prefix="USER.ACCEPT_PRIVACY_POLICY.PREFIX"
                                         label="USER.ACCEPT_PRIVACY_POLICY.LABEL"
                                         title="USER.ACCEPT_PRIVACY_POLICY.TITLE"
                                         suffix="USER.ACCEPT_PRIVACY_POLICY.SUFFIX"
                        />
                    </template>
                </v-checkbox>

                <v-checkbox v-model="acceptNews"
                            :error="Boolean(errors.acceptNews)"
                            :error-messages="errors.acceptNews"
                            hide-details
                            class="mt-1"
                >
                    <template v-slot:label>
                        <translated-text code="USER.LABEL.ACCEPT_NEWS"/>
                    </template>
                </v-checkbox>

                <v-checkbox v-model="acceptProcessPersonalData"
                            :error="Boolean(errors.acceptProcessPersonalData)"
                            :error-messages="errors.acceptProcessPersonalData"
                            hide-details
                            class="mt-1"
                >
                    <template v-slot:label>
                        <translated-text code="USER.LABEL.ACCEPT_PROCESS_PERSONAL_DATA"/>
                    </template>
                </v-checkbox>

                <div class="user-register__required mt-5">
                    <translated-text code="GENERAL.REQUIRED_FIELDS"/>
                </div>

                <v-btn type="submit" :disabled="busy"
                       block large depressed color="orange" class="mt-5">
                    <translated-text code="USER.REGISTER.BUTTON.SUBMIT"/>
                </v-btn>

                <div class="user-register__help">
                    <translated-link prefix="USER.REGISTER.MESSAGE.DO_YOU_HAVE_ACCOUNT"
                                     label="USER.REGISTER.MESSAGE.LOGIN"
                                     @click="clickLogin"
                    />
                </div>

            </v-form>

        </template>

    </div>
</template>

<script>
    import {mapActions, mapGetters, mapState} from "vuex";
    import {mapComputed} from "../../modules/VuexMappers";
    import UserRegisterStore from "./store";
    import TranslatedText from "../translated-text/TranslatedText";
    import TranslatedLink from "../translated-text/TranslatedLink";

    export default {
        name: "UserRegister",

        props: {
            saveWishList: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        components: {TranslatedLink, TranslatedText},

        data() {
            return {
                passwordMarker: false,
                confirmPasswordMarker: false,
            };
        },

        computed: {
            ...mapState("global", ["me", 'isDealerMode',]),
            ...mapState("userRegister", {
                done: state => state.done,
                busy: state => state.busy,
                invalid: state => state.invalid,
                errors: state => state.errors,
                globalError: state => state.globalError,
                wishListSaved: state => state.wishListSaved,
            }),
            ...mapGetters("userRegister", [
                "privacyPolicyUrl"
            ]),
            ...mapComputed('userRegister',
                'email',
                'acceptNews',
                'acceptProcessPersonalData',
                'acceptPrivacyPolicy',
            ),

            translationTitlePrefix() {
                return 'USER.REGISTER.' + (this.saveWishList ? 'SAVE_CONF.' : '');
            },

            titleMessage() {
                return this.$tc(this.translationTitlePrefix + 'TITLE');
            },

            subTitleMessage() {
                return this.$tc(this.translationTitlePrefix + 'SUB_TITLE');
            },

            doneMessage() {
                return this.$tc(this.translationTitlePrefix + 'DONE');
            },
        },

        watch: {
            wishListSaved(val){
                if (val) {
                    this.$store.dispatch('global/loadMe');
                }
            },
        },

        methods: {
            ...mapActions("userRegister", [
                "submitUserRegister",
            ]),

            submitUserRegisterForm() {
                if (this.saveWishList) {
                    this.submitUserRegister({uid: this.me.uid, isDealerMode: this.isDealerMode});
                } else {
                    this.submitUserRegister();
                }
                this.$emit('click:register');
            },

            clickLogin() {
                this.$emit('click:login');
            },

            clickAcceptPrivacyPolicy() {
                window.open(this.privacyPolicyUrl, '_blank').focus();
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule('userRegister')) {
                this.$store.registerModule('userRegister', UserRegisterStore);
            }
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .user-register {
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
        }

        &__required {
            @include normal-font(12px, 14px);
            font-family: $fontSomfySansLight;
            color: #485C74;
            margin-bottom: 15px;
        }


        &__help {
            margin-top: 20px;
            @include normal-font(14px, 19px);
            color: #485c74;
            font-family: $fontSomfySansLight;
            text-align: center;

            a {
                text-decoration: underline;

                &:hover {
                    text-decoration: none;
                }
            }
        }

    }

</style>
