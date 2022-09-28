<template>
    <div class="product-set-remove">
        <v-btn icon small @click="onClick()" v-if="!busy">
            <v-icon>heart-f-icon</v-icon>
        </v-btn>

        <v-progress-circular indeterminate color="orange" :size="24" v-if="busy"></v-progress-circular>
    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import WishListStore from "../wish-list/store";
    import ProductSetStore from "./store";

    export default {
        name: "ProductSetRemove",

        props: {
            productSetId: {
                type: Number,
                required: true
            }
        },

        components: {},

        data() {
            return {
                busy: false
            };
        },

        computed: {
            ...mapState("global", ["me",]),
            ...mapState("wishList", ["wishList"]),
        },

        watch: {},

        methods: {
            ...mapActions("wishList", ["removeProductSetFromWishList"]),
            ...mapActions("productSet", [
                "resetProductSetReplacements",
            ]),

            onClick() {
                this.busy = true;

                this.removeProductSetFromWishList({
                    uid: this.me.uid,
                    wishListId: this.wishList.id,
                    productSetId: this.productSetId
                });
                this.resetProductSetReplacements({
                    productSetId: this.productSetId
                });

                this.$emit('click');
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
            if (!this.$store.hasModule("productSet")) {
                this.$store.registerModule("productSet", ProductSetStore);
            }
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .product-set-remove {
        position: absolute;
        z-index: 3;
        top: 5px;
        right: 5px;

        .v-btn {
            .v-icon {
                font-size: 19px;
                color: #ff6227;
            }
        }
    }
</style>
