<template>
    <div class="wish-list-products">
        <div v-for="productSet in wishList.productSets" class="product-set-item">
            <product-set-product-list
                :wish-list-id="wishList.id"
                :item="productSet"
                :key="productSet.id"
                :hide-duplicates="true"
                is-wish-list-page
            />
        </div>

<!--        <div class="cart-total&#45;&#45;info">-->
<!--            <div class="cart-total&#45;&#45;info-details">-->
<!--                <div class="cart-total&#45;&#45;info-price-title">-->
<!--                    Preis (UVP) gesamt:-->
<!--                </div>-->
<!--                <div class="cart-total&#45;&#45;info-text">-->
<!--                    <translated-text code="PRICE.NOTICE.INSTALL"/>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="cart-total&#45;&#45;info-price">-->
<!--                <template v-if="isRangePrice">-->
<!--                    <div v-html="priceRangeValue"/>-->
<!--                    <span v-html="priceRangeNotice"/>-->
<!--                </template>-->
<!--                <template v-else>-->
<!--                    ab {{ totalPrice.toLocaleString() }} €-->
<!--                </template>-->
<!--            </div>-->
<!--        </div>-->

        <div class="mt-2">
            *<translated-text code="PRICE.INCLUDES_VAT"/>
        </div>
    </div>
</template>

<script>
    import WishListStore from "./store";
    import {mapGetters, mapState} from "vuex";
    import ProductSetProductList from "../product-set-product/ProductSetProductList";
    import TranslatedText from "../translated-text/TranslatedText";

    export default {
        name: "WishListProductSetCartList",

        props: {},

        components: {TranslatedText, ProductSetProductList},

        data() {
            return {};
        },

        computed: {
            ...mapState("wishList", ["wishList"]),
            ...mapGetters("wishList", [
                "totalPrice",
                "totalPriceEnd",
                "isRangePrice",
            ]),

            priceRangeParams() {
                return {
                    price: this.totalPrice,
                    price_end: this.totalPriceEnd
                };
            },

            priceRangeValue() {
                return this.isRangePrice
                    ? this.$t('PRICE.RANGE.VALUE', this.priceRangeParams)
                    : null;
            },

            priceRangeNotice() {
                return this.$tc('PRICE.RANGE.NOTICE');
            },

        },

        watch: {},

        methods: {},

        mounted() {
        },

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        }
    };
</script>

<style scoped lang="scss">

    @import "../../../scss/base";

    .product-set-item + .product-set-item {
        margin-top: 15px;
    }

    .cart-total {
        padding: 20px 0;

        &--info {
            @include flexbox();
            margin-bottom: 10px;

            &-price {
                margin-left: auto;
                color: #000;
                font-family: $fontSomfySansMedium;
                @include normal-font(16px, 21px);
                width: 50%;

                span {
                    display: block;
                    @include normal-font(12px, 14px);
                    font-family: $fontSomfySansRegular;
                }

                &-title {
                    width: auto;
                    @extend .cart-total--info-price;
                }
            }

            &-text {
                @include normal-font(12px, 14px);
                color: #343a40;
                max-width: 150px;
            }
        }

        &--actions {
            @include flexbox();

            .add-to-cart {
                width: 100%;
                @include flex(auto);
            }

            &::v-deep {
                .share {
                    margin-left: 12px;

                    .v-btn {
                        padding: 0;
                        min-width: 44px;
                        height: 44px;

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

</style>
