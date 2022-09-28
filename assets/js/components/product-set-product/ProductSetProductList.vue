<template>
    <div class="product-set-cart-list">
        <!--
        <h3 class="product-set-cart-list__details--title">{{ item.title }}</h3>
        -->
        <template v-if="replacedProducts.length > 0 && replacedProduct">
            <div class="card-items">
                <template v-for="(product, index) in replacedProducts">
                    <product-set-product
                        :wish-list-id="wishListId"
                        :wish-list-product-set-id="item.id"
                        :item="product"
                        :product-set-index="index"
                        :hide-duplicates="hideDuplicates"
                        :is-wish-list-page="isWishListPage"
                        is-replacement-product
                    />
                </template>

                <div v-if="!hideBeforeCustomization" class="before-customization">
                    <h3 class="card-items--subtitle">
                        <translated-text code="PRODUCT_SET.DECISION_RESULT.REPLACED_BEFORE"/>
                    </h3>

                    <product-set-product
                        hide-quantity
                        hide-tip
                        :item="replacedProduct"
                        :is-wish-list-page="isWishListPage"
                    />
                </div>
            </div>
        </template>

        <template v-for="(product, index) in products">
            <product-set-product
                v-if="!excludedByWhereToBuy(product)"
                :wish-list-id="wishListId"
                :wish-list-product-set-id="item.id"
                :product-set-index="index"
                :item="product"
                :hide-duplicates="hideDuplicates"
                :hide-quantity="hideQuantity"
                :is-wish-list-page="isWishListPage"
            />
        </template>

        <div v-if="visibleOptionalProductsQty > 0" class="optional-products">
            <v-expansion-panels
                v-model="panel"
                multiple
            >
                <v-expansion-panel>
                    <v-expansion-panel-header v-if="!isWishListPage">
                        <product-set-optional-product-header
                            :optional-products-qty="visibleOptionalProductsQty"
                        />
                    </v-expansion-panel-header>

                    <v-expansion-panel-content>
                        <template v-for="(product, index) in optionalProducts">
                            <product-set-product
                                v-if="!product.isHiddenOnWishListPage && !excludedByWhereToBuy(product)"
                                :wish-list-id="wishListId"
                                :wish-list-product-set-id="item.id"
                                :product-set-index="index"
                                :is-optional-product=true
                                :item="product"
                                :hide-duplicates="hideDuplicates"
                                :hide-quantity="hideQuantity"
                                :is-wish-list-page="isWishListPage"
                            />
                        </template>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>
    </div>
</template>

