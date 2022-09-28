<template>
    <div class="user-forgot-password">

        <template v-if="!ready">
            <v-form @submit.prevent="" class="progress mt-10 mb-10">
                <v-progress-circular indeterminate color="orange" class="spinner" size="70"/>
                <v-row></v-row>
            </v-form>
        </template>

        <template v-else-if="done">
            <h2 class="user-forgot-password__title">
                <translated-text code="USER.FORGOT_PASSWORD.DONE"/>
            </h2>

            <v-alert type="success" dense text>
                <translated-text code="USER.FORGOT_PASSWORD.MESSAGE.SUCCESS"/>
            </v-alert>
        </template>

        <template v-else>

            <h2 class="user-forgot-password__title">
                <translated-text code="USER.FORGOT_PASSWORD.TITLE"/>
            </h2>
            <h3 class="user-forgot-password__subtitle">
                <translated-text code="USER.FORGOT_PASSWORD.SUB_TITLE"/>
            </h3>

            <v-alert v-if="invalid && globalError" type="error" dense text>
                <translated-text :code="globalError"/>
            </v-alert>

            <v-form @submit.prevent="submitUserForgotPassword" :class="{'progress': busy}">

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

                <div class="user-forgot-password__required mt-5">
                    <translated-text code="GENERAL.REQUIRED_FIELDS"/>
                </div>

                <v-btn type="submit" :disabled="busy"
                       block large depressed color="orange" class="mt-5">
                    <translated-text code="USER.FORGOT_PASSWORD.BUTTON.SUBMIT"/>
                </v-btn>

            </v-form>

        </template>

    </div>
</template>

<script>
    import UserForgotPasswordStore from "./store";
    import {mapActions, mapState} from "vuex";
    import {mapComputed} from "../../modules/VuexMappers";
    import TranslatedText from "../translated-text/TranslatedText";
    import TranslatedLink from "../translated-text/TranslatedLink";

    export default {
        name: 'UserForgotPassword',

        props: {},

        components: {TranslatedLink, TranslatedText},

        data() {
            return {};
        },

        computed: {
            ...mapState('userForgotPassword', {
                done: state => state.done,
                busy: state => state.busy,
                invalid: state => state.invalid,
                errors: state => state.errors,
                globalError: state => state.globalError,
                ready: state => state.ready,
            }),
            ...mapComputed('userForgotPassword',
                'email',
            ),
        },

        watch: {},

        methods: {
            ...mapActions('userForgotPassword', [
                'setReady',
                'submitUserForgotPassword',
            ]),
        },

        beforeCreate() {
            if (!this.$store.hasModule('userForgotPassword')) {
                this.$store.registerModule('userForgotPassword', UserForgotPasswordStore);
            }
        },
        created() {
            setTimeout(() => {
                this.setReady(true);
            }, 500);
        }
    };
</script>

<style scoped lang="scss">

    @import "../../../scss/base";

    .user-forgot-password {
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

    }

</style>
