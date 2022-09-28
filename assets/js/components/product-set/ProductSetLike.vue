<template>
    <div class="product-set-like">
        <v-btn icon small :class="{active: active}" @click="onClick()" v-if="!busy">
            <v-icon>heart-icon</v-icon>
        </v-btn>

        <v-progress-circular indeterminate color="orange" v-if="busy" :size="24"></v-progress-circular>
    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import WishListStore from "../wish-list/store";
    import ProductSetStore from "../product-set/store";
    import {EventBus, events as EVENTS} from "../../modules/EventBus";
    import WishListProductSet from "../../models/wish-list-product-set";
    import ProductSet from "../../models/product-set";

    export default {
        name: "ProductSetLike",

        props: {
            active: {
                type: Boolean,
                required: false,
                default: false
            },

            productSet: {
                type: ProductSet | WishListProductSet,
                required: true
            },

            isDayWithSomfyPage: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        components: {},

        data() {
            return {
                busy: false,
            };
        },

        computed: {
            ...mapState("global", ["me",]),
            ...mapState("wishList", ["wishList"]),

            productSetId() {
                return this.productSet instanceof WishListProductSet ? this.productSet.originalId : this.productSet.id;
            },
        },

        watch: {
            active() {
                this.busy = false;
            }
        },

        methods: {
            ...mapActions("wishList", [
                "addProductSetToWishList",
                "removeProductSetFromWishList"
            ]),
            ...mapActions("productSet", [
                "resetProductSetReplacements",
            ]),

            onClick() {
                this.busy = true;

                if (this.active) {
                    this.removeProductSetFromWishList({
                        uid: this.me.uid,
                        wishListId: this.wishList.id,
                        productSetId: this.productSetId
                    }).finally(() => {
                        this.closeProductSetDetails();
                    });
                    this.resetProductSetReplacements({
                        productSetId: this.productSetId
                    });
                } else {
                    this.addProductSetToWishList({
                        uid: this.me.uid,
                        wishListId: this.wishList.id,
                        productSetId: this.productSetId
                    }).finally(() => {
                        this.busy = false;
                        this.closeProductSetDetails();

                        this.tcEventClickOnAddToWishList(this.productSet.title);
                    });
                }
            },

            tcEventClickOnAddToWishList(productSetTitle) {
                if (this.isDayWithSomfyPage) {
                    // the function is for Planner page only
                    return;
                }

                if (typeof (tc_events_global) === 'undefined') {
                    return;
                }

                try {
                    tc_events_global(this, 'smartConfiguration_addto_wishlist', {
                        'evt_category': 'SmartHome_planner',
                        'evt_button_action': 'addto_wishlist',
                        'evt_button_label': productSetTitle
                    });
                } catch (error) {
                    console.error(error);
                }
            },

            closeProductSetDetails() {
                EventBus.emit(EVENTS.PRODUCT_SET_DETAILS_CLOSED);
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
            if (!this.$store.hasModule("productSetStore")) {
                this.$store.registerModule("productSetStore", ProductSetStore);
            }
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .product-set-like {
        position: absolute;
        z-index: 3;
        top: 5px;
        right: 5px;

        .v-btn {
            .v-icon {
                font-size: 19px;
                color: #fff;
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
</style>
