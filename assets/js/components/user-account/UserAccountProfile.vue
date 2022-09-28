<template>
    <div class="user-account-profile">

        <h2 class="user-account-profile__title">
            <translated-text code="USER.ACCOUNT.TITLE"/>
        </h2>
        <h3 class="user-account-profile__subtitle">
            <translated-text code="USER.ACCOUNT.SUB_TITLE"/>
        </h3>

        <v-alert v-if="done" type="success" dense text>
            <translated-text code="USER.ACCOUNT.MESSAGE.SUCCESS"/>
        </v-alert>

        <v-alert v-if="invalid && globalError" type="error" dense text>
            <translated-text :code="globalError"/>
        </v-alert>

        <v-form @submit.prevent="submitUserAccountForm" :class="{'progress': busy}">

            <v-progress-circular v-if="busy" indeterminate color="orange" class="spinner" size="70"/>

            <v-text-field v-model="firstName"
                          :error="Boolean(errors.firstName)"
                          :error-messages="errors.firstName"
                          hide-details="auto"
                          outlined
                          class="mb-4"
            >
                <template v-slot:label>
                    <translated-link prefix="USER.LABEL.FIRST_NAME" suffix="*"/>
                </template>
            </v-text-field>

            <v-text-field v-model="lastName"
                          :error="Boolean(errors.lastName)"
                          :error-messages="errors.lastName"
                          hide-details="auto"
                          outlined
                          class="mb-4"
            >
                <template v-slot:label>
                    <translated-link prefix="USER.LABEL.LAST_NAME" suffix="*"/>
                </template>
            </v-text-field>

            <v-text-field v-model="email"
                          :error="Boolean(errors.email)"
                          :error-messages="errors.email"
                          hide-details="auto"
                          outlined
                          class="mb-4"
            >
                <template v-slot:label>
                    <translated-link prefix="USER.LABEL.EMAIL" suffix="*"/>
                </template>
            </v-text-field>

            <v-text-field v-model="password" :type="marker ? 'text' : 'password'"
                          :error="Boolean(errors.password)"
                          :error-messages="errors.password"
                          hide-details="auto"
                          outlined
                          :append-icon="marker ? 'visibility-on-icon' : 'visibility-off-icon'"
                          @click:append="toggleMarker"
                          autocomplete="new-password"
                          class="mb-4"
            >
                <template v-slot:label>
                    <translated-link prefix="USER.LABEL.PASSWORD" suffix="*"/>
                </template>
            </v-text-field>

            <div class="user-account-profile__accept mt-5">
                <h3 class="user-account-profile__accept__title">
                    <translated-text code="USER.ACCOUNT.ACCEPT_TITLE"/>
                </h3>

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

            </div>

            <div class="user-account-profile__required mt-5">
                <translated-text code="GENERAL.REQUIRED_FIELDS"/>
            </div>

            <v-btn type="submit" :disabled="busy"
                   outlined
                   block large depressed color="orange" class="mt-5">
                <translated-text code="USER.ACCOUNT.BUTTON.SUBMIT"/>
            </v-btn>

            <div class="other-actions">
                <span class="delete" @click="openDeleteDialog()">
                    <translated-text code="USER.ACCOUNT.BUTTON.DELETE"/>
                </span>
            </div>

            <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card class="modal-delete">
                    <v-card-title class="modal-title">
                        <translated-text code="USER.ACCOUNT.DELETE.TITLE"/>
                    </v-card-title>

                    <v-card-actions>
                        <v-spacer></v-spacer>

                        <v-btn class="modal-btn" text @click="deleteConfirmed">
                            <translated-text code="USER.ACCOUNT.BUTTON.DELETE"/>
                        </v-btn>

                        <v-btn class="modal-btn modal-btn_close" text @click="closeDeleteDialog">
                            <translated-text code="USER.ACCOUNT.BUTTON.CANCEL"/>
                        </v-btn>

                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>

        </v-form>

    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import UserAccountStore from "./store";
    import {mapComputed} from "../../modules/VuexMappers";
    import TranslatedText from "../translated-text/TranslatedText";
    import TranslatedLink from "../translated-text/TranslatedLink";

    export default {
        name: "UserAccountProfile",

        props: {},

        components: {TranslatedLink, TranslatedText},

        data() {
            return {
                marker: true,
                dialogDelete: false,
            };
        },

        computed: {
            ...mapState("global", ["me"]),
            ...mapState("userAccount", {
                done: state => state.done,
                busy: state => state.busy,
                invalid: state => state.invalid,
                errors: state => state.errors,
                globalError: state => state.globalError,
            }),
            ...mapComputed('userAccount',
                'firstName',
                'lastName',
                'email',
                'password',
                'acceptNews',
                'acceptProcessPersonalData',
                'acceptPrivacyPolicy',
            ),
        },

        watch: {
            me() {
                if (Object.keys(this.me).length !== 0) {
                    this.setUserAccount(this.me);
                }
            },

            done(newValue) {
                if (newValue) {
                    this.$store.dispatch('global/loadMe');
                }
            },

            dialogDelete(val) {
                val || this.closeDeleteDialog()
            },
        },

        methods: {
            ...mapActions("userAccount", [
                "setUserAccount",
                "submitUserAccount",
                'deleteCurrentUser',
            ]),

            submitUserAccountForm() {
                this.submitUserAccount();
            },

            toggleMarker() {
                this.marker = !this.marker
            },

            clickAcceptPrivacyPolicy() {
                window.location.href = '/privacy-policy';
            },

            openDeleteDialog() {
                this.dialogDelete = true;
            },

            closeDeleteDialog() {
                this.dialogDelete = false
            },

            deleteConfirmed() {
                this.deleteCurrentUser();

                this.closeDeleteDialog();
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule('userAccount')) {
                this.$store.registerModule('userAccount', UserAccountStore);
            }
        },
    };
</script>

<style scoped lang="scss">

    @import "../../../scss/base";

    .user-account-profile {
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

        &__accept {
            &__title {

            }
        }

        .other-actions {
            margin: 20px 0 0 0;
            color: #8996a4;
            font-family: $fontSomfySansLight;
            @include normal-font(14px, 20px);

            .delete {
                text-decoration: underline;
                cursor: pointer;

                &:hover {
                    text-decoration: none;
                }
            }
        }
    }

    .modal-delete {
        padding: 20px;

        .modal-title {
            font-size: 21px;
            justify-content: center;
            padding: 0;
            margin-bottom: 15px;
            color: #3c4f64;
        }

        .modal-btn {
            transition: color 0.2s ease-in-out, border 0.2s ease-in-out, background 0.2s ease-in-out;
            padding: 10px 30px;
            font-size: 14px;
            line-height: 22px;
            border: 1px solid #fcac22;
            border-radius: 4px;
            color: #fcac22 !important;
            background: none;
            display: flex;
            justify-content: center;
            font-family: $fontSomfySansRegular;

            &:hover {
                background: #fcac22;
                color: #fff !important;
            }

            &_close {
                background: #fcac22;
                color: #fff !important;

                &:hover {
                    background: transparent;
                    color: #fcac22 !important;
                }
            }
        }
    }
</style>
