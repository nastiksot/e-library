<template>
    <div class="app">
        <app-header active="account"/>

        <alert v-if="alertData"
               :type="alertData.type"
               :color="alertData.color"
               :message="alertData.message"
               class="request-alert"
        />

        <div class="app__account">
            <div class="app__account--aside">

                <user-account-navigation active="user-account-requests"/>

            </div>
            <div class="app__account--content">

                <user-dealer-request :is-archived-requests-page="isArchivedRequestsPage"/>

            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import AppHeader from "../../components/app-header/AppHeader";
    import UserAccountNavigation from "../../components/user-account/UserAccountNavigation";
    import UserDealerRequest from '../../components/user-dealer-request/UserDealerRequest';
    import Alert from "../../components/alert/Alert";
    import AlertStore from "../../components/alert/store";

    export default {
        name: "UserDealerRequestPage",

        props: {
            strIsArchivedRequestsPage: {
                type: String,
                require: true,
            }
        },

        components: {
            AppHeader,
            UserAccountNavigation,
            UserDealerRequest,
            Alert,
        },

        data() {
            return {};
        },

        computed: {
            ...mapState("alert", ["notification"]),

            alertData() {
                return  this.notification;
            },

            isArchivedRequestsPage() {
                return !!parseInt(this.strIsArchivedRequestsPage);
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("alert")) {
                this.$store.registerModule("alert", AlertStore);
            }
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .app {
        &__account {
            @include flexbox();

            @media all and (max-width: 1200px) {
                flex-wrap: wrap;
            }

            &--aside {
                background: #F8F7F5;
                width: 425px;
                padding: 25px 30px;

                @media all and (max-width: 1200px) {
                    width: 100%;
                }

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
                padding: 20px 55px 55px 32px;
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

        .request-alert {
            font-family: $fontSomfySansMedium;
            padding: 3px 0;

            &::v-deep {
                .v-alert__wrapper {
                    justify-content: center;

                    .v-btn {
                        position: absolute;
                        right: 5px;
                    }
                }
                .v-alert__content {
                    flex: 0 0 auto;
                    display: inline-flex;
                    align-items: center;

                    &:before {
                        font-family: 'somfy';
                        content: "\e92b";
                        color: #009845;
                        font-size: 20px;
                        margin-right: 7px;
                    }

                    v-icon {
                        display: none;
                    }
                }
            }
        }
    }

</style>