<script>
    import {mapState, mapGetters} from "vuex";
    import ProductSet from "../../models/product-set";
    import ProductSetStore from "../product-set/store";
    import ProductSetLike from "../product-set/ProductSetLike";
    import ProductSetRemove from "../product-set/ProductSetRemove";
    import TranslatedText from "../translated-text/TranslatedText";
    import WishListProductSet from "../../models/wish-list-product-set";
    import ProductSetProduct from "./ProductSetProduct";
    import ProductSetOptionalProductHeader from "./ProductSetOptionalProductHeader";
    import _filter from "lodash/filter";
    import _find from "lodash/find";
    import WishListProductSetProduct from "../../models/wish-list-product-set-product";
    import WishListStore from "../wish-list/store";
    import {EventBus, events as EVENTS} from "../../modules/EventBus";

    export default {
        name: "ProductSetProductList",

        props: {
            wishListId: {
                type: Number | null,
                required: false,
                default: null
            },

            item: {
                type: WishListProductSet | ProductSet,
                required: true
            },

            hideDuplicates: {
                type: Boolean,
                required: false,
                default: false,
            },

            hideQuantity: {
                type: Boolean,
                required: false,
                default: false,
            },

            showOnlineOnly: {
                type: Boolean,
                required: false,
                default: false,
            },

            showRetailOnly: {
                type: Boolean,
                required: false,
                default: false,
            },

            isWishListPage: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        components: {ProductSetProduct, TranslatedText, ProductSetRemove, ProductSetLike, ProductSetOptionalProductHeader},

        data() {
            return {};
        },

        computed: {
            ...mapState("wishList", ["wishList"]),
            ...mapGetters("productSet", [
                "isProductSetUsesDecision",
                "getDeletedProductSetProduct",
                "getReplacedProductSetProduct",
                "getReplacedProductSetProducts",
            ]),

            hideBeforeCustomization(){
                if (this.item instanceof WishListProductSet) {
                    return true;
                }

                return this.isProductSetUsesDecision(this.item.id);
            },

            deletedProduct(): ?ProductSetProduct {
                return !this.wishListId ? this.getDeletedProductSetProduct(this.item.id) : null;
            },

            replacedProduct(): ?ProductSetProduct {
                return !this.wishListId ? this.getReplacedProductSetProduct(this.item.id) : null;
            },

            replacedProducts(): Array<ProductSetProduct> {
                return !this.wishListId ? this.getReplacedProductSetProducts(this.item.id) : [];
            },

            products() {
                if (this.wishListId) {
                    return this.item.displayedProducts;
                }

                // do not show products that were replaced
                let replacedProductIds = [...this.replacedProducts.map(product => product.product.id)];
                if (replacedProductIds.length > 0) {
                    return _filter(this.item.displayedProducts, (product: ProductSetProduct) => {
                        // was not replaced
                        return replacedProductIds.indexOf(product.product.id) === -1
                    });
                }


                return this.item.displayedProducts;
            },

            optionalProducts() {
                let result = this.item.displayedOptionalProducts;

                if (this.isWishListPage) {
                    setTimeout(() => {
                        // prepare Optional Products to display on Wish List page
                        // (a small delay for the case when the user changes product quantity)
                        this.prepareOptionalProductsOnWishListPage(result);
                    }, 500);
                }

                return result;
            },

            visibleOptionalProductsQty() {
                return _filter(this.optionalProducts, (product: ProductSetProduct | WishListProductSetProduct) => {
                    return !this.isWishListPage || !product.isHiddenOnWishListPage;
                }).length;
            },

            panel: {
                get() {
                    return [...Array(this.optionalProducts.length).keys()];
                },
                set(value) {
                },
            },
        },

        watch: {
            wishList: {
                handler(newVal, oldVal) {
                    // this function is used only wish-list page for Optional products
                    if (!this.isWishListPage || !this.optionalProducts.length) {
                        return;
                    }

                    let wishListProductSetId = this.item.id,
                        newProductSet = _find(newVal.productSets, {id: wishListProductSetId}),
                        oldProductSet = _find(oldVal.productSets, {id: wishListProductSetId});

                    if (!newProductSet || !oldProductSet) {
                        // data not found => exit
                        return;
                    }

                    if (JSON.stringify(newProductSet.displayedOptionalProducts) === JSON.stringify(oldProductSet.displayedOptionalProducts)) {
                        // Optional products are the same => exit
                        return;
                    }

                    // update current Optional products list
                    this.prepareOptionalProductsOnWishListPage(this.item.displayedOptionalProducts);
                },
                deep: true,
            },
        },

        methods: {
            switchOriginalProductToAlternativeFinalLevel({
                                                             mode,
                                                             productSetIndex,
                                                             originalProductId,
                                                             alternativeProductId
                                                         }) {
                let productVarName;

                if (mode === 'regular') {
                    productVarName = 'products';
                } else if (mode === 'optional') {
                    productVarName = 'optionalProducts';
                } else {
                    productVarName = 'replacedProducts';
                }

                if (!this[productVarName] || !this[productVarName][productSetIndex]) {
                    console.error('Error: products not found by productSetIndex = _' + productSetIndex + '_');
                    return;
                }

                let productSetItem = this[productVarName][productSetIndex],
                    productSetProducts = {};
                productSetProducts[productSetItem.product.id] = productSetItem.product;
                let alternativeProducts = productSetItem.product.alternativeProducts;

                for (let i in alternativeProducts) {
                    productSetProducts[alternativeProducts[i].id] = alternativeProducts[i];
                }

                if (!productSetProducts[originalProductId] || !productSetProducts[alternativeProductId]) {
                    console.error('Error: products not found by originalProductId/alternativeProductId');
                    return;
                }

                let originalProduct = productSetProducts[originalProductId],
                    originalProductAlternatives = productSetProducts[originalProductId].alternativeProducts,
                    alternativeProduct = productSetProducts[alternativeProductId];
                originalProduct.alternativeProducts = [];
                alternativeProduct.alternativeProducts = [];

                for (let i in originalProductAlternatives) {
                    if (originalProductAlternatives[i].id === alternativeProductId) {
                        // replace original alternative product with original product
                        originalProductAlternatives[i] = originalProduct;
                        break;
                    }
                }

                // store changed alternative products list of original product in the alternative product
                alternativeProduct.alternativeProducts = originalProductAlternatives;

                // switch the original product with the alternative product
                this[productVarName][productSetIndex].product = alternativeProduct;
            },

            prepareOptionalProductsOnWishListPage(optionalProductsArr) {
                for (let i in optionalProductsArr) {
                    let optionalProduct = optionalProductsArr[i];

                    if (!optionalProduct.currentQuantity) {
                        // hide optional products having quantity = 0
                        optionalProduct.isHiddenOnWishListPage = true;
                    }
                }

                return optionalProductsArr;
            },

            excludedByWhereToBuy(product) {
                return (this.showOnlineOnly && (!product.product.isOnlineOnlyProduct || product.currentQuantity === 0))
                    || (this.showRetailOnly && (!product.product.isRetailOnlyProduct || product.currentQuantity === 0));
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("productSet")) {
                this.$store.registerModule("productSet", ProductSetStore);
            }

            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        },

        created() {
            EventBus.on(EVENTS.PRODUCT_SET_PRODUCT_SWITCHED_WITH_ALTERNATIVE, this.switchOriginalProductToAlternativeFinalLevel);
        },

        destroyed() {
            EventBus.off(EVENTS.PRODUCT_SET_PRODUCT_SWITCHED_WITH_ALTERNATIVE);
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .product-set-cart-list {
        &__details {
            &--title {
                @include normal-font(18px, 24px);
                margin-bottom: 3px;
            }
        }
    }

    .card-items {
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #d5dadf;
        margin-bottom: 11px;
    }

    .card-items {
        &::v-deep {
            .cart-item {
                border: none;
                padding: 0;
                margin-bottom: 16px;

                & + .cart-item {
                    margin-top: 0;
                }

                &-info__btn,
                &-info__close{
                    top: -5px;
                    right: -5px;
                }
            }
        }

        .before-customization {
            &::v-deep {
                .cart-item {
                    background: #F3F5F8;
                    padding: 15px;

                    &-info__btn,
                    &-info__close{
                        top: 5px;
                        right: 5px;
                    }

                    &-info__content {
                        background: #F3F5F8;
                    }
                }
            }
        }

        &--subtitle {
            @include normal-font(16px, 19px);
            color: #000;
            margin-bottom: 10px;
            font-family: $fontSomfySansLight;
        }
    }

    .optional-products {
        margin: 8px 0 0 0;

        &::v-deep {
            .v-expansion-panel {
                background: none;
                border-radius: 0 !important;
                margin-top: 0 !important;

                .v-expansion-panel-header {
                    padding: 0;
                    min-height: 0;
                    color: #343a40;
                    @include normal-font(16px, 21px);
                    font-family: $fontSomfySansMedium;

                    &__icon {
                        margin-top: 5px;
                    }
                }

                .v-expansion-panel-content__wrap {
                    padding: 0 0 0 0;
                    margin-top: 8px;
                }

                &:after,
                &:before {
                    display: none !important;
                }

                @media (max-width: 767px) {
                    padding-left: 0;
                    padding-right: 0;
                }
            }
        }
    }

</style>
