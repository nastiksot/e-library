<template>
    <div class="product-set-product-price" :class="{'alternative-product' : isAlternativeProduct}">
        <div v-if="product.priceOnRequest" class="product-set-product-price__price empty">
            <translated-text code="PRICE.ON_REQUEST"/>
        </div>
        <template v-else>
            <div class="product-set-product-price__range" v-if="product.isRangePrice">
                <div v-html="priceRangeText" class="product-set-product-price__price"/>

                <v-tooltip top v-if="!isAlternativeProduct">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            small
                            icon
                            v-bind="attrs"
                            v-on="on"
                            class="product-set-product-price__price range-notice-btn"
                        >
                            <v-icon>info-o-icon</v-icon>
                        </v-btn>
                    </template>
                    <span v-html="priceRangeNotice"/>
                </v-tooltip>
            </div>
            <div v-else-if="product.price" class="product-set-product-price__price">
                {{ priceText }} {{ pricePostfix }}
            </div>
        </template>
        <div v-if="isAlternativeProduct"
             class="original-alternative-products-switcher product-set-product-price__price">
            <span @click="switchOriginalProductToAlternativeInitLevel">
                <translated-text code="PRODUCT.SELECT_PRODUCT"/>
            </span>
        </div>
    </div>
</template>

<script>
    import Product from "../../models/product";
    import TranslatedText from "../translated-text/TranslatedText";
    import {priceFormat} from "../../helpers/priceHelper";
    import {EventBus, events as EVENTS} from "../../modules/EventBus";

    export default {
        name: "ProductSetProductPrice",

        props: {
            product: {
                type: Product,
                required: true,
            },

            isAlternativeProduct: {
                type: Boolean,
                required: false,
                default: false,
            },

            originalProductId: {
                type: Number | null,
                required: false,
                default: null,
            },

            productSetIndex: {
                type: Number | null,
                required: false,
                default: null,
            },

            isOptionalProduct: {
                type: Boolean,
                required: false,
                default: false,
            },

            isReplacementProduct: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        components: {TranslatedText},

        data() {
            return {};
        },

        computed: {

            priceRangeParams() {
                return {
                    price: priceFormat(this.product.price),
                    price_end: priceFormat(this.product.priceEnd)
                };
            },

            priceRangeText() {
                return this.product.isRangePrice
                    ? this.$t('PRICE.RANGE.ON_REQUEST', this.priceRangeParams) + '<br>' + this.pricePostfix
                    : null;
            },

            priceText() {
                return priceFormat(this.product.price);
            },

            priceRangeNotice() {
                return this.$tc('PRICE.RANGE.NOTICE');
            },

            pricePostfix() {
                return this.$tc('PRICE.POSTFIX');
            },
        },

        watch: {},

        methods: {
            switchOriginalProductToAlternativeInitLevel() {
                let mode = 'regular';

                if (this.isOptionalProduct) {
                    mode = 'optional';
                } else if (this.isReplacementProduct) {
                    mode = 'replacement';
                }

                EventBus.emit(EVENTS.PRODUCT_SET_PRODUCT_SWITCHED_WITH_ALTERNATIVE, {
                    mode: mode,
                    productSetIndex: this.productSetIndex,
                    originalProductId: this.originalProductId,
                    alternativeProductId: this.product.id
                });
            }
        },
    }
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .product-set-product-price {
        &__price {
            @include normal-font(12px, 16px);
            color: #8996a4;
            text-align: center;

            &.empty {
                max-width: 48px;
            }

            &.range-notice-btn {
                .v-icon {
                    font-size: 20px;
                    background: none !important;
                    color: #B1BBC5 !important;
                }
            }
        }

        &__range{
            @include flexbox();
            @include align-items(center);

            .v-icon{
                color: #B1BBC5;
            }
        }

        &.alternative-product {
            display: flex;

            .original-alternative-products-switcher {
                margin-left: 9px;
                border-left: 1px solid #8996a4;
                padding-left: 8px;
                display: table;

                span {
                    display: table-cell;
                    vertical-align: middle;
                    user-select: none;
                    text-decoration: underline;
                    cursor: pointer;

                    &:hover {
                        text-decoration: none;
                    }
                }
            }
        }
    }
</style>
