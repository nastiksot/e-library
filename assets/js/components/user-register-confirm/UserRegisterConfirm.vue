<template>
    <div class="user-register-confirm">

        <template v-if="!ready">
            <v-form @submit.prevent="" class="progress mt-10 mb-10">
                <v-progress-circular indeterminate color="orange" class="spinner" size="70"/>
                <v-row></v-row>
            </v-form>
        </template>

        <template v-else-if="done">
            <h2 class="user-register-confirm__title">
                <translated-text code="USER.REGISTER_CONFIRM.DONE"/>
            </h2>

            <v-alert type="success" dense text>
                <translated-text code="USER.REGISTER_CONFIRM.MESSAGE.SUCCESS"/>
            </v-alert>

            <v-btn type="button" @click="clickLogin"
                   block large depressed color="orange" class="mt-5">
                <translated-text code="USER.REGISTER_CONFIRM.BUTTON.LOGIN"/>
            </v-btn>

        </template>

        <template v-else>

            <h2 class="user-register-confirm__title">
                <translated-text code="USER.REGISTER_CONFIRM.TITLE"/>
            </h2>
            <h3 class="user-register-confirm__subtitle">
                <translated-text code="USER.REGISTER_CONFIRM.SUB_TITLE"/>
            </h3>

            <v-alert v-if="invalid && globalError" type="error" dense text>
                <translated-text :code="globalError"/>
            </v-alert>

            <v-form @submit.prevent="submitUserRegisterConfirm" :class="{'progress': busy}">

                <v-progress-circular v-if="busy" indeterminate color="orange" class="spinner" size="70"/>

                <v-text-field v-model="password" :type="passwordMarker ? 'text' : 'password'"
                              :error="Boolean(errors.password)"
                              :error-messages="errors.password"
                              hide-details="auto"
                              outlined
                              :append-icon="passwordMarker ? 'visibility-on-icon' : 'visibility-off-icon'"
                              @click:append="togglePasswordMarker"
                              autocomplete="new-password"
                >
                    <template v-slot:label>
                        <translated-link prefix="USER.LABEL.PASSWORD" suffix="*"/>
                    </template>
                </v-text-field>

                <v-text-field v-model="confirmPassword" :type="confirmPasswordMarker ? 'text' : 'password'"
                              :error="Boolean(errors.confirmPassword)"
                              :error-messages="errors.confirmPassword"
                              hide-details="auto"
                              outlined
                              :append-icon="confirmPasswordMarker ? 'visibility-on-icon' : 'visibility-off-icon'"
                              @click:append="toggleConfirmPasswordMarker"
                              autocomplete="new-password"
                >
                    <template v-slot:label>
                        <translated-link prefix="USER.LABEL.CONFIRM_PASSWORD" suffix="*"/>
                    </template>
                </v-text-field>

                <div class="user-register-confirm__required mt-5">
                    <translated-text code="GENERAL.REQUIRED_FIELDS"/>
                </div>

                <v-btn type="submit" :disabled="busy"
                       block large depressed color="orange" class="mt-5">
                    <translated-text code="USER.REGISTER_CONFIRM.BUTTON.SUBMIT"/>
                </v-btn>

            </v-form>

        </template>

    </div>
</template>

<script>
    import userRegisterConfirmStore from "./store";
    import {mapActions, mapState} from "vuex";
    import {mapComputed} from "../../modules/VuexMappers";
    import TranslatedText from "../translated-text/TranslatedText";
    import TranslatedLink from "../translated-text/TranslatedLink";
    import RouteGenerator from "../../modules/RouteGenerator";

    export default {
        name: "UserRegisterConfirm",

        props: {
            token: {
                type: String,
                required: true
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
            ...mapState('userRegisterConfirm', {
                done: state => state.done,
                busy: state => state.busy,
                invalid: state => state.invalid,
                errors: state => state.errors,
                globalError: state => state.globalError,
                ready: state => state.ready,
            }),
            ...mapComputed('userRegisterConfirm',
                'password',
                'confirmPassword',
            ),
        },

        watch: {
            async done(nevVal, oldVal) {
                if (nevVal && nevVal !== oldVal) {
                    this.tcEventClickOnUserRegistered();
                }
            },
        },

        methods: {
            ...mapActions('userRegisterConfirm', [
                'setToken',
                'setReady',
                'submitUserRegisterConfirm',
            ]),

            togglePasswordMarker() {
                this.passwordMarker = !this.passwordMarker
            },

            toggleConfirmPasswordMarker() {
                this.confirmPasswordMarker = !this.confirmPasswordMarker
            },

            clickLogin() {
                window.location.href = RouteGenerator.generate('web.login');
            },

            tcEventClickOnUserRegistered() {
                if (typeof (tc_events_global) === 'undefined') {
                    return;
                }

                try {
                    tc_events_global(this, 'account_creation', {
                        'evt_category': 'account',
                        'evt_button_action': 'account_creation',
                    });
                } catch (error) {
                    console.error(error);
                }
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule('userRegisterConfirm')) {
                this.$store.registerModule('userRegisterConfirm', userRegisterConfirmStore);
            }
        },

        created() {
            this.setToken(this.token);
            setTimeout(() => {
                this.setReady(true);
            }, 500);
        }
    };
</script>

<style scoped lang="scss">

    @import "../../../scss/base";

    .user-register-confirm {
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

        &::v-deep {
            .v-icon {
                font-family: 'somfy' !important;
            }
        }

    }

</style>
