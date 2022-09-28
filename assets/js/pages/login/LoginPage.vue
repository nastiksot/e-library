<template>
    <div class="app">
        <app-header active="account"/>
        <div class="app__login">
            <div class="app__login--content">
                <user-login @click:forgotPassword="forgotPasswordDialog=true"
                            :last-username="lastUsername"
                            :error="error"/>
            </div>
        </div>

        <v-dialog
            v-model="forgotPasswordDialog"
            width="550"
            overlay-color="#000"
            overlay-opacity="0.65"
            content-class="forgot-password-dialog"
        >
            <v-card>
                <div>
                    <v-card-title>
                        <v-btn icon small class="v-dialog__close" @click="forgotPasswordDialog = false">
                            <v-icon>close-icon</v-icon>
                        </v-btn>
                    </v-card-title>

                    <v-card-text>
                        <user-forgot-password/>
                    </v-card-text>

                </div>

            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import {mapState, mapActions} from "vuex";
    import AppHeader from "../../components/app-header/AppHeader";
    import UserLogin from "../../components/user-login/UserLogin";
    import TranslatedText from "../../components/translated-text/TranslatedText";
    import UserForgotPassword from "../../components/user-forgot-password/UserForgotPassword";
    import WishListStore from "../../components/wish-list/store";

    export default {
        name: "LoginPage",

        props: {
            lastUsername: {
                type: String,
                required: false
            },
            error: {
                type: String,
                required: false,
            },
        },

        components: {UserForgotPassword, TranslatedText, UserLogin, AppHeader},

        data() {
            return {
                tabs: null,
                forgotPasswordDialog: false,
            };
        },

        computed: {
            ...mapState("global", ["me",]),
        },

        watch: {
            me() {
                if (Object.keys(this.me).length !== 0) {
                    this.loadWishList({uid: this.me.uid})
                }
            }
        },

        methods: {
            ...mapActions("wishList", ["loadWishList"]),
        },

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";
    @import "../../../scss/components/dialog";

    .app {
        &__login {
            @include flexbox();
            background: #F8F7F5;
            padding-top: 20px;

            &--content {
                padding: 20px;
                max-width: 500px;
                width: 100%;
                margin: auto;
                background: #fff;

                .v-window {
                    overflow: visible;
                }

                &-title {
                    color: #485c74;
                    @include normal-font(28px, 37px);
                    font-family: $fontSomfySansLight;
                    margin-bottom: 7px;
                }

                &-subtitle {
                    color: #485c74;
                    @include normal-font(22px, 30px);
                    font-family: $fontSomfySansLight;
                    padding-bottom: 8px;
                    border-bottom: 1px solid #cacfd5;
                    margin-bottom: 23px;
                }

                &-link {
                    color: #fcac22;
                    @include normal-font(14px, 17px);
                    margin-top: 3px;
                    font-family: $fontSomfySansLight;
                    text-decoration: underline;

                    &:hover {
                        text-decoration: none;
                    }
                }

                &-sort {
                    margin-bottom: 20px;
                }

                &::v-deep {
                    .v-input {
                        &__icon--append {
                            .v-icon {
                                &:before {
                                    font-family: 'somfy' !important;
                                }
                            }
                        }
                    }
                }

                .v-form {
                    &__actions {
                        margin-top: 40px;
                        text-align: right;

                        .v-btn {
                            min-width: 215px;
                        }
                    }
                }
            }

            @media (max-width: 991px) {
                @include flex-direction(column);

                &--content {
                    padding: 20px;
                }
            }
        }
    }
</style>
