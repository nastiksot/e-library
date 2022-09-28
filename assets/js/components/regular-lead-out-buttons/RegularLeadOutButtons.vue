<template>
    <div class="regular-lead-out-buttons" v-if="!isEmptyWishList">
        <v-btn v-if="isEmptyRetailOnly" class="buy-now__btn--open" color="orange"
               @click="goToOnlineShop">
            <v-icon>cart-icon</v-icon>
            <translated-text code="LEAD_OUT.BUY_ONLINE.BUTTON.EXTERNAL"/>
        </v-btn>

        <regular-lead-out-button-buy-online v-else
            :input-dialog="dialog"
            :products-qty="productsQty"
            @go-to-somfy-online-shop="goToOnlineShop"/>

        <regular-lead-out-button-find-dealer
            :input-dialog="dialog"
            :products-qty="productsQty"
            @go-to-somfy-online-shop="goToOnlineShop"/>

        <div class="note">
            <translated-text code="LEAD_OUT.NOTE" as-html/>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";
import WishListStore from "../wish-list/store";
import RegularLeadOutButtonBuyOnline from "./RegularLeadOutButtonBuyOnline";
import RegularLeadOutButtonFindDealer from "./RegularLeadOutButtonFindDealer";
import TranslatedText from "../translated-text/TranslatedText";
import _forEach from "lodash/forEach";
import _join from "lodash/join";
import _uniq from "lodash/uniq";

export default {
    name: "RegularLeadOutButtons",

    props: {},

    components: {RegularLeadOutButtonBuyOnline, RegularLeadOutButtonFindDealer, TranslatedText,},

    data() {
        return {
            dialog: {
                buyOnline:  false,
                findDealer: false,
            }
        };
    },

    computed: {
        ...mapState("global", ["me"]),
        ...mapState("wishList", ["wishList"]),

        isEmptyWishList() {
            return Object.keys(this.wishList).length === 0 || this.wishList.isEmptyProductSets;
        },

        productsQty() {
            let productIds       = [],
                dealerProductIds = [],
                onlineProductIds = [];

            _forEach(this.wishList.productSets, (productSet: WishListProductSet) => {
                _forEach(['displayedProducts', 'displayedOptionalProducts'], (propertyName: String) => {
                    _forEach(productSet[propertyName], (product: WishListProductSetProduct) => {
                        if (product.currentQuantity > 0) {
                            productIds.push(product.product.id);

                            if (product.product.isRetailOnlyProduct) {
                                dealerProductIds.push(product.product.id);
                            } else if (product.product.isOnlineOnlyProduct) {
                                onlineProductIds.push(product.product.id);
                            }
                        }
                    });
                });
            });

            let productIdsQty = _uniq(productIds).length,
                dealerProductIdsQty = _uniq(dealerProductIds).length,
                onlineProductIdsQty = _uniq(onlineProductIds).length;

            return {
                notRetailOnly: (productIdsQty - dealerProductIdsQty),
                retailOnly:    dealerProductIdsQty,
                notOnlineOnly: (productIdsQty - onlineProductIdsQty),
                onlineOnly:    onlineProductIdsQty,
            };
        },

        isEmptyRetailOnly() {
            return this.productsQty.retailOnly === 0;
        },
    },

    watch: {},

    methods: {
        goToOnlineShop() {
            if (!this.me.cartAddProductsUrl) {
                return;
            }

            // collect sku and Quantity of products
            let skus = [],
                productIds = [],
                productsCost = 0;

            _forEach(this.wishList.productSets, (productSet: WishListProductSet) => {
                _forEach(productSet.displayedProducts, (product: WishListProductSetProduct) => {
                    if (!product.product.isRetailOnlyProduct) {
                        productIds.push(product.product.id);
                        productsCost += product.productCost;

                        for (let i = 0; i < product.currentQuantity; i++) {
                            skus.push(product.product.sku);
                        }
                    }
                });

                _forEach(productSet.displayedOptionalProducts, (product: WishListProductSetProduct) => {
                    if (!product.product.isRetailOnlyProduct && product.currentQuantity > 0) {
                        productIds.push(product.product.id);
                        productsCost += product.productCost;

                        for (let i = 0; i < product.currentQuantity; i++) {
                            skus.push(product.product.sku);
                        }
                    }
                });
            });

            let strProductIds = _join(_uniq(productIds, ','));
            productsCost = Number(productsCost).toFixed(2);

            this.tcEventClickOnGoToOnlineShop(strProductIds, productsCost);

            setTimeout(() => {
                // a small delay in order to execute tcEvent
                window.location.href = this.me.cartAddProductsUrl + _join(skus, ',');
            }, 100);
        },

        tcEventClickOnGoToOnlineShop(strProductIds, productsCost) {
            if (typeof (tc_events_global) === 'undefined') {
                return;
            }

            try {
                tc_events_global(this, 'add_to_cart', {
                    'evt_category': 'myWishlist',
                    'evt_button_action': 'add_to_cart',
                    'evt_listing_product_ids': strProductIds,
                    'evt_order_total_amount': productsCost
                });
            } catch (error) {
                console.error(error);
            }
        },
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

.regular-lead-out-buttons {
    width: 100%;

    &::v-deep {
        button {
            width: 100%;

            &.buy-now__btn--open {
                margin-bottom: 10px;
            }

            &.find-dealer__btn--open {
                .v-icon {
                    font-weight: bold;
                }
            }
        }
    }

    .note {
        background: #F4F4F4;
        padding: 8px;
        @include flexbox();
        @include align-items(center);
        margin-top: 16px;
        color: #343a40;
        font-family: $fontSomfySansLight;
        @include normal-font(14px, 20px);
    }
}

</style>
