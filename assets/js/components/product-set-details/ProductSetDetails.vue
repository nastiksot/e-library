<template>
    <div class="product-set-details" :class="{'mobile' : isMobile }">
        <div class="tabs" v-if="isMobile">
            <v-btn text @click="activeMobileTab = 1" :class="{'active' : activeMobileTab === 1 }">
                <translated-text code="NAVIGATION.TAB.VIDEO"></translated-text>
            </v-btn>
            <v-btn text @click="activeMobileTab = 2" :class="{'active' : activeMobileTab === 2 }">
                <translated-text code="NAVIGATION.TAB.PRODUCT_SET_PRODUCTS_VIEW"></translated-text>
            </v-btn>
        </div>

        <div class="left-side" :class="{'hide-on-mobile' : isMobile && activeMobileTab !== 1 }">
            <video-player v-if="videoCode"/>

            <product-set-details-full-info v-if="moreDetailsProductSetId"
                                           :product-set-id="moreDetailsProductSetId"
                                           :is-mobile="isMobile"
                                           :is-day-with-somfy-page="isDayWithSomfyPage"/>

            <alert v-if="!isMobile && alertData"
                   :type="alertData.type"
                   :color="alertData.color"
                   :message="alertData.message"
            >
            </alert>
        </div>
        <v-navigation-drawer
            v-if="show"
            v-model="show"
            :class="{'hide-on-mobile' : activeMobileTab !== 2 }"
            absolute
            permanent
            width="415px"
            right
        >
            <div v-if="productSet">
                <v-app-bar
                    class="product-set-bar"
                    height="auto"
                    shrink-on-scroll
                    scroll-target="#product-set"
                >
                    <product-set-details-info :item="productSet" :is-day-with-somfy-page="isDayWithSomfyPage"/>
                </v-app-bar>
                <div class="product-set-details__content">
                    <div class="product-set-details__content-top" id="product-set">
                        <product-set-decision-tree
                            v-if="productSet.decisionId && productSet.decisionProductId && !wishListId"
                            :product-set="productSet"
                            v-on:updateProductSet="onDecisionTreeUpdateProductSet"
                        />

                        <product-set-product-list
                            :wish-list-id="wishListId"
                            :item="productSet"
                            :key="productSet.id"
                            :is-wish-list-page="isWishListPage"/>

                        <div class="mt-2">
                            *<translated-text code="PRICE.INCLUDES_VAT"/>
                        </div>

                        <product-set-total :product-set="productSet"/>
                    </div>

                    <div class="product-set-details__content-bottom">
                        <share/>

                        <product-set-save
                            :product-set="productSet"
                            :base-product-set-id="productSetId"
                            :is-apply-decision=true
                            v-on:update-product-set-from-save-button="updateProductSet"
                        />
                    </div>

                    <alert v-if="isMobile && alertData"
                           :type="alertData.type"
                           :color="alertData.color"
                           :message="alertData.message"
                    >
                    </alert>
                </div>
            </div>

        </v-navigation-drawer>
    </div>
</template>

