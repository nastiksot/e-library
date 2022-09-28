<template>
    <div class="app-header">

        <template  v-if="Object.keys(dealer).length > 0">
            <div class="header-logos">
                <a :href="homepageLink" class="app-header__logo app-header__logo-dealer">
                    <img src="../../../../assets/images/logo-dealer.svg" :alt="dealer.title">
                </a>
                <a v-if="dealerLogo" :href="homepageLink" class="app-header__logo app-header__logo-partner">
                    <img :src="dealerLogo" :alt="dealer.title">
                </a>
            </div>
        </template>

        <template v-else>
            <a :href="homepageLink" class="app-header__logo">
                <img src="../../../../assets/images/logo.svg" alt="">
            </a>
        </template>

        <div class="app-header__menu">
            <v-btn
                text
                tile
                :class="{'active': active === 'day-with-somfy'}"
                :href="dayWithSomfyLink"
            >
                <v-icon>home-clock-icon</v-icon>
                <translated-text code="NAVIGATION.DAY_WITH_SOMFY"/>
            </v-btn>

            <v-btn
                text
                tile
                :class="{'active': active === 'home-planner'}"
                :href="homePlannerLink"
            >
                <v-icon>smart-home-icon</v-icon>
                <translated-text code="NAVIGATION.SMART_HOME_PLANNER"/>
            </v-btn>

            <v-btn
                text
                tile
                :class="{'active': active === 'wishlist'}"
                :href="wishListLink"
                :disabled="null === wishListLink"
            >
                <v-badge
                    color="orange"
                    overlap
                    inline
                    :value="wishListCounter"
                    :content="wishListCounter"
                >
                    <v-icon>heart-icon</v-icon>
                </v-badge>
                <translated-text code="NAVIGATION.WISH_LIST"/>
            </v-btn>
        </div>
        <div class="app-header__actions">
            <share/>

            <save-configuration/>

            <v-btn
                icon
                class="app-header__account-btn"
                :class="{'active': active === 'account'}"
                :href="RouteGenerator.generate('web.account')"
            >
                <v-icon>user-icon</v-icon>
                <!-- <translated-text code="NAVIGATION.MY_ACCOUNT"></translated-text>-->
            </v-btn>
            <language/>
        </div>

        <alert-message v-if="alertMessages.length > 0"/>

    </div>
</template>

