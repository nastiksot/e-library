<template>
    <div class="cart-item" v-if="!isHidden">
        <product-set-product-info :item="item.product" />

        <div class="cart-item__info">
            <a @click.prevent="" href="#" class="cart-item__image">
                <img :src="image" :alt="item.product.title">
            </a>
            <div class="cart-item__details">
                <a v-if="item.product.title" @click.prevent="" href="#" class="cart-item__details--title">
                    {{ item.product.title }}
                </a>

                <a v-if="item.product.name" @click.prevent="" href="#" class="cart-item__details--name">
                    {{ item.product.name }}
                </a>

                <div v-if="item.product.whereToBuy !== 'online_and_retail'" class="cart-item__details--where-to-buy">
                    <translated-text :code="`PRODUCT.AVAILABLE_${item.product.whereToBuy.toUpperCase()}`"/>
                </div>
<!--                <div v-if="item.product.specialShop" class="cart-item__details&#45;&#45;special-shop">-->
<!--                    <translated-text code="PRODUCT.SPECIAL_SHOP" />-->
<!--                </div>-->

                <div class="cart-item__quantity" :class="{'without-amount': hideQuantity}">
                    <product-set-product-amount
                        v-if="!hideQuantity"
                        :wish-list-id="wishListId"
                        :wish-list-product-set-id="wishListProductSetId"
                        :item="item"
                    />

                    <product-set-product-price
                        :product="item.product"
                        :is-alternative-product="isAlternativeProduct"
                        :original-product-id="originalProductId"
                        :product-set-index="productSetIndex"
                        :is-optional-product="isOptionalProduct"
                        :is-replacement-product="isReplacementProduct"
                    />
                </div>
            </div>
        </div>

        <template v-if="!hideTip">
            <product-set-product-tip
                v-if="showDuplicateWarning"
                code="PRODUCT.WARNING.DUPLICATE"
                icon="warning-icon"
            />

            <product-set-product-tip
                v-else-if="showQuantityZeroWarning && !isOptionalProduct"
                code="PRODUCT.WARNING.QUANTITY_ZERO"
                icon="warning-icon"
            />

            <product-set-product-tip
                v-if="item.product.tip"
                :text="item.product.tip"
            />
        </template>