<script>
    import {mapGetters, mapState, mapActions} from "vuex";
    import ProductSet from "../../models/product-set";
    import WishListProductSet from "../../models/wish-list-product-set";
    import CartTotal from "../cart/CartTotal";
    import ProductSetProductList from "../product-set-product/ProductSetProductList";
    import ProductSetTotal from "../product-set/ProductSetTotal";
    import TranslatedText from "../translated-text/TranslatedText";
    import ProductSetDetailsInfo from "./ProductSetDetailsInfo";
    import ProductSetStore from "../product-set/store";
    import WishListStore from "../wish-list/store";
    import ProductSetSave from "../product-set/ProductSetSave";
    import ProductSetDecisionTree from "../product-set/ProductSetDecisionTree";
    import Share from "../share/Share";
    import Alert from "../../components/alert/Alert";
    import AlertStore from "../../components/alert/store";
    import VideoPlayer from "../../components/video-player/VideoPlayer";
    import VideoPlayerStore from "../../components/video-player/store";
    import ProductSetDetailsFullInfo from "../../components/product-set-details/ProductSetDetailsFullInfo";
    import {EventBus, events as EVENTS} from "../../modules/EventBus";

    export default {
        name: "ProductSetDetails",

        props: {
            productSetId: {
                type: Number | null,
                required: false,
                default: null
            },

            wishListId: {
                type: Number | null,
                required: false,
                default: null
            },

            isWishListPage: {
                type: Boolean,
                required: false,
                default: false,
            },

            isDayWithSomfyPage: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        components: {
            Share,
            ProductSetDecisionTree,
            ProductSetSave,
            ProductSetTotal,
            CartTotal,
            TranslatedText,
            ProductSetProductList,
            ProductSetDetailsInfo,
            ProductSetDetailsFullInfo,
            Alert,
            VideoPlayer
        },

        data() {
            return {
                /** @type ProductSet|WishListProductSet */
                productSet: null,
                dialog: false,
                activeMobileTab: 2,
            };
        },

        computed: {
            ...mapState("productSet", ["productSets", "moreDetailsProductSetId"]),
            ...mapState("videoPlayer", ["videoCode"]),
            ...mapState("alert", ["notification"]),

            show: {
                get() {
                    return this.moreDetailsProductSetId !== null;
                },
                set() {
                }
            },

            isInWishList() {
                return this.wishListId !== null;
            },

            alertData() {
                return  this.notification;
            },

            isMobile() {
                return this.$vuetify.breakpoint.mobile;
            }
        },

        watch: {
            productSetId(val) {
                this.updateProductSet(val);
            }
        },

        methods: {
            ...mapGetters("productSet", ["getProductSetById"]),
            ...mapGetters("wishList", ["getProductSetByOriginalId"]),
            ...mapActions("productSet", ["setMoreDetailsProductSetId"]),
            ...mapActions("videoPlayer", ["setVideoUrl"]),

            updateProductSet(productSetId) {
                if (productSetId) {
                    if (this.isInWishList) {
                        this.productSet = this.getProductSetByOriginalId()(productSetId);
                    } else {
                        this.productSet = this.getProductSetById()(productSetId);
                    }

                } else {
                    this.productSet = null;
                }
            },

            onDecisionTreeUpdateProductSet(productSetId: Number) {
                this.updateProductSet(this.productSetId);
            },

            closeProductSet() {
                this.setMoreDetailsProductSetId(null);
                this.setVideoUrl(null);
            },
        },

        mounted() {
            this.updateProductSet(this.productSetId);
        },

        beforeCreate() {
            if (!this.$store.hasModule("productSet")) {
                this.$store.registerModule("productSet", ProductSetStore);
            }

            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }

            if (!this.$store.hasModule("alert")) {
                this.$store.registerModule("alert", AlertStore);
            }

            if (!this.$store.hasModule("videoPlayer")) {
                this.$store.registerModule("videoPlayer", VideoPlayerStore);
            }
        },

        created() {
            EventBus.on(EVENTS.PRODUCT_SET_DETAILS_CLOSED, () => {
                this.closeProductSet();
            });
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";
    @import "../../../scss/components/dialog";

    .product-set-details {
        &.mobile {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: -40px;

            .tabs {
                display: block;
                position: absolute;
                left: 0;
                right: 0;
                top: 0;
                height: 40px;
                text-align: center;
                background: #fff;
                z-index: 999;

                .v-btn {
                    color: #1a1b1c;
                    font-size: 16px;
                    font-family: $fontSomfySansLight;
                    border-bottom: 3px solid transparent;
                    border-radius: 0;
                    min-width: 150px;

                    &.active {
                        color: #fcac22;
                        border-bottom-color: #fcac22;
                    }
                }
            }

            .left-side {
                top: 40px;
                right: 0;

                &.hide-on-mobile {
                    display: none;
                }
            }

            &::v-deep {
                .v-navigation-drawer {
                    width: 100%!important;
                    height: 100%;
                    transform: translateX(0%);
                    display: block;
                    top: 40px !important;

                    &.hide-on-mobile {
                        display: none;
                    }

                    .product-set-details__content {
                        &-top {
                            max-height: none;
                            overflow: visible;
                        }
                    }
                }
            }
        }

        &__title {
            color: #343a40;
            @include normal-font(16px, 21px);
            font-family: $fontSomfySansLight;
            margin-bottom: 20px;
        }

        &__content {
            padding: 20px;

            &-top {
                max-height: 100%;
                overflow: auto;
                padding-right: 3px;
            }

            &-bottom {
                @include flexbox();
                padding-top: 20px;

                &::v-deep {
                    .product-set-save {
                        @include calc("width", "100% - 54px");
                        margin-left: 10px;
                    }

                    .share {
                        .v-btn {
                            padding: 0;
                            height: 44px;
                            min-width: 44px;

                            .v-icon {
                                margin-right: 0;
                                margin-left: 0;
                            }

                            &__text {
                                display: none;
                            }
                        }
                    }
                }
            }
        }

        &__info-btn {
            flex: none;

            .v-icon {
                font-size: 20px;
                background: none !important;
                color: #b1bbc5 !important;
            }
        }

        &::v-deep {
            .description-text {
                @include transition(max-height 0.4s ease-in-out);
                overflow: hidden;
                max-height: 1000px;
            }

            .v-navigation-drawer {
                z-index: 10;

                &__content {
                    overflow: visible;
                    @include flexbox();
                    @include flex-direction(column);

                    > div {
                        height: 100%;
                        @include flexbox();
                        @include flex-direction(column);

                        .product-set-details__content {
                            overflow: auto;
                            @include flexbox();
                            @include flex-direction(column);
                        }
                    }
                }
            }
        }

        .left-side {
            left: 0;
            bottom: 0;
            position: absolute;
            top: 0;
            right: 415px;
        }

        &::v-deep {
            .alert-component {
                position: absolute;
                left: 0;
                right: 0;
                top: 0;
                z-index: 998;
            }
        }
    }

    .product-set-bar {
        &::v-deep {
            box-shadow: none !important;
            background: none;
            @include flex(none);

            .v-toolbar__content {
                padding: 0;
                box-shadow: none !important;
            }

            &.v-app-bar--is-scrolled {
                .description-text {
                    max-height: 0;
                }

                .product-details-info__icon {
                    margin-top: -20px;
                }

                .product-details-info__actions,
                .product-details-info__text {
                    margin-bottom: 0;
                }

                .product-details-info__details {
                    padding-bottom: 15px;
                }
            }
        }
    }
</style>
