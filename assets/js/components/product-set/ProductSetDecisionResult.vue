<template>
    <div class="product-set-decision-result">

        <v-expansion-panels
            v-model="panel"
            multiple
        >
            <v-expansion-panel>
                <v-expansion-panel-header>
                    <div class="product-set-decision-result__header">
                        <translated-text code="PRODUCT_SET.DECISION_RESULT.TITLE"/>
                    </div>
                </v-expansion-panel-header>

                <v-expansion-panel-content>

                    <template v-if="deletedProduct">
                        <h3 class="product-set-decision-result--title">
                            <translated-text code="PRODUCT_SET.DECISION_RESULT.DELETED"/>
                        </h3>
                        <div class="product-set-decision-result--items">
                            <product-set-product
                                hide-quantity
                                hide-tip
                                hide-alternative-products
                                :item="deletedProduct"
                            />
                        </div>
                    </template>

                    <template v-if="replacedProducts.length > 0 && replacedProduct">
                        <h3 class="product-set-decision-result--title">
                            <translated-text code="PRODUCT_SET.DECISION_RESULT.REPLACED"/>
                        </h3>

                        <div class="product-set-decision-result--items">
                            <template v-for="product in replacedProducts">
                                <product-set-product
                                    hide-quantity
                                    hide-tip
                                    hide-alternative-products
                                    :item="product"
                                />
                            </template>

                            <div class="before-customization">
                                <h3 class="product-set-decision-result--subtitle">
                                    <translated-text code="PRODUCT_SET.DECISION_RESULT.REPLACED_BEFORE"/>
                                </h3>

                                <product-set-product
                                    hide-quantity
                                    hide-tip
                                    hide-alternative-products
                                    :item="replacedProduct"
                                />
                            </div>
                        </div>
                    </template>

                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>

    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import ProductSet from '../../models/product-set';
    import ProductSetStore from "../product-set/store";
    import ProductSetProduct from '../product-set-product/ProductSetProduct';
    import TranslatedText from '../translated-text/TranslatedText';
    import Decision from "../../models/decision";
    import {DECISION_ACTION} from "../../dictionary/decision-action";

    export default {
        name: "ProductSetDecisionResult",

        props: {
            decision: {
                type: Decision,
                required: true
            },

            productSet: {
                type: ProductSet,
                required: true
            },
        },

        components: {ProductSetProduct, TranslatedText},

        data() {
            return {
                // open the first panel
                panel: [0],
            };
        },

        computed: {
            ...mapGetters("productSet", [
                "getProductSetProductByProductSetIdAndProductId",
                "createProductSetProductsFromProducts",
            ]),

            deletedProduct(): ?ProductSetProduct {
                if (DECISION_ACTION.DELETE_MAIN === this.decision.action) {
                    return this.getProductSetProductByProductSetIdAndProductId(this.productSet.id, this.productSet.decisionProductId);
                }

                return null;
            },

            replacedProduct(): ?ProductSetProduct {
                if (DECISION_ACTION.REPLACE_MAIN === this.decision.action) {
                    return this.getProductSetProductByProductSetIdAndProductId(this.productSet.id, this.productSet.decisionProductId);
                }

                return null;
            },

            replacedProducts(): Array<ProductSetProduct> {
                if (DECISION_ACTION.REPLACE_MAIN === this.decision.action) {
                    return this.createProductSetProductsFromProducts(this.decision.replacedProducts);
                }

                return [];
            }

        },

        watch: {},

        methods: {},

        beforeCreate() {
            if (!this.$store.hasModule("productSet")) {
                this.$store.registerModule("productSet", ProductSetStore);
            }
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .product-set-decision-result {
        .v-expansion-panel {
            padding-bottom: 0;
        }

        .product-set-decision-result__header {
            @include normal-font(18px, 32px);
            color: #3C4F64;
            font-family: $fontSomfySansMedium;
        }

        &::v-deep {
            .cart-item {
                border: none;
                padding: 0;
                margin-bottom: 16px;

                & + .cart-item {
                    margin-top: 0;
                }

                &-info__btn,
                &-info__close {
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
                    &-info__close {
                        top: 5px;
                        right: 5px;
                    }

                    &-info__content {
                        background: #F3F5F8;
                    }
                }
            }
        }

        &--title {
            @include normal-font(16px, 19px);
            color: #3C4F64;
            margin-bottom: 16px;
            font-family: $fontSomfySansMedium;
        }

        &--items {
            border: 1px solid #D5DADF;
            border-radius: 5px;
            padding: 15px;
        }

        &--subtitle {
            @include normal-font(16px, 19px);
            color: #000;
            margin-bottom: 10px;
            font-family: $fontSomfySansLight;
        }
    }
</style>
