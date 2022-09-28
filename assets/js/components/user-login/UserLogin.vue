<template>
    <div class="user-login">

        <template v-if="!ready">
            <v-form @submit.prevent="" class="progress mt-10 mb-10">
                <v-progress-circular indeterminate color="orange" class="spinner" size="70"/>
                <v-row></v-row>
            </v-form>
        </template>

        <template v-else-if="done">
            <h2 class="user-login__title">
                <translated-text code="USER.LOGIN.DONE"/>
            </h2>
            <v-alert type="success" dense text>
                <translated-text code="USER.LOGIN.MESSAGE.SUCCESS"/>
            </v-alert>
        </template>

        <template v-else>

            <h2 class="user-login__title">
                <translated-text code="USER.LOGIN.TITLE"/>
            </h2>
            <h3 class="user-login__subtitle">
                <translated-text code="USER.LOGIN.SUB_TITLE"/>
            </h3>

            <v-alert v-if="invalid && globalError" type="error" dense text>
                <span v-html="globalError"/>
            </v-alert>

            <v-form @submit.prevent="submitUserLoginForm" :action="formAction" method="post"
                    :class="{'progress': busy}">

                <v-progress-circular v-if="busy" indeterminate color="orange" class="spinner" size="70"/>

                <v-text-field v-model="username"
                              :error="Boolean(errors.username)"
                              :error-messages="errors.username"
                              hide-details="auto"
                              outlined
                              name="_username"
                              autofocus
                              autocomplete="new-password"
                              class="mb-4"
                >
                    <template v-slot:label>
                        <translated-link prefix="USER.LABEL.EMAIL" suffix="*"/>
                    </template>
                </v-text-field>

                <v-text-field v-model="password" :type="passwordMarker ? 'text' : 'password'"
                              :error="Boolean(errors.password)"
                              :error-messages="errors.password"
                              :label="$tc('USER.LABEL.PASSWORD')+'*'"
                              :placeholder="$tc('USER.LABEL.PASSWORD')+'*'"
                              hide-details="auto"
                              outlined
                              :append-icon="passwordMarker ? 'visibility-on-icon' : 'visibility-off-icon'"
                              @click:append="togglePasswordMarker"
                              name="_password"
                              autocomplete="new-password"
                >
                    <template v-slot:label>
                        <translated-link prefix="USER.LABEL.PASSWORD" suffix="*"/>
                    </template>
                </v-text-field>

                <div class="user-login__required mt-5">
                    <translated-text code="GENERAL.REQUIRED_FIELDS"/>
                </div>

                <v-btn type="submit" :disabled="busy"
                       block large depressed color="orange" class="mt-5">
                    <translated-text code="USER.LOGIN.BUTTON.SUBMIT"/>
                </v-btn>

                <div class="user-login__help">
                    <a @click.prevent="clickForgotPassword" href="#">
                        <translated-text code="USER.LOGIN.MESSAGE.FORGOT_PASSWORD"/>
                    </a>
                    <a :href="registerLink">
                        <translated-text code="USER.LOGIN.MESSAGE.REGISTER"/>
                    </a>
                </div>

            </v-form>

        </template>

    </div>
</template>

<script>
    import UserLoginStore from "./store";
    import {mapActions, mapGetters, mapState} from "vuex";
    import {mapComputed} from "../../modules/VuexMappers";
    import TranslatedText from "../translated-text/TranslatedText";
    import TranslatedLink from "../translated-text/TranslatedLink";
    import RouterGenerator from "../../modules/RouteGenerator";

    export default {
        name: 'UserLogin',

        props: {
            noRedirectAfterLogin: {
                type: Boolean,
                required: false,
                default: false,
            },
            lastUsername: {
                type: String,
                required: false
            },
            error: {
                type: String,
                required: false,
            },
        },

        components: {TranslatedLink, TranslatedText},

        data() {
            return {
                passwordMarker: false,
            };
        },

        computed: {
            ...mapState('userLogin', {
                done: state => state.done,
                busy: state => state.busy,
                invalid: state => state.invalid,
                errors: state => state.errors,
                globalError: state => state.globalError,
                ready: state => state.ready,
            }),
            ...mapComputed('userLogin',
                'username',
                'password',
            ),
            formAction() {
                return this.loginCheckUrl();
            },

            registerLink() {
                return RouterGenerator.generate('web.auth.register');
            },
        },

        watch: {
            async done() {
                this.tcEventClickOnLogin();

                await this.$store.dispatch('global/loadMe');
                setTimeout(() => {
                    this.$emit('logged-in');
                }, 1000);
            }
        },

        methods: {
            ...mapActions('userLogin', [
                'setReady',
                'setErrorMessage',
                'setLastUsername',
                'submitUserLogin',
            ]),
            ...mapGetters('userLogin', [
                'loginCheckUrl',
            ]),
            togglePasswordMarker() {
                this.passwordMarker = !this.passwordMarker
            },

            clickForgotPassword() {
                this.$emit('click:forgotPassword');
            },

            submitUserLoginForm() {
                if (this.noRedirectAfterLogin) {
                    this.submitUserLogin(false);
                } else {
                    this.submitUserLogin(true);
                }
            },

            tcEventClickOnLogin() {
                if (typeof (tc_events_global) === 'undefined') {
                    return;
                }

                try {
                    tc_events_global(this, 'account_login', {
                        'evt_category': 'account',
                        'evt_button_action': 'account_login',
                    });
                } catch (error) {
                    console.error(error);
                }
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule('userLogin')) {
                this.$store.registerModule('userLogin', UserLoginStore);
            }
        },

        created() {
            this.setErrorMessage(this.error);
            this.setLastUsername(this.lastUsername);
            setTimeout(() => {
                this.setReady(true);
            }, 500);
        }

    };
</script>

<style scoped lang="scss">

    @import "../../../scss/base";

    .user-login {
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
            display: flex;
            justify-content: space-evenly;

            a {
                text-decoration: underline;

                &:hover {
                    text-decoration: none;
                }
            }
        }

    }

</style>
