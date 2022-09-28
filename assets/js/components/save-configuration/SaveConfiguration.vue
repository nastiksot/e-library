<template>
    <div class="save">
        <v-dialog
            v-model="dialog"
            width="550"
            overlay-color="#000"
            overlay-opacity="0.65"
            content-class="save-dialog"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    outlined
                    color="orange"
                    v-bind="attrs"
                    v-on="on"
                    :disabled="disabled"
                    class="save__btn"
                >
                    <v-icon>save-icon</v-icon>
                    <span class="v-btn__text"><translated-text code="NAVIGATION.SAVE_CONFIGURATION"/></span>
                </v-btn>
            </template>

            <v-card>
                <div v-if="step === 'login'">
                    <v-card-title>
                        <v-btn icon small class="v-dialog__close" @click="dialog = false">
                            <v-icon>close-icon</v-icon>
                        </v-btn>
                    </v-card-title>

                    <v-card-text>
                        <user-login @click:forgotPassword="clickForgotPassword"
                                    no-redirect-after-login
                                    @logged-in="userLoggedIn"
                        />
                    </v-card-text>
                </div>

                <div v-else-if="step === 'register'">
                    <v-card-title>
                        <v-btn icon small class="v-dialog__close" @click="dialog = false">
                            <v-icon>close-icon</v-icon>
                        </v-btn>
                    </v-card-title>

                    <v-card-text>
                        <user-register @click:login="clickLogin"
                                       save-wish-list
                        />
                    </v-card-text>
                </div>

                <div v-else-if="step === 'forgot-password'">
                    <v-card-title>
                        <v-btn icon small class="v-dialog__close" @click="dialog = false">
                            <v-icon>close-icon</v-icon>
                        </v-btn>
                    </v-card-title>

                    <v-card-text>
                        <user-forgot-password/>
                    </v-card-text>
                </div>

                <div v-else-if="step === 'save'">
                    <v-card-title>
                        <v-btn icon small class="v-dialog__close" @click="dialog = false">
                            <v-icon>close-icon</v-icon>
                        </v-btn>
                    </v-card-title>

                    <v-card-text>
                        <save-configuration-wish-list @close="clickClose"/>
                    </v-card-text>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import TranslatedText from "../translated-text/TranslatedText";
    import UserRegister from "../user-register/UserRegister";
    import UserForgotPassword from "../user-forgot-password/UserForgotPassword";
    import UserLogin from "../user-login/UserLogin";
    import WishListStore from "../wish-list/store";
    import UserRegisterStore from "../user-register/store";
    import {EventBus, events as EVENTS} from "../../modules/EventBus";
    import SaveConfigurationStore from "./store";
    import SaveConfigurationWishList from "./SaveConfigurationWishList";

    export default {
        name: "SaveConfiguration",

        props: {},

        components: {
            SaveConfigurationWishList,
            UserLogin, UserForgotPassword, UserRegister,
            TranslatedText
        },

        data() {
            return {
                step: null,
                dialog: false,
            };
        },

        computed: {
            ...mapState("global", ["me"]),
            ...mapState("wishList", ["wishList"]),
            ...mapState("userRegister", ["wishListSaved"]),
            ...mapState("saveConfigurationStore", {
                done: state => state.done,
                wishListSaved: state => state.wishListSaved,
            }),

            disabled() {
                return Object.keys(this.wishList).length === 0 || this.wishList.isEmptyProductSets;
            },

            wishListSavedAndDialogAndMeUid() {
                return `${this.wishListSaved}|${this.dialog}|${this.me.uid}`;
            },

        },

        watch: {

            me(newValue, oldValue) {
                // me was changed at first time
                if (Object.keys(newValue).length > 0 &&
                    Object.keys(oldValue).length === 0
                ){
                    this.step = this.resolveStartStep();
                }
            },

            dialog(newValue, oldValue) {
                // dialog close
                if (false === newValue) {
                    // reset step to default
                    this.step = this.resolveStartStep();

                    // reset wishListSaved
                    this.setWishListSaved(false);
                }
            },

            wishListSavedAndDialogAndMeUid(newValue, oldValue) {

                // split-up watched values
                let [newWishListSaved, newDialog, newMeUid] = newValue.split('|');
                let [oldWishListSaved, oldDialog, oldMeUid] = oldValue.split('|');

                // convert string to boolean values
                newWishListSaved = (newWishListSaved === 'true');
                newDialog = (newDialog === 'true');
                oldWishListSaved = (oldWishListSaved === 'true');
                oldDialog = (oldDialog === 'true');

                // emit event: wishList was saved
                if (true === newWishListSaved && // the wishList was saved
                    false === newDialog && // the dialog was closed
                    newMeUid && oldMeUid // the global me.uid was changed
                ) {
                    EventBus.emit(EVENTS.WISHLIST_SAVED, newMeUid);
                }
            },
        },

        methods: {

            ...mapActions("saveConfigurationStore", [
                "setWishListSaved",
            ]),

            resolveStartStep() {
                return Object.keys(this.me).length !== 0 && this.me.isLoggedIn ? 'save' : 'register';
            },

            clickLogin() {
                this.step = 'login';
            },

            clickForgotPassword() {
                this.step = 'forgot-password';
            },

            userLoggedIn() {
                this.step = this.resolveStartStep();
            },

            clickClose() {
                this.dialog = false;
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
            if (!this.$store.hasModule('userRegister')) {
                this.$store.registerModule('userRegister', UserRegisterStore);
            }
            if (!this.$store.hasModule("saveConfigurationStore")) {
                this.$store.registerModule("saveConfigurationStore", SaveConfigurationStore);
            }
        },

        created() {
            this.step = this.resolveStartStep();
        }
    }
</script>

<style scoped lang="scss">
    @import "../../../scss/base";
    @import "../../../scss/components/dialog";

    .save {

        &__btn[disabled] {
            border-color: #CED4DA;

            .v-btn__text {
                color: #CED4DA;
            }
        }

        @media (max-width: 1502px) {
            .v-btn {
                min-width: 44px;
                padding: 0;

                .v-icon {
                    margin-right: 0;
                }

                &__text {
                    display: none;
                }
            }
        }
    }

    .v-dialog {
        .v-card {
            padding: 22px 48px;

            &::v-deep {
                .v-icon {
                    font-family: 'somfy' !important;
                }
            }
        }
    }

</style>
