<template>
    <div class="product-set-decision-tree">
        <v-dialog
            v-model="dialog"
            width="550"
            scrollable
            overlay-color="#000"
            overlay-opacity="0.65"
            content-class="product-set-decision-tree-dialog"
            :persistent="isCallFromAddToWishList"
        >
            <template v-if="!isCallFromAddToWishList" v-slot:activator="{ on, attrs }">
                <v-btn
                    v-bind="attrs"
                    v-on="on"
                    :outlined="hasDecision"
                    color="orange"
                    block
                    class="product-set-decision-tree__btn"
                    :elevation="0"
                >
                    <translated-text v-if="hasDecision" code="USECASE.RESET_REPLACEMENTS"/>
                    <translated-text v-else code="USECASE.ADJUST_PRODUCTS"/>

                    <v-badge
                        v-if="hasDecision"
                        inline
                        color="success"
                        icon="check-icon"
                    />
                </v-btn>
            </template>

            <v-card :class="{'from-add-to-wish-list': isCallFromAddToWishList}">
                <v-card-title>
                    {{ $tc('DECISION_TREE.TITLE') }}
                    <v-btn icon small class="v-dialog__close" @click="close"
                           :disabled="isActiveRequestToAddProductSetToWishList">
                        <v-icon>close-icon</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text>

                    <decision-tree :decision-id="productSet.decisionId"
                                   :hide-reset-button="isCallFromAddToWishList"
                                   :is-done="hasDecision"
                                   :is-active-request-to-add-product-set-to-wish-list="isActiveRequestToAddProductSetToWishList"
                                   v-on:close="close"
                                   v-on:reset="reset"
                                   v-on:decision="onChooseDecision"
                                   v-on:final="final">

                        <template v-slot:done-content v-if="showDecisionResultDoneContent">
                            <product-set-decision-result :decision="finalDecision" :product-set="productSet"/>
                        </template>

                        <template v-slot:form-footer-content v-if="showDecisionResultFooterContent">
                            <product-set-decision-result :decision="finalDecision" :product-set="productSet"/>
                        </template>
                    </decision-tree>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import ProductSetStore from "../product-set/store";
    import TranslatedText from "../translated-text/TranslatedText";
    import DecisionTree from "../decision-tree/DecisionTree";
    import DecisionTreeStore from "../decision-tree/store";
    import {mapActions, mapGetters, mapState} from "vuex";
    import Decision from "../../models/decision";
    import {DECISION_ACTION} from "../../dictionary/decision-action";
    import ProductSetDecisionResult from "./ProductSetDecisionResult";
    import ProductSet from "../../models/product-set";

    export default {
        name: "ProductSetDecisionTree",

        props: {
            productSet: {
                type: ProductSet,
                required: true,
                validator: (productSet: ProductSet) => {
                    return productSet.isAbleUseDecision();
                },
            },
            isCallFromAddToWishList: {
                type: Boolean | null,
                required: false,
                default: false
            },
            isActiveRequestToAddProductSetToWishList: {
                type: Boolean | null,
                required: false,
                default: false
            },
            baseDialog: {
                type: Boolean | null,
                required: false,
                default: null
            }
        },

        components: {ProductSetDecisionResult, DecisionTree, TranslatedText},

        data() {
            return {
                dialog: false,
                /** @type Decision */
                finalDecision: null,
            };
        },

        computed: {
            ...mapGetters("productSet", [
                "isProductSetUsesDecision",
                "getProductSetProductByProductSetIdAndProductId",
            ]),

            hasDecision() {
                return this.isProductSetUsesDecision(this.productSet.id);
            },

            showDecisionResultDoneContent() {
                return  null !== this.finalDecision && this.hasDecision && this.productSet.hasReplacements;
            },

            showDecisionResultFooterContent() {
                if (null !== this.finalDecision) {
                    if (DECISION_ACTION.KEEP_MAIN !== this.finalDecision.action) {
                        return !(DECISION_ACTION.REPLACE_MAIN === this.finalDecision.action &&
                            this.finalDecision.replacedProducts.length === 0);
                    }
                }

                return false;
            },
        },

        watch: {},

        methods: {
            ...mapActions("productSet", [
                "addDecisionProductSetId",
                "setProductSetDeleteProductId",
                "setProductSetReplacedProducts",
                "resetProductSetReplacements",
            ]),

            close() {
                this.dialog = false;

                if (this.isCallFromAddToWishList) {
                    if (this.hasDecision) {
                        // user applied Decision to products set before => just display message that Decision saved successfully
                        this.$emit('display-msg-about-decision-saved-successfully');
                    } else {
                        // user doesn't want to apply Decision to products set => just close current popup
                        this.$emit('ignore-decision', null, true);
                    }
                }
            },

            reset() {
                this.finalDecision = null;

                // reset productSet replacements
                this.resetProductSetReplacements({productSetId: this.productSet.id});
                this.$emit('updateProductSet', this.productSet.id);

                if (this.isCallFromAddToWishList) {
                    this.$emit('ignore-decision', this.productSet.id, true);
                }
            },

            onChooseDecision(decision: Decision) {
                let finalDecision = null;
                if (decision.final &&
                    decision.positive
                ) {
                    finalDecision = decision;
                }

                this.finalDecision = finalDecision;
            },

            final(decision: Decision) {

                switch (decision.action) {

                    case DECISION_ACTION.REPLACE_MAIN:
                        this.setProductSetReplacedProducts({
                            productSetId: this.productSet.id,
                            productId: this.productSet.decisionProductId,
                            replacedProducts: decision.replacedProducts
                        });
                        this.$emit('updateProductSet', this.productSet.id);
                        break;

                    case DECISION_ACTION.DELETE_MAIN:
                        this.setProductSetDeleteProductId({
                            productSetId: this.productSet.id,
                            productId: this.productSet.decisionProductId
                        });
                        this.$emit('updateProductSet', this.productSet.id);
                        break;

                    case DECISION_ACTION.KEEP_MAIN:
                        this.addDecisionProductSetId(this.productSet.id);
                        break;
                }

                if (this.isCallFromAddToWishList) {
                    this.$emit('save-decision', this.productSet.id, false);
                }
            }
        },

        beforeCreate() {
            if (!this.$store.hasModule("productSet")) {
                this.$store.registerModule("productSet", ProductSetStore);
            }

            if (!this.$store.hasModule("decisionTree")) {
                this.$store.registerModule("decisionTree", DecisionTreeStore);
            }

        },

        created() {
            if (this.baseDialog !== null) {
                this.dialog = this.baseDialog;
            }
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";
    @import "../../../scss/components/dialog";

    .product-set-decision-tree-dialog {
        .v-card {
            box-shadow: none !important;

            &__text {
                overflow: visible;
            }

            &.from-add-to-wish-list {
                &::v-deep {
                    .theme--light.v-btn.v-btn--disabled {
                        color: rgba(0, 0, 0, 0.26) !important;
                    }
                }
            }
        }
    }

    .product-set-decision-tree {
        &__btn {
            margin-bottom: 15px;

            &:focus {
                &:before {
                    opacity: 0;
                }
            }

            &::v-deep {
                .v-badge__badge {
                    width: 18px;
                    height: 18px;
                    padding: 0;
                    min-width: 0;
                    background: #9cbf59 !important;
                    @include flexbox();
                    @include align-items(center);
                    @include justify-content(center);

                    .v-icon {
                        margin: 0;
                    }
                }
            }
        }
    }
</style>
