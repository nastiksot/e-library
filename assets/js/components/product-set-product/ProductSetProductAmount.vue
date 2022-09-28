<template>
    <div class="cart-item-amount">
        <a @click.prevent="onClickMinus" href="#" class="cart-item-amount__btn">
            <v-icon>minus-icon</v-icon>
        </a>
        <input
            v-if="!isBusy"
            type="text"
            class="cart-item-amount__input"
            v-model="quantity"
        >
        <div class="cart-item-amount__input" v-if="isBusy">
            <v-progress-circular indeterminate color="orange" :size="24"/>
        </div>
        <a @click.prevent="onClickPlus" href="#" class="cart-item-amount__btn">
            <v-icon>plus-icon</v-icon>
        </a>
    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import WishListStore from "./../wish-list/store";
    import ProductSetStore from "./../product-set/store";
    import ProductSetProduct from "../../models/product-set-product";
    import WishListProductSetProduct from "../../models/wish-list-product-set-product";
    import _debounce from "lodash/debounce";

    export default {
        name: "ProductSetProductAmount",

        props: {
            wishListId: {
                type: Number,
                required: false,
                default: null
            },

            wishListProductSetId: {
                type: Number,
                required: false,
                default: null
            },

            item: {
                type: WishListProductSetProduct | ProductSetProduct,
                required: true
            },
        },

        components: {},

        data() {
            return {
                isBusy: false,
            };
        },

        computed: {
            ...mapState("global", ["me",]),

            isInWishList() {
                return this.item instanceof WishListProductSetProduct;
            },

            quantity: {
                get: function () {
                    return this.item.currentQuantity ?? 0;
                },

                set: function (newValue) {
                    this.item.currentQuantity = newValue
                }
            },
        },

        watch: {
            quantity: _debounce(function (val) {
                if (this.isInWishList) {
                    if (val < 0) {
                        val = 0;
                    }

                    this.saveQuantity(val);
                }
            }, 500, {trailing: true}),
        },

        methods: {
            ...mapActions("wishList", ["updateProductSetProductQuantity", "loadWishList"]),

            saveQuantity(quantity) {
                if (this.isInWishList) {
                    this.isBusy = true;
                    this.updateProductSetProductQuantity({
                        uid: this.me.uid,
                        wishListId: this.wishListId,
                        wishListProductSetId: this.wishListProductSetId,
                        wishListProductSetProductId: this.item.id,
                        quantity: quantity,
                    }).then(() => {
                        this.loadWishList({uid: this.me.uid}).then(() => {
                            this.isBusy = false;
                        })
                    });
                }
            },

            onClickPlus() {
                if (this.isBusy) {
                    return;
                }

                this.quantity++;
            },

            onClickMinus() {
                if (this.isBusy) {
                    return;
                }

                if (this.quantity) {
                    this.quantity--;
                }
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
    }
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .cart-item-amount {
        border-radius: 5px;
        border: 1px solid #cacfd5;
        @include flexbox();
        @include align-items(center);
        margin-right: 16px;
        overflow: hidden;

        &__btn {
            width: 27px;
            height: 30px;
            text-align: center;
            @include flexbox();
            @include align-items(center);
            @include justify-content(center);
            @include transition(background 0.2s ease-in-out);

            .v-icon {
                color: #485c74;
                font-size: 16px;
                @include transition(color 0.2s ease-in-out);
            }

            &:hover {
                text-decoration: none;
                background: #f3f3f3;

                .v-icon {
                    color: #000;
                }
            }
        }

        &__input {
            width: 40px;
            height: 30px;
            text-align: center;
            border-left: 1px solid #cacfd5;
            border-right: 1px solid #cacfd5;
            outline: none;
            color: #485c74;
            font-size: 14px;
        }
    }
</style>
