<template>
    <div class="cart-total">
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

        <div class="cart-total--actions">
            <dealer-request v-if="Object.keys(dealer).length > 0"/>
            <regular-lead-out-buttons v-else/>
        </div>
    </div>
</template>

<script>
    import WishListStore from "./../wish-list/store";
    import {mapGetters, mapState} from "vuex";
    import TranslatedText from "../translated-text/TranslatedText";
    import RegularLeadOutButtons from "../regular-lead-out-buttons/RegularLeadOutButtons";
    import DealerRequest from "../dealer-request/DealerRequest";

    export default {
        name: "CartTotal",

        props: {},

        components: {DealerRequest, RegularLeadOutButtons, TranslatedText,},

        data() {
            return {
                dialog: false
            };
        },

        computed: {
            ...mapState("global", ["dealer"]),
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

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";
    @import "../../../scss/components/dialog";

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

                span{
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
        }
    }
</style>
