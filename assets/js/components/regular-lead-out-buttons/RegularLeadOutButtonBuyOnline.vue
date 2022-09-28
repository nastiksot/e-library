<template>
    <v-dialog
        v-model="dialog.buyOnline"
        width="420"
        overlay-color="#000"
        overlay-opacity="0.65"
        content-class="regular-lead-out-buttons-dialog"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-btn class="buy-now__btn--open"
                   depressed
                   large
                   color="orange"
                   v-bind="attrs"
                   v-on="on">
                <v-icon>cart-icon</v-icon>
                <translated-text code="LEAD_OUT.BUY_ONLINE.BUTTON.EXTERNAL"/>
            </v-btn>
        </template>

        <v-card>
            <v-card-title>
                <translated-text code="LEAD_OUT.BUY_ONLINE.TITLE"/>

                <v-btn icon small class="v-dialog__close" @click="dialog.buyOnline = false">
                    <v-icon>close-icon</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text>
                <div class="regular-lead-out-buttons-dialog__item">
                    <template v-if="productsQty.retailOnly > 0">
                        <div class="regular-lead-out-buttons-dialog__item--text">
                            <translated-text code="LEAD_OUT.FIND_DEALER.TEXT.PREFIX"/>
                            {{ productsQty.retailOnly }}
                            <translated-text
                                :code="'LEAD_OUT.FIND_DEALER.TEXT.POSTFIX_' + (productsQty.retailOnly > 1 ? 'PLURAL': 'SINGULAR')"/>
                        </div>

                        <div v-for="productSet in wishList.productSets" class="product-set-item">
                            <product-set-product-list
                                show-retail-only
                                hide-quantity
                                :wish-list-id="wishList.id"
                                :item="productSet"
                                :key="productSet.id"
                                :hide-duplicates="true"
                                is-wish-list-page
                            />
                        </div>
                    </template>

                    <div v-if="isEmptyNotRetailOnly" class="empty">
                        <translated-text code="LEAD_OUT.NO_DATA"/>
                    </div>

                    <v-btn @click.prevent="clickBuySomfy" depressed large block class="mt-3" color="orange">
                        <v-icon>cart-icon</v-icon>
                        <translated-text code="LEAD_OUT.BUY_ONLINE.BUTTON.INTERNAL"/>
                    </v-btn>

                    <translated-text code="LEAD_OUT.OR" class="or"/>

                    <v-btn @click.prevent="openFindDealerDialog" depressed large block color="orange" class="btn__location">
                        <v-icon>location-icon</v-icon>
                        <translated-text code="LEAD_OUT.FIND_DEALER.BUTTON.EXTERNAL"/>
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import {mapState} from "vuex";
import ProductSetProductList from '../product-set-product/ProductSetProductList';
import WishListStore from "../wish-list/store";
import TranslatedText from "../translated-text/TranslatedText";

export default {
    name: "RegularLeadOutButtonBuyOnline",

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
    },

    data() {
        return {
            dialog: this.inputDialog,
        };
    },

    computed: {
        ...mapState("global", ["me"]),
        ...mapState("wishList", ["wishList"]),

        isEmptyNotRetailOnly() {
            return this.productsQty.notRetailOnly === 0;
        },

        clickBuySomfyColor() {
            return this.isEmptyNotRetailOnly ? 'grey' : 'orange';
        }
    },

    methods: {
        clickBuySomfy() {
            this.$emit("go-to-somfy-online-shop");
        },

        openFindDealerDialog() {
            this.dialog.buyOnline  = false;
            this.dialog.findDealer = true;
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
body .v-btn.orange.btn__location {
    background: transparent!important;
    color: #FCAC22;
    border: 1px solid #FCAC22;

    .v-icon {
        font-weight: bold;
    }
}
</style>
