<template>
    <div class="app">
        <app-header active="account"/>
        <div class="app__account">
            <div class="app__account--aside">

                <user-account-navigation active="dealer-list"/>

            </div>
            <div class="app__account--content">

                <dealer-user-account-profile :dealer-user-id="dealerUserId"/>

            </div>
        </div>
    </div>
</template>

<script>
import AppHeader from "../../components/app-header/AppHeader";
import UserAccountNavigation from "../../components/user-account/UserAccountNavigation";
import DealerUserAccountProfile from '../../components/dealer-user-account/DealerUserAccountProfile';

export default {
    name: "DealerUserAccountPage",

    props: {
        strDealerUserId: {
            type: String,
            required: true
        },
    },

    components: {
        AppHeader,
        UserAccountNavigation,
        DealerUserAccountProfile,
    },

    data() {
        return {};
    },

    computed: {
        dealerUserId() {
            return parseInt(this.strDealerUserId);
        }
    }
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
</style>
