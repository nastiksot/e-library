<template>
    <v-dialog
        v-model="dialog.findDealer"
        width="420"
        overlay-color="#000"
        overlay-opacity="0.65"
        content-class="regular-lead-out-buttons-dialog"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-btn class="find-dealer__btn--open"
                   depressed
                   large
                   color="orange"
                   v-bind="attrs"
                   v-on="on">
                <v-icon>location-icon</v-icon>
                <translated-text code="LEAD_OUT.FIND_DEALER.BUTTON.EXTERNAL"/>
            </v-btn>
        </template>

        <v-card>
            <v-card-title>
                <translated-text code="LEAD_OUT.FIND_DEALER.TITLE"/>

                <v-btn icon small class="v-dialog__close" @click="dialog.findDealer = false">
                    <v-icon>close-icon</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text>
                <div class="regular-lead-out-buttons-dialog__item">
                    <template v-if="productsQty.onlineOnly > 0">
                        <div class="regular-lead-out-buttons-dialog__item--text">
                            <translated-text code="LEAD_OUT.BUY_ONLINE.TEXT.PREFIX"/>
                            {{ productsQty.onlineOnly }}
                            <translated-text
                                :code="'LEAD_OUT.BUY_ONLINE.TEXT.POSTFIX_' + (productsQty.onlineOnly > 1 ? 'PLURAL': 'SINGULAR')"/>
                        </div>

                        <div v-for="productSet in wishList.productSets" class="product-set-item">
                            <product-set-product-list
                                show-online-only
                                hide-quantity
                                :wish-list-id="wishList.id"
                                :item="productSet"
                                :key="productSet.id"
                                :hide-duplicates="true"
                                is-wish-list-page
                            />
                        </div>
                    </template>

                    <div v-if="isEmptyNotOnlineOnly" class="empty">
                        <translated-text code="LEAD_OUT.NO_DATA"/>
                    </div>

                    <div class="zip-block">
                        <translated-text code="LEAD_OUT.FIND_DEALER.ZIP_CODE.LABEL" class="zip-block__label"/>

                        <v-text-field v-model="zipIndex"
                                      solo
                                      hide-details="auto"
                                      outlined
                                      class="mb-4"
                        >
                            <template v-slot:label>
                                <translated-link prefix="LEAD_OUT.FIND_DEALER.ZIP_CODE.PLACEHOLDER"/>
                            </template>
                        </v-text-field>
                    </div>

                    <v-btn @click.prevent="clickBuyDealer" depressed large block
                           :disabled="isEmptyZipIndex"
                           :color="clickBuyDealerColor"
                            class="btn__location">
                        <v-icon>location-icon</v-icon>
                        <translated-text code="LEAD_OUT.FIND_DEALER.BUTTON.INTERNAL"/>
                    </v-btn>

                    <translated-text code="LEAD_OUT.OR" class="or"/>

                    <v-btn @click.prevent="clickBuySomfy" depressed large block color="orange" class="btn__shop">
                        <v-icon>cart-icon</v-icon>
                        <translated-text code="LEAD_OUT.BUY_ONLINE.BUTTON.EXTERNAL"/>
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import {mapState} from "vuex";
import WishListStore from "../wish-list/store";
import ProductSetProductList from '../product-set-product/ProductSetProductList';
import TranslatedText from "../translated-text/TranslatedText";
import TranslatedLink from "../translated-text/TranslatedLink";
import _forEach from "lodash/forEach";
import _uniq from "lodash/uniq";
import _join from "lodash/join";

export default {
    name: "RegularLeadOutButtonFindDealer",

    props: {
        inputDialog: {
            type: Object,
            required: true
        },

        productsQty: {
            type: Object,
            required: true
        },
    },

    components: {
        ProductSetProductList,
        TranslatedText,
        TranslatedLink,
    },

    data() {
        return {
            dialog: this.inputDialog,
            zipIndex: '',
        };
    },

    computed: {
        ...mapState("global", ["me"]),
        ...mapState("wishList", ["wishList"]),

        isEmptyZipIndex() {
            return this.zipIndex.length === 0;
        },

        clickBuyDealerColor() {
            return  this.isEmptyZipIndex ? 'grey' : 'orange';
        },

        isEmptyNotOnlineOnly() {
            return this.productsQty.notOnlineOnly === 0;
        },
    },

    methods: {
        clickBuyDealer() {
            if (!this.me.dealerSearchUrl) {
                return;
            }

            this.tcEventClickOnBuyDealer();

            setTimeout(() => {
                // a small delay in order to execute tcEvent
                window.location.href = this.me.dealerSearchUrl + this.zipIndex;
            }, 100);
        },

        tcEventClickOnBuyDealer() {
            if (typeof (tc_events_global) === 'undefined') {
                return;
            }

            let preparedData = this.prepareTcEventData();

            try {
                tc_events_global(this, 'find_a_dealerClick', {
                    'evt_category': 'myWishlist',
                    'evt_button_action': 'find_a_dealerClick',
                    'evt_listing_product_ids': preparedData.strProductIds,
                    'evt_order_total_amount': preparedData.productsCost
                });
            } catch (error) {
                console.error(error);
            }
        },

        prepareTcEventData() {
            let productIds = [],
                productsCost = 0;

            _forEach(this.wishList.productSets, (productSet: WishListProductSet) => {
                _forEach(productSet.displayedProducts, (product: WishListProductSetProduct) => {
                    if (!product.product.isOnlineOnlyProduct) {
                        productIds.push(product.product.id);
                        productsCost += product.productCost;
                    }
                });

                _forEach(productSet.displayedOptionalProducts, (product: WishListProductSetProduct) => {
                    if (!product.product.isOnlineOnlyProduct && product.currentQuantity > 0) {
                        productIds.push(product.product.id);
                        productsCost += product.productCost;
                    }
                });
            });

            return {
                'strProductIds': _join(_uniq(productIds), ','),
                'productsCost': Number(productsCost).toFixed(2),
            };
        },

        clickBuySomfy() {
            this.$emit("go-to-somfy-online-shop");
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
    .zip-block {
        margin: 16px 0;

        &__label {
            color: #343A40;
            @include normal-font(18px, 23px);
        }

        &::v-deep {
            .v-input__slot {
                box-shadow: none !important;
            }
        }
    }

    .btn__location {
        &::v-deep {
            .v-icon {
                font-weight: bold;
            }
        }
    }
    body .v-btn.orange.btn__shop {
        background: transparent!important;
        color: #FCAC22;
        border: 1px solid #FCAC22;
    }
</style>