<!--        <product-set-product-alternative-->
<!--            v-if="item.product.alternativeProducts.length > 0"-->
<!--            :products="item.product.alternativeProducts"-->
<!--        />-->

        <template v-if="!isWishListPage && !hideAlternativeProducts && !isAlternativeProduct && item.product.alternativeProducts.length > 0">
            <div class="cart-item-alternative">
                <h3 class="cart-item-alternative--title">
                    <translated-text code="PRODUCT.ALTERNATIVE_PRODUCTS"/>
                </h3>
                <template v-for="alternativeProduct in item.product.alternativeProducts">
                    <product-set-product
                        :item="{'product': alternativeProduct}"
                        :original-product-id="item.product.id"
                        :product-set-index="productSetIndex"
                        :wish-list-product-set-id="wishListProductSetId"
                        :is-optional-product="isOptionalProduct"
                        :is-replacement-product="isReplacementProduct"
                        hide-quantity
                        hide-tip
                        is-alternative-product
                        v-on="$listeners"
                    />
                </template>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import ProductSetProduct from '../../models/product-set-product';
    import ProductSetProductTip from './ProductSetProductTip';
    import ProductSetProductInfo from './ProductSetProductInfo';
    import ProductSetProductAmount from './ProductSetProductAmount';
    import WishListProductSetProduct from '../../models/wish-list-product-set-product';
    import RouteGenerator from '../../modules/RouteGenerator';
    import TranslatedText from '../translated-text/TranslatedText';
    import ProductSetProductAlternative from './ProductSetProductAlternative';
    import ProductSetProductPrice from './ProductSetProductPrice';
    import WishListStore from "./../wish-list/store";

    export default {
        name: 'ProductSetProduct',

        props: {
            wishListId: {
                type: Number,
                required: false,
                default: null,
            },

            wishListProductSetId: {
                type: Number,
                required: false,
                default: null,
            },

            item: {
                type: WishListProductSetProduct | ProductSetProduct,
                required: true,
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

            hideTip: {
                type: Boolean,
                required: false,
                default: false,
            },

            hideAlternativeProducts: {
                type: Boolean,
                required: false,
                default: false,
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

            isWishListPage: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        components: {
            ProductSetProductPrice,
            ProductSetProductAlternative,
            TranslatedText,
            ProductSetProductAmount,
            ProductSetProductInfo,
            ProductSetProductTip,
        },

        data() {
            return {};
        },

        computed: {
            ...mapState("wishList", ["briefRealWishListProductSets"]),

            isHidden() {
                return (this.hideDuplicates && this.showDuplicateWarning);
            },

            showDeletedWarning() {
                return this.item instanceof WishListProductSetProduct && this.item.deleted;
            },

            showReplacedWarning() {
                return this.item instanceof WishListProductSetProduct && this.item.replaced;
            },

            showDuplicateWarning() {
                let isDuplicated                = false,
                    currentProductId            = this.item.product.id,
                    currentWishListProductSetId = this.wishListProductSetId,
                    isWishListProductSetProduct = !!(this.item instanceof WishListProductSetProduct && currentWishListProductSetId),
                    dbWishListProductSets       = this.briefRealWishListProductSets,
                    currentWishListProductIds   = isWishListProductSetProduct && dbWishListProductSets[currentWishListProductSetId]
                        ? dbWishListProductSets[currentWishListProductSetId]
                        : [];

                for (let dbWishListProductSetId in dbWishListProductSets) {
                    if (isWishListProductSetProduct && currentWishListProductSetId == dbWishListProductSetId) {
                        // it's current WishListProductSet => skip
                        continue;
                    }

                    // get product ids of this loop item
                    let dbWishListProductSetProductIds = dbWishListProductSets[dbWishListProductSetId];

                    if (dbWishListProductSetProductIds.includes(currentProductId)) {
                        // current product is found in this loop products
                        isDuplicated = true;

                        if (isWishListProductSetProduct) {
                            // check current product exists in its own WishListProductSet
                            // this check in case when base and alternative products are switched, but this change not saved yet
                            // eg original WishListProductSetProduct = [1, 5], then "1" product was replaced with alternative "2" product (this change not saved in DB)
                            // thus "2" product not exists in original WishListProductSetProduct
                            let isExistedInOwnWishListProductSet = currentWishListProductIds.includes(currentProductId);

                            if (isExistedInOwnWishListProductSet) {
                                // earlier WishListProductSets have bigger priority
                                // eg there are 3 WishListProductSets {34: [2, 4], 35: [2(duplicated), 5], 36: [1, 5(duplicated)]}.
                                isDuplicated = currentWishListProductSetId > dbWishListProductSetId;
                            }
                        }

                        if (isDuplicated) {
                            break;
                        }
                    }
                }

                return isDuplicated;
            },

            showQuantityZeroWarning() {
                return this.item.currentQuantity <= 0;
            },

            image() {
                return RouteGenerator.generate('web.image', {
                    type: 'product',
                    crop: 'fit',
                    size: '68x68',
                    name: this.item.product.image ?? 'default.jpg',
                });
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        },
    };
</script>

<style lang="scss">
    .cart-item-info.active {
        .cart-item-info__btn {
            display: none;
        }

        + .cart-item__info {
            display: none;
        }
    }
</style>

<style scoped lang="scss">
    @import "../../../scss/base";

    .cart-item {
        position: relative;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #d5dadf;
        background: #fff;

        &__info {
            @include flexbox();
        }

        &__image {
            margin-right: 20px;
            @include flex(none);
        }

        &__details {
            &--title {
                color: #343a40;
                font-family: $fontSomfySansLight;
                @include normal-font(12px, 16px);
                display: block;

                &:hover {
                    text-decoration: none;
                    cursor: default;
                }
            }

            &--name {
                color: #343a40;
                font-family: $fontSomfySansMedium;
                @include normal-font(14px, 21px);
                padding-right: 10px;

                &:hover {
                    text-decoration: none;
                    cursor: default;
                }
            }

            &--where-to-buy {
                @include normal-font(12px, 14px);
                color: #007bff;
            }

            //&--special-shop {
            //    @include normal-font(12px, 14px);
            //    color: #007bff;
            //}
        }

        &__quantity {
            margin-top: 8px;
            @include flexbox();
            @include align-items(center);
        }

        //&__alternative {
        //    margin-top: 10px;
        //
        //    &--title {
        //        color: #000;
        //        @include normal-font(16px, 21px);
        //        font-family: $fontSomfySansLight;
        //        margin-bottom: 12px;
        //    }
        //
        //    &--item {
        //        @include flexbox();
        //        @include align-items(center);
        //        padding: 15px;
        //        background: #f3f5f8;
        //
        //        &-image {
        //            @include flex(none);
        //            margin-right: 20px;
        //        }
        //
        //        &-details {
        //            &-title {
        //                color: #343a40;
        //                font-family: $fontSomfySansLight;
        //                @include normal-font(12px, 16px);
        //            }
        //
        //            &-name {
        //                color: #343a40;
        //                font-family: $fontSomfySansMedium;
        //                @include normal-font(14px, 17px);
        //                padding-right: 10px;
        //            }
        //
        //            &-info {
        //                @include normal-font(12px, 14px);
        //                color: #007bff;
        //            }
        //        }
        //
        //        + .cart-item__alternative--item {
        //            margin-top: 10px;
        //        }
        //    }
        //}

        + .cart-item {
            margin-top: 16px;
        }
    }

    .cart-item-alternative {
        margin-top: 10px;

        .cart-item {
            @include flexbox();
            @include align-items(center);
            padding: 10px 15px 6px;
            background: #f3f5f8;

            ::v-deep .cart-item-info {
                &__content {
                    background: none;
                }
            }

            + .cart-item {
                margin-top: 10px;
            }
        }
    }
</style>
