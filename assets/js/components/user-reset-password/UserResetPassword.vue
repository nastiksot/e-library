<template>
    <div class="user-reset-password">

        <template v-if="!ready">
            <v-form @submit.prevent="" class="progress mt-10 mb-10">
                <v-progress-circular indeterminate color="orange" class="spinner" size="70"/>
                <v-row></v-row>
            </v-form>
        </template>

        <template v-else-if="done">
            <h2 class="user-reset-password__title">
                <translated-text code="USER.RESET_PASSWORD.DONE"/>
            </h2>

            <v-alert type="success" dense text>
                <translated-text code="USER.RESET_PASSWORD.MESSAGE.SUCCESS"/>
            </v-alert>
        </template>

        <template v-else>

            <h2 class="user-reset-password__title">
                <translated-text code="USER.RESET_PASSWORD.TITLE"/>
            </h2>
            <h3 class="user-reset-password__subtitle">
                <translated-text code="USER.RESET_PASSWORD.SUB_TITLE"/>
            </h3>

            <v-alert v-if="invalid && globalError" type="error" dense text>
                <translated-text :code="globalError"/>
            </v-alert>

            <v-form @submit.prevent="submitUserResetPassword" :class="{'progress': busy}">

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
                              :label="$tc('USER.LABEL.CONFIRM_PASSWORD')+'*'"
                              :placeholder="$tc('USER.LABEL.CONFIRM_PASSWORD')+'*'"
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

                <div class="user-reset-password__required mt-5">
                    <translated-text code="GENERAL.REQUIRED_FIELDS"/>
                </div>

                <v-btn type="submit" :disabled="busy"
                       block large depressed color="orange" class="mt-5">
                    <translated-text code="USER.RESET_PASSWORD.BUTTON.SUBMIT"/>
                </v-btn>

            </v-form>

        </template>

    </div>
</template>

<script>
    import UserResetPasswordStore from "./store";
    import {mapActions, mapState} from "vuex";
    import {mapComputed} from "../../modules/VuexMappers";
    import TranslatedText from "../translated-text/TranslatedText";
    import {EventBus, events as EVENTS} from "../../modules/EventBus";
    import TranslatedLink from "../translated-text/TranslatedLink";

    export default {
        name: 'UserResetPassword',

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
            ...mapState('userResetPassword', {
                done: state => state.done,
                busy: state => state.busy,
                invalid: state => state.invalid,
                errors: state => state.errors,
                globalError: state => state.globalError,
                ready: state => state.ready,
            }),
            ...mapComputed('userResetPassword',
                'password',
                'confirmPassword',
            ),
        },

        watch: {},

        methods: {
            ...mapActions('userResetPassword', [
                'setToken',
                'setReady',
                'submitUserResetPassword',
            ]),

            togglePasswordMarker() {
                this.passwordMarker = !this.passwordMarker
            },

            toggleConfirmPasswordMarker() {
                this.confirmPasswordMarker = !this.confirmPasswordMarker
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule('userResetPassword')) {
                this.$store.registerModule('userResetPassword', UserResetPasswordStore);
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

    .user-reset-password {
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
