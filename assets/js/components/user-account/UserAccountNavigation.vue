<template>
    <div class="user-account-navigation">
        <h2 class="user-account-navigation__hello">
            <translated-text code="USER.ACCOUNT.HELLO" :params="helloParams" />
        </h2>
        <div class="user-account-navigation__welcome">
            <translated-text code="USER.ACCOUNT.WELCOME" />
        </div>

        <div class="user-account-navigation__menu">
            <v-btn
                v-if="isDealerUserRole"
                text
                tile
                :class="{'active': active === 'user-account-requests'}"
                :href="RouteGenerator.generate('web.account.dealer.requests')"
            >
                <translated-text code="USER.ACCOUNT.MENU.REQUESTS" />
                <v-icon>external-link-icon</v-icon>
            </v-btn>

            <v-spacer />

            <v-btn
                text
                tile
                :class="{'active': active === 'user-account-wish-list'}"
                :href="RouteGenerator.generate('web.account.wishlist')"
            >
                <translated-text code="USER.ACCOUNT.MENU.WISHLISTS" />
                <v-icon>external-link-icon</v-icon>
            </v-btn>

            <v-spacer />

            <v-btn
                v-if="isDealerAdminUserRole"
                text
                tile
                :class="{'active': active === 'dealer-list'}"
                :href="RouteGenerator.generate('web.account.dealer.users')"
            >
                <translated-text code="USER.ACCOUNT.MENU.DEALER_LIST" />
                <v-icon>external-link-icon</v-icon>
            </v-btn>

            <v-spacer />

            <v-btn
                text
                tile
                :class="{'active': active === 'user-account-profile'}"
                :href="RouteGenerator.generate('web.account')"
            >
                <translated-text code="USER.ACCOUNT.MENU.ACCOUNT" />
                <v-icon>external-link-icon</v-icon>
            </v-btn>

            <v-spacer />
        </div>

<!--        <div class="user-account-navigation__country">-->
<!--            <v-select label="Land" outlined :menu-props="{offsetY: true}" hide-details></v-select>-->
<!--        </div>-->

<!--        <div class="user-account-navigation__language">-->
<!--            <div class="user-account-navigation__language__label">-->
<!--                <translated-text code="USER.ACCOUNT.LANGUAGE" />-->
<!--            </div>-->

<!--            <v-btn-toggle v-model="language">-->
<!--                <v-btn outlined color="grey">DE</v-btn>-->
<!--                <v-btn outlined color="grey">EN</v-btn>-->
<!--            </v-btn-toggle>-->
<!--        </div>-->

        <div class="user-account-navigation__logout">
            <v-btn
                text
                class="user-account-navigation__logout__btn"
                :href="RouteGenerator.generate('default.logout')"
            >
                <v-icon>logout-icon</v-icon>
                <translated-text code="USER.ACCOUNT.BUTTON.LOGOUT" />
            </v-btn>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import RouteGenerator from '../../modules/RouteGenerator';
    import TranslatedText from '../translated-text/TranslatedText';

    export default {
        name: 'UserAccountNavigation',

        props: {
            active: {
                type: String,
                required: false,
                default: null,
            },
        },

        components: {TranslatedText},

        data() {
            return {
                RouteGenerator,
                // language: 0,
            };
        },
        computed: {
            ...mapState('global', ['me']),

            helloParams() {
                return Object.keys(this.me).length > 0
                    ? {first_name: this.me.firstName, last_name: this.me.lastName}
                    : {};
            },

            isMeLoaded() {
                return Object.keys(this.me).length !== 0;
            },

            isDealerUserRole() {
                return this.isMeLoaded && this.me.isDealerRole()
            },

            isDealerAdminUserRole() {
                return this.isMeLoaded && this.me.isDealerAdminRole();
            },
        },

        watch: {},

        methods: {
        },

    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .user-account-navigation {
        &__hello {
            color: #485c74;
            @include normal-font(28px, 32px);
            font-family: $fontSomfySansLight;
            margin-bottom: 8px;
        }

        &__welcome {
            font-family: $fontSomfySansLight;
            @include normal-font(16px, 21px);
            color: #485c74;
            margin-bottom: 45px;
        }

        &__menu {
            position: relative;
            white-space: nowrap;
            flex: 1 0 auto;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.5, 1) 0s;

            .v-btn {
                display: flex;
                font-family: $fontSomfySansRegular;
                font-size: 16px !important;
                height: auto !important;
                justify-content: flex-start;
                letter-spacing: normal;
                line-height: 21px;
                text-transform: none;
                border-bottom: 1px solid rgb(202, 207, 213);
                padding: 16px 20px 16px 0;
                margin: 0;
                color: rgba(0, 0, 0, 0.54);

                &:hover {
                    text-decoration: none;
                }

                &.active {
                    color: rgb(252, 172, 34);

                    &:hover:before,
                    &:focus:before {
                        background: rgb(252, 172, 34);
                        opacity: 0.12;
                    }
                }

                &::v-deep {
                    .v-btn__content {
                        justify-content: space-between;
                    }
                }

                .v-icon {
                    font-size: 17px;
                    margin: 0;
                }
            }
        }

        //&__country {
        //    margin-top: 20px;
        //    margin-bottom: 20px;
        //}

        //&__language {
        //    margin-top: 20px;
        //    margin-bottom: 20px;
        //
        //    &__label {
        //        color: #485c74;
        //        @include normal-font(14px, 19px);
        //        font-family: $fontSomfySansLight;
        //        margin-bottom: 10px;
        //    }
        //
        //    .v-btn {
        //        padding: 0;
        //        height: 40px !important;
        //        min-width: 40px !important;
        //        background: #fff;
        //        font-size: 14px !important;
        //        color: #8996a4 !important;
        //        margin-right: 12px;
        //        border-radius: 4px;
        //        border-width: 1px !important;
        //
        //        &-toggle {
        //            background: none;
        //        }
        //
        //        &--active {
        //            background: #8996a4 !important;
        //            color: #fff !important;
        //            border: 1px solid #8996a4 !important;
        //        }
        //    }
        //}

        &__logout {
            color: #485c74;
            padding: 0 8px !important;
            margin-top: 30px;
            margin-left: -25px;

            &__btn {
                text-decoration: none;
            }

            .v-icon {
                font-size: 16px;
                margin-right: 8px;
            }

        }

        .user-account-navigation__logout__btn {
            color: rgb(72, 92, 116);
        }
    }
</style>
