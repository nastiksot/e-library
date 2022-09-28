<template>
    <div class="product-set-save">
        <div class="product-set-save--actions" :class="{'progress': busy}">
            <v-progress-circular v-if="busy" indeterminate color="orange" class="spinner" size="30"/>

            <v-btn v-if="!isShowProductSetDecision"
                    class="save-btn" depressed large color="orange"
                   :class="{active: isLiked}"
                   :disabled="busy"
                   @click.prevent="toggleWishListProductSet"
            >
                <v-icon>heart-icon</v-icon>
                <translated-text :code="'PRODUCT_SET.BUTTON.' + (isLiked ? 'SAVED' : 'SAVE')"/>
            </v-btn>

            <div v-else class="offer-to-use-decision-wrap">
                <v-dialog
                    v-model="offerToUseDecisionDialog"
                    width="350"
                    scrollable
                    overlay-color="#000"
                    overlay-opacity="0.65"
                    content-class="offer-to-use-decision-dialog"
                    persistent
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="save-btn product-set-decision-tree__btn" depressed large
                               v-bind="attrs"
                               v-on="on"
                               :outlined="false"
                               color="orange"
                               block
                               :elevation="0"
                        >
                            <v-icon>heart-icon</v-icon>
                            <translated-text code="PRODUCT_SET.BUTTON.SAVE"/>
                        </v-btn>
                    </template>

                    <v-card>
                        <v-card-title>
                            {{ $tc('GENERAL.NOTICE') }}
                            <v-btn icon small class="v-dialog__close" @click="ignoreDecision">
                                <v-icon>close-icon</v-icon>
                            </v-btn>
                        </v-card-title>

                        <v-card-text>
                            <translated-text code="PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.INFO"/>

                            <div class="actions">
                                <v-btn class="orange" outlined block large depressed
                                       @click.prevent="openProductSetDecisionDialog">
                                    <translated-text code="PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.BUTTON.YES"/>
                                </v-btn>

                                <v-btn outlined block large depressed color="orange"
                                    @click.prevent="clickSave(true)">
                                    <translated-text code="PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.BUTTON.NO"/>
                                </v-btn>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-dialog>

                <product-set-decision-tree
                    v-if="productSetDecisionTreeDialog"
                    :base-dialog="true"
                    :product-set="productSet"
                    :is-call-from-add-to-wish-list=true
                    :is-active-request-to-add-product-set-to-wish-list=busy
                    v-on:save-decision="actionOnSaveDecisionData"
                    v-on:ignore-decision="ignoreDecision"
                    v-on:display-msg-about-decision-saved-successfully="displayMsgAboutDecisionSavedSuccessfully"
                />
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters, mapState} from "vuex";
    import ProductSetStore from "../product-set/store";
    import WishListStore from "../wish-list/store";
    import ProductSet from "../../models/product-set";
    import TranslatedText from "../translated-text/TranslatedText";
    import _forEach from "lodash/forEach";
    import _join from "lodash/join";
    import ProductSetProduct from "../../models/product-set-product";
    import WishListProductSet from "../../models/wish-list-product-set";
    import ProductSetDecisionTree from "../product-set/ProductSetDecisionTree";
    import RouterGenerator from "../../modules/RouteGenerator";
    import AlertStore from "../../components/alert/store";
    import {EventBus, events as EVENTS} from "../../modules/EventBus";

    export default {
        name: "ProductSetSave",

        props: {
            productSet: {
                type: ProductSet | WishListProductSet,
                required: true
            },
            baseProductSetId: {
                type: Number | null,
                required: false,
                default: null
            },
            isApplyDecision: {
                type: Boolean | null,
                required: false,
                default: false
            },

            isDayWithSomfyPage: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        components: {TranslatedText, ProductSetDecisionTree, RouterGenerator},

        data() {
            return {
                busy: false,
                productsVarNames: ['displayedProducts', 'displayedOptionalProducts'],
                offerToUseDecisionDialog: false,
                productSetDecisionTreeDialog: false,
                // flag "User ignored possibility to use Decision for product set when adding it to Wish list"
                isUserRefusedUseProductSetDecision: false,
                // flag "User started to use Decision when adding it to Wish list, wait when he closes Decision dialog"
                isWaitingWhenUserCloseAfterSaveDecisionData: false,
            };
        },

        computed: {
            ...mapState("global", ["me",]),
            ...mapState("wishList", ["wishList"]),
            ...mapGetters("wishList", ["selectedProductSets"]),
            ...mapGetters("productSet", ["isProductSetUsesDecision", "getProductSetById"]),

            isLiked() {
                return this.baseProductSetId && this.selectedProductSets.indexOf(this.baseProductSetId) !== -1;
            },

            isShowProductSetDecision() {
                return this.isApplyDecision &&
                    (this.isWaitingWhenUserCloseAfterSaveDecisionData
                        // check current product set is not in Wish list, and it's able to use Decision, but does not use
                        || (!this.isUserRefusedUseProductSetDecision
                            && this.productSet instanceof ProductSet
                            && this.productSet.isAbleUseDecision()
                            && !this.isProductSetUsesDecision(this.baseProductSetId)));
            },

            wishListLink() {
                return Object.keys(this.me).length > 0
                    ? RouterGenerator.generate('web.wishlist.details', {wishListUid: this.me.uid})
                    : '#';
            },
        },

        watch: {
            isLiked(newVal, oldVal) {
                if (oldVal && !newVal) {
                    // product set was removed  from Wish list => reset data so user can use Decision
                    this.isUserRefusedUseProductSetDecision = false;
                }
            },
        },

        methods: {
            ...mapActions("wishList", [
                "addProductSetToWishList",
                "removeProductSetFromWishList"
            ]),
            ...mapActions("productSet", [
                "resetProductSetReplacements",
            ]),
            ...mapActions("alert", ["setNotification"]),

            ignoreDecision() {
                this.closeDecisionDialogs(false);
            },

            actionOnSaveDecisionData(productSetId: Number, isCloseDecisionDialogs: Boolean) {
                let delay = 0;
                if (productSetId) {
                    // updateProductSet
                    this.isWaitingWhenUserCloseAfterSaveDecisionData = true;
                    this.$emit('update-product-set-from-save-button', productSetId);
                    delay = 100;
                }

                setTimeout(() => {
                    this.clickSave(isCloseDecisionDialogs);
                }, delay);
            },

            closeDecisionDialogs(isUserRefusedUseProductSetDecision) {
                // hide all dialog elements
                this.offerToUseDecisionDialog     = false;
                this.productSetDecisionTreeDialog = false;

                this.isUserRefusedUseProductSetDecision = isUserRefusedUseProductSetDecision === undefined ? true : false;
                this.isWaitingWhenUserCloseAfterSaveDecisionData = false;
            },

            displayMsgAboutDecisionSavedSuccessfully() {
                this.closeDecisionDialogs();

                let prefix    = this.$tc('PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.NOTICE.SUCCESS_PREFIX'),
                    linkLabel = this.$tc('PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.NOTICE.SUCCESS_LINK_LABEL'),
                    linkTitle = this.$tc('PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.NOTICE.SUCCESS_LINK_TITLE'),
                    suffix    = this.$tc('PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.NOTICE.SUCCESS_SUFFIX'),
                    message   = prefix + ' <a href="' + this.wishListLink + '" title="' + linkTitle + '">' + linkLabel + '</a>' + suffix;
                this.setNotification({'message': message});
            },

            openProductSetDecisionDialog() {
                this.offerToUseDecisionDialog     = false;
                this.productSetDecisionTreeDialog = true;
            },

            toggleWishListProductSet() {
                if (this.isLiked) {
                    // remove ProductSet from WishList
                    this.clickDeleteFromWishList();
                } else {
                    // add ProductSet from WishList
                    this.clickSave();
                }
            },

            /**
             * @param {ProductSetProduct} product
             * @param {Number} alternativeProductIdsQty
             * @param {Array} alternativeProductIds
             *
             * @returns {Number}
             */
            getOriginalProductId(product, alternativeProductIdsQty, alternativeProductIds) {
                let productId = product.product.id;

                if (alternativeProductIdsQty > 0) {
                    let originalProductId = Object.keys(alternativeProductIds).find(key => alternativeProductIds[key] === productId);

                    if (originalProductId) {
                        productId = originalProductId;
                    }
                }

                return productId;
            },

            clickSave(isCloseDecisionDialogs) {
                this.busy = true;

                if (isCloseDecisionDialogs === true) {
                    this.closeDecisionDialogs();
                }

                let alternativeProductIds = this.generateSwitchedOriginalAndAlternativeProducts(),
                    alternativeProductIdsQty = Object.keys(alternativeProductIds).length,
                    productsQuantityIds = {};

                for (let ind in this.productsVarNames) {
                    let productsVarName = this.productsVarNames[ind];

                    _forEach(this.productSet[productsVarName], (product: ProductSetProduct) => {
                        let productId = this.getOriginalProductId(product, alternativeProductIdsQty, alternativeProductIds);
                        productsQuantityIds[productId] = product.currentQuantity;
                    });
                }

                let replacementProductIds = [];

                _forEach(this.productSet.replacedProductSetProducts, (product: ProductSetProduct) => {
                    let productId = this.getOriginalProductId(product, alternativeProductIdsQty, alternativeProductIds);
                    replacementProductIds.push(productId);
                });

                this.addProductSetToWishList({
                    uid: this.me.uid,
                    wishListId: this.wishList.id,
                    productSetId: this.baseProductSetId,
                    productsQuantityIds: productsQuantityIds,
                    alternativeProductIds: alternativeProductIds,
                    deleteProductId: this.productSet.deleteProductId,
                    replaceProductId: this.productSet.replaceProductId,
                    replacementProductIds: replacementProductIds,
                }).finally(() => {
                    this.postToggleWishListProductSet();

                    this.tcEventClickOnAddToWishList(this.productSet.title);
                });
            },

            tcEventClickOnAddToWishList(productSetTitle) {
                if (typeof (tc_events_global) === 'undefined') {
                    return;
                }

                let id, data;

                if (this.isDayWithSomfyPage) {
                    id = 'inspiration_addto_wishlist';
                    data = {
                        'evt_category': 'inspiration',
                        'evt_button_action': 'addto_wishlist',
                        'evt_button_label': productSetTitle
                    };
                } else {
                    // it's Home planner page
                    let preparedData = this.prepareTcEventData();
                    id = 'smartConfiguration_addto_wishlist';
                    data = {
                        'evt_category': 'SmartHome_planner',
                        'evt_button_action': 'addto_wishlist',
                        'evt_button_label': productSetTitle,
                        'evt_listing_product_ids': preparedData.strProductIds,
                        'evt_order_total_amount': preparedData.productsCost
                    };
                }

                try {
                    tc_events_global(this, id, data);
                } catch (error) {
                    console.error(error);
                }
            },

            prepareTcEventData() {
                let productIds = [],
                    productsCost = 0;

                for (let ind in this.productsVarNames) {
                    let productsVarName = this.productsVarNames[ind];

                    _forEach(this.productSet[productsVarName], (product: ProductSetProduct) => {
                        productIds.push(product.product.id);
                        productsCost += product.productCost;
                    });
                }

                return {
                    'strProductIds': _join(productIds, ','),
                    'productsCost': Number(productsCost).toFixed(2),
                };
            },

            /**
             * the method collects data about which original products have been switched with their alternative products
             * and returns an object in which
             *    -- keys are ids of original products;
             *    -- values are ids of the alternative products that replaced the original products
             */
            generateSwitchedOriginalAndAlternativeProducts() {
                let result = {};

                for(let ind in this.productsVarNames) {
                    let productsVarName = this.productsVarNames[ind];

                    _forEach(this.productSet[productsVarName], (product: ProductSetProduct) => {
                        if (!product.product.isBaseProduct) {
                            // this product is alternative that replaced original product
                            // find id of original product
                            let originalProductId;

                            for (let i in product.product.alternativeProducts) {
                                /** Product alternativeProduct */
                                let alternativeProduct = product.product.alternativeProducts[i];

                                if (alternativeProduct.isBaseProduct) {
                                    // this product is original
                                    originalProductId = alternativeProduct.id;
                                    result[originalProductId] = product.product.id;
                                    break;
                                }
                            }
                        }
                    });
                }

                return result;
            },

            clickDeleteFromWishList() {
                this.busy = true;

                this.removeProductSetFromWishList({
                    uid: this.me.uid,
                    wishListId: this.wishList.id,
                    productSetId: this.baseProductSetId,
                }).finally(() => {
                    this.postToggleWishListProductSet();
                });

                this.resetProductSetReplacements({
                    productSetId: this.baseProductSetId
                });
            },

            postToggleWishListProductSet() {
                this.busy = false;
                EventBus.emit(EVENTS.PRODUCT_SET_DETAILS_CLOSED);
            },
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
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";
    @import "../../../scss/components/dialog";

    .product-set-save {
        &--actions {
            @include flexbox();

            .save-btn {
                width: 100%;
                @include flex(auto);

                &:focus {
                    &:before {
                        opacity: 0;
                    }
                }

                &.v-btn--disabled{
                    color: #fcac22 !important;
                    caret-color: #fcac22 !important;
                    @include opacity(0.5);

                    .v-icon{
                        color: #fcac22!important;
                    }
                }

                &::v-deep {
                    &.active {
                        .heart-icon {
                            color: #fcac22;
                        }

                        .v-btn__content {
                            .heart-icon {
                                &:before {
                                    content: "\e918";
                                    color: #ff6227;
                                    text-shadow: -2px 0 #fcac27, 0 2px #fcac27, 2px 0 #fcac27, 0 -2px #fcac27;
                                }
                            }
                        }
                    }
                }
            }
        }

        .offer-to-use-decision-wrap {
            width: 100%;
        }
    }

    .offer-to-use-decision-dialog {
        .v-card {
            &__title {
                margin: 10px 0 15px;
            }

            &__text {
                text-align: center;

                .actions {
                    margin: 20px 0 0 0;

                    .v-btn {
                        + .v-btn {
                            margin-top: 15px;
                        }
                    }
                }
            }
        }
    }

    .product-set-decision-tree-dialog {
        .v-card {
            box-shadow: none !important;

            &__text {
                overflow: visible;
            }
        }
    }
</style>
