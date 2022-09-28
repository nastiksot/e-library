<template>
    <div class="product-set-total">
<!--        <div class="product-set-total&#45;&#45;info">-->
<!--            <div class="product-set-total&#45;&#45;info-details">-->
<!--                <div class="product-set-total&#45;&#45;info-price-title">-->
<!--                    Preis (UVP) gesamt:-->
<!--                </div>-->
<!--                <div class="product-set-total&#45;&#45;info-text">-->
<!--                    <translated-text code="PRICE.NOTICE.INSTALL"/>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="product-set-total&#45;&#45;info-price">-->
<!--                <template v-if="productSet.isRangePrice">-->
<!--                    <div v-html="priceRangeValue"/>-->
<!--                    <span v-html="priceRangeNotice"/>-->
<!--                </template>-->
<!--                <template v-else>-->
<!--                   ab {{ productSet.totalPrice.toLocaleString() }} €-->
<!--                </template>-->
<!--            </div>-->
<!--        </div>-->

<!--        <div class="product-set-total&#45;&#45;actions">-->
<!--            <v-dialog-->
<!--                v-model="dialog"-->
<!--                width="320"-->
<!--                overlay-color="#000"-->
<!--                overlay-opacity="0.65"-->
<!--                content-class="buy-dialog"-->
<!--            >-->
<!--                <template v-slot:activator="{ on, attrs }">-->
<!--                    <v-btn class="add-to-cart" depressed large color="orange" v-bind="attrs" v-on="on">-->
<!--                        <v-icon>cart-icon</v-icon>-->
<!--                        Jetzt kaufen-->
<!--                    </v-btn>-->
<!--                </template>-->

<!--                <v-card>-->
<!--                    <v-card-title>-->
<!--                        Jetzt kaufen-->

<!--                        <v-btn icon small class="v-dialog__close" @click="dialog = false">-->
<!--                            <v-icon>close-icon</v-icon>-->
<!--                        </v-btn>-->
<!--                    </v-card-title>-->

<!--                    <v-card-text>-->
<!--                        <div class="buy-dialog__item">-->
<!--                            <h3 class="buy-dialog__item&#45;&#45;title">-->
<!--                                Im Somfy Onlineshop kaufen-->
<!--                            </h3>-->
<!--                            <div class="buy-dialog__item&#45;&#45;text">-->
<!--                                Es sind 5 von 7 Artikeln online erhältlich-->
<!--                            </div>-->
<!--                            <v-btn depressed large block color="orange">-->
<!--                                <v-icon>cart-icon</v-icon>-->
<!--                                Zum Somfy Onlineshop-->
<!--                            </v-btn>-->
<!--                        </div>-->
<!--                        <div class="buy-dialog__item">-->
<!--                            <h3 class="buy-dialog__item&#45;&#45;title">-->
<!--                                Beim Fachhändler vor Ort kaufen-->
<!--                            </h3>-->
<!--                            <div class="buy-dialog__item&#45;&#45;text">-->
<!--                                Fordern Sie jetzt kostenlos ein Angebot-->
<!--                                für alle Artikel bei Ihrem Fachhändler-->
<!--                                vor Ort an-->
<!--                            </div>-->
<!--                            <v-btn depressed large block color="orange">-->
<!--                                <v-icon>location-icon</v-icon>-->
<!--                                Fachhändler finden-->
<!--                            </v-btn>-->
<!--                        </div>-->
<!--                    </v-card-text>-->
<!--                </v-card>-->
<!--            </v-dialog>-->

<!--            <share/>-->
<!--        </div>-->
    </div>

</template>

<script>
    import ProductSet from "../../models/product-set";
    import WishListProductSet from "../../models/wish-list-product-set";
    import Share from "../share/Share";
    import TranslatedText from "../translated-text/TranslatedText";
    import {priceFormat} from "../../helpers/priceHelper";

    export default {
        name: "ProductSetTotal",

        props: {
            productSet: {
                type: ProductSet | WishListProductSet,
                required: true
            }
        },

        components: {TranslatedText, Share},

        data() {
            return {
                dialog: false
            };
        },

        computed: {

            priceRangeParams() {
                return {
                    price: priceFormat(this.productSet.totalPrice),
                    price_end: priceFormat(this.productSet.totalPriceEnd)
                };
            },

            priceRangeValue() {
                return this.productSet.isRangePrice
                    ? this.$t('PRICE.RANGE.VALUE', this.priceRangeParams)
                    : null;
            },

            priceRangeNotice() {
                return this.$tc('PRICE.RANGE.NOTICE');
            },

        },

        watch: {},

        methods: {},
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";
    @import "../../../scss/components/dialog";

    .product-set-total {
        padding: 20px 0 0;

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
                    @extend .product-set-total--info-price;
                }
            }

            &-text {
                @include normal-font(12px, 14px);
                color: #343a40;
                max-width: 150px;
            }
        }
    }
</style>
