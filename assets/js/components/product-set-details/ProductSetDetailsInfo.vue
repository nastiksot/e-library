<template>
    <div class="product-details-info">
        <div class="product-details-info__details">
            <div class="product-details-info__details--inner">
                <div class="product-details-info__actions">
                    <v-btn icon small class="back-btn" @click="onClose">
                        <v-icon>back-icon</v-icon>
                    </v-btn>

                    <product-set-like :product-set="item" :active="isLiked" :is-day-with-somfy-page="isDayWithSomfyPage"/>
                    <!-- <product-set-remove :product-set-id="originalId" v-if="isInWishList" @click="onClose"/>-->
                </div>
                <div class="product-details-info__title">
                    {{ item.title }}
                </div>
                <div class="product-details-info__text">
                    <translated-text code="USECASE.RECOMMENDED_PRODUCTS"/>
                    ({{ item.displayedProducts.length }})
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import ProductSet from "../../models/product-set";
    import WishListProductSet from "../../models/wish-list-product-set";
    import ProductSetLike from "../product-set/ProductSetLike";
    import ProductSetRemove from "../product-set/ProductSetRemove";
    import TranslatedText from "../translated-text/TranslatedText";
    import VideoPlayerStore from "../video-player/store";
    import WishListStore from "../wish-list/store";
    import {EventBus, events as EVENTS} from "../../modules/EventBus";

    export default {
        name: "ProductSetDetailsInfo",

        props: {
            item: {
                type: ProductSet | WishListProductSet,
                required: true
            },

            isDayWithSomfyPage: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        components: {ProductSetRemove, TranslatedText, ProductSetLike},

        data() {
            return {
                show: false
            };
        },

        computed: {
            ...mapGetters("wishList", ["selectedProductSets"]),

            isInWishList() {
                return this.item instanceof WishListProductSet;
            },

            isLiked() {
                return this.selectedProductSets.indexOf(this.originalId) !== -1;
            },

            originalId() {
                return this.isInWishList ? this.item.originalId : this.item.id;
            },
        },

        watch: {},

        methods: {
            onClose() {
                EventBus.emit(EVENTS.PRODUCT_SET_DETAILS_CLOSED);
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("videoPlayer")) {
                this.$store.registerModule("videoPlayer", VideoPlayerStore);
            }

            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .product-details-info {
        width: 100%;

        &::v-deep {
            .product-set-like .v-btn.active .heart-icon {
                &:before {
                    text-shadow: none;
                }
            }
        }

        &__actions {
            text-align: left;
            @include flexbox();
            @include align-items(center);
            margin-bottom: 8px;
            @include transition(margin 0.4s ease-in-out);
            position: absolute;
            left: 0;
            right: 0;

            .back-btn {
                color: #3C4F64;

                .v-icon {
                    font-size: 14px;
                }
            }

            &::v-deep {
                .product-set-like {
                    position: static;
                    margin-left: auto;

                    .v-icon {
                        color: #3C4F64;
                        font-size: 26px;
                    }
                }
            }
        }

        &__details {
            padding: 17px 20px 10px;
            position: relative;
            background: #fff;
            @include transition(padding 0.4s ease-in-out);

            &--inner {
                position: relative;
                z-index: 2;
                color: #fff;
                text-align: center;
            }

            &:before {
                content: "";
                position: absolute;
                z-index: 1;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                background: #fff;
            }
        }

        &__title {
            @include normal-font(24px, 32px);
            margin-bottom: 8px;
            color: #FCAC22;

            @media all and (max-width: 992px){
                padding-left: 20px;
                padding-right: 20px;
            }
        }

        &__text {
            @include normal-font(14px, 16px);
            margin: 0;
            font-family: $fontSomfySansLight;
            color: #343A40;
            @include transition(margin 0.4s ease-in-out);
        }
    }
</style>

