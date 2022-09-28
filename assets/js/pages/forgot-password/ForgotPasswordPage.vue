<template>
    <div class="app">
        <app-header active="account"/>
        <div class="app__account">
            <div class="app__account--aside">
                <h2 class="app__account--aside-title">Anmeldung</h2>
                <div class="app__account--aside-text">Registrieren Sie Ihr Smart-Home-Planer-Konto</div>
                <v-tabs centered vertical hide-slider v-model="tabs" class="app__account--aside-menu">
                    <v-tab>
                        Kontoverwaltung
                        <v-icon>arrow-right-icon</v-icon>
                    </v-tab>
                </v-tabs>
            </div>
            <div class="app__account--content">
                <v-tabs-items v-model="tabs">
                    <v-tab-item>

                        <user-forgot-password/>

                    </v-tab-item>

                </v-tabs-items>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState, mapActions} from "vuex";
    import AppHeader from "../../components/app-header/AppHeader";
    import ProductSetItem from "../../components/product-set/ProductSetItem";
    import UserForgotPassword from "../../components/user-forgot-password/UserForgotPassword";
    import WishListStore from "../../components/wish-list/store";

    export default {
        name: "ForgotPasswordPage",

        props: {},

        components: {UserForgotPassword, ProductSetItem, AppHeader},

        data() {
            return {
                tabs: null,
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

    .app {
        &__account {
            @include flexbox();

            &--aside {
                background: #F8F7F5;
                width: 425px;
                padding: 25px 30px;

                &-title {
                    color: #485c74;
                    @include normal-font(28px, 32px);
                    font-family: $fontSomfySansLight;
                    margin-bottom: 8px;
                }

                &-text {
                    font-family: $fontSomfySansLight;
                    @include normal-font(16px, 21px);
                    color: #485c74;
                    margin-bottom: 45px;
                }

                &-country {
                    margin-bottom: 20px;
                }

                &-language {
                    margin-bottom: 25px;

                    &-label {
                        color: #485c74;
                        @include normal-font(14px, 19px);
                        font-family: $fontSomfySansLight;
                        margin-bottom: 10px;
                    }

                    .v-btn {
                        padding: 0;
                        height: 40px !important;
                        min-width: 40px !important;
                        background: #fff;
                        font-size: 14px !important;
                        color: #8996a4 !important;
                        margin-right: 12px;
                        border-radius: 4px;
                        border-width: 1px !important;

                        &-toggle {
                            background: none;
                        }

                        &--active {
                            background: #8996A4 !important;
                            color: #fff !important;
                            border: 1px solid #8996A4 !important;
                        }
                    }
                }

                &-logout {
                    color: #485c74;
                    padding: 0 8px !important;
                    margin-left: -8px;

                    .v-icon {
                        font-size: 16px;
                        margin-right: 8px;
                    }
                }

                &::v-deep {
                    .v-tabs {
                        margin-bottom: 60px;

                        .v-tabs-bar {
                            background: none !important;

                            &__content {
                                display: block;

                                .v-tab {
                                    margin: 0 !important;
                                    height: auto;
                                    @include justify-content(flex-start);
                                    text-align: left;
                                    padding: 16px 20px 16px 0;
                                    @include normal-font(16px, 21px);
                                    border-bottom: 1px solid #cacfd5;
                                    text-transform: none;
                                    letter-spacing: normal;
                                    font-family: $fontSomfySansRegular;

                                    .v-icon {
                                        font-size: 12px;
                                        color: #485c74;
                                        margin-left: auto;
                                    }

                                    &--active {
                                        color: #fcac22;

                                        .v-icon {
                                            color: #fcac22;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            &--content {
                padding: 20px 55px 55px;
                max-width: 750px;
                width: 100%;

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
        }
    }

    .subscriptions {
        margin-top: 40px;

        &::v-deep {
            .v-input--checkbox {
                max-width: 250px;

                .v-label {
                    color: #485c74 !important;
                }
            }
        }
    }

    .property {
        max-width: 375px;
    }

    .property {
        &__list {
            &--delete {
                color: #8996a4;

                .v-icon {
                    margin-top: 3px;
                    margin-right: 5px;
                }
            }
        }
    }
</style>