<script>
    import WishListStore from "../wish-list/store";
    import {mapState} from "vuex";
    import Share from "../share/Share";
    import RouteGenerator from "../../modules/RouteGenerator";
    import SaveConfiguration from "../save-configuration/SaveConfiguration";
    import TranslatedText from "../translated-text/TranslatedText";
    import Language from "../Language";
    import AlertMessageStore from "../alert-message/store";
    import AlertMessage from "../alert-message/AlertMessage";

    export default {
        name: "AppHeader",

        components: {AlertMessage, Language, TranslatedText, SaveConfiguration, Share},

        props: {
            active: {
                type: String,
                required: false,
                default: "",
            },
        },

        data() {
            return {
                RouteGenerator,
            };
        },

        computed: {
            ...mapState("global", ["me"]),
            ...mapState("global", ["dealer"]),
            ...mapState("wishList", ["wishList"]),
            ...mapState("alertMessage", ["alertMessages"]),

            dealerLogo() {
                return Object.keys(this.dealer).length > 0 && this.dealer.image
                    ? RouteGenerator.generate('web.image', {
                        type: 'dealer',
                        crop: 'resize',
                        size: '150x49',
                        name: this.dealer.image,
                    })
                    : null;
            },

            wishListCounter() {
                return typeof (this.wishList.productSets) !== 'undefined'
                    ? this.wishList.productSets.length
                    : 0;
            },

            homepageLink() {
                return Object.keys(this.dealer).length > 0
                    ? RouteGenerator.generate('web.dealer.homepage', {dealerSlug: this.dealer.slug})
                    : RouteGenerator.generate('web.homepage');
            },

            homePlannerLink() {
                return Object.keys(this.dealer).length > 0
                    ? RouteGenerator.generate('web.dealer.home_planner', {dealerSlug: this.dealer.slug})
                    : RouteGenerator.generate('web.home_planner');
            },

            dayWithSomfyLink() {
                return Object.keys(this.dealer).length > 0
                    ? RouteGenerator.generate('web.dealer.homepage', {dealerSlug: this.dealer.slug})
                    : RouteGenerator.generate('web.homepage');
            },

            wishListLink() {
                if (Object.keys(this.dealer).length > 0 && Object.keys(this.me).length > 0) {
                    return RouteGenerator.generate('web.dealer.wishlist.details', {
                        dealerSlug: this.dealer.slug,
                        wishListUid: this.me.uid
                    });
                }

                return Object.keys(this.me).length > 0
                    ? RouteGenerator.generate('web.wishlist.details', {wishListUid: this.me.uid})
                    : null;
            },
        },

        watch: {},

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
            if (!this.$store.hasModule("alertMessage")) {
                this.$store.registerModule("alertMessage", AlertMessageStore);
            }
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .app-header {
        @include flexbox();
        @include align-items(center);
        padding-right: 20px;
        padding-left: 30px;
        position: relative;

        @media all and (max-width: 660px){
            flex-wrap: wrap;
        }

        &__account-btn {
            margin-left: 5px;
            margin-right: 5px;

            .v-icon {
                color: #3c4f64;
                font-size: 20px;
            }

            &.active {
                .v-icon {
                    color: #fcac22;
                }
            }

            &:hover {
                text-decoration: none;
            }
        }

        .header-logos {
            display: inline-flex;
            @include align-items(center);
            @include justify-content(center);
            @media all and (max-width: 660px){
                flex: 0 0 100%;
                margin-bottom: 20px;

                &~.app-header__actions{
                    margin-left: 0;
                }
            }
        }

        &__logo {
            margin-right: 55px;
            @include flex(none);

            @media (max-width: 1180px) {
                margin-right: 20px;
            }

            &-dealer {
                margin-right: 34px;
                position: relative;

                &:after {
                    content: '';
                    display: block;
                    width: 1px;
                    height: 28px;
                    background: #3C4F64;
                    position: absolute;
                    right: -20px;
                    top: 50%;
                    transform: translateY(-50%);
                }
            }

            &-partner {
                margin-right: 30px;
                max-width: 132px;
            }
        }

        &__actions {
            margin-left: auto;
            @include flexbox();
            @include align-items(center);

            .share {
                margin-right: 15px;
            }

            @media (max-width: 991px) {
                .v-btn {
                    min-width: 42px;
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

        &__menu {
            .v-btn {
                height: auto !important;
                padding: 24px 0;
                margin-right: 28px;
                font-size: 18px !important;
                color: #3c4f64;
                border-bottom: 4px solid transparent;


                .v-icon {
                    font-size: 20px;
                }

                &::v-deep {
                    .v-badge {
                        &__wrapper {
                            margin: 0;
                            position: absolute;
                            display: block;
                            width: 13px;
                            height: 13px;
                            top: -3px;
                            right: 3px;
                            left: auto;
                        }

                        &__badge {
                            padding: 0;
                            @include normal-font(8px, 13px);
                            min-width: 13px;
                            height: 13px;
                            display: block;

                            @media (max-width: 991px) {
                                right: -10px;
                            }
                        }
                    }
                }

                &.active {
                    color: #fcac22;
                    border-bottom-color: #fcac22;
                }

                &:hover {
                    text-decoration: none;
                }

                @media (max-width: 1280px) {
                    margin-right: 20px;
                    font-size: 13px !important;
                }


                @media (max-width: 1024px) {
                    margin-right: 5px;
                }

                @media (max-width: 991px) {
                    margin-right: 0;
                    font-size: 14px !important;
                    padding: 10px 0 5px;

                    &::v-deep {
                        .v-btn__content {
                            @include flex-direction(column);

                            .v-icon {
                                margin-right: 0;
                                margin-bottom: 5px;
                            }
                        }
                    }

                    + .v-btn {
                        margin-left: 20px;
                    }
                }

                @media (max-width: 600px) {
                    font-size: 12px !important;
                }
            }

            @media (max-width: 991px) {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1111;
                background: #fff;
                @include flexbox();
                @include justify-content(center);
                box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16);
            }
        }

        @media (max-width: 1130px) {
            padding-left: 20px;
        }

        @media (max-width: 991px) {
            padding: 16px;
        }

        &::v-deep {
            .alert-message {
                position: absolute;
                left: 0;
                right: 415px;
                top: 100%;
                z-index: 9999;

                @media all and (max-width: 992px) {
                    right: 0;
                }
            }
        }
    }
</style>
