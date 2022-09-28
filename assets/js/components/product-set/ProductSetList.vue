<template>
    <div class="product-set-list">
        <product-set-filter-wrapper/>

        <div class="product-set-list__list">
            <template v-for="productSet in productSetsList">
                <product-set-item :item="productSet" :key="productSet.id"/>
            </template>
        </div>
    </div>
</template>

<script>
    import ProductSetItem from "./ProductSetItem";
    import ProductSetFilterWrapper from "../product-set-filter/ProductSetFilterWrapper";
    import TranslatedText from "../translated-text/TranslatedText";
    import ProductSetStore from "./store";
    import ProductSetFilterStore from "./../product-set-filter/store";
    import WishListStore from "./../wish-list/store";
    import {mapActions, mapGetters, mapState} from "vuex";
    import _forEach from "lodash/forEach";
    import ProductSet from "../../models/product-set";

    export default {
        name: "ProductSetList",

        props: {},

        components: {TranslatedText, ProductSetFilterWrapper, ProductSetItem},

        data() {
            return {};
        },

        computed: {
            ...mapState("global", ["me", 'isDealerMode',]),
            ...mapState("productSet", ["productSets"]),
            ...mapGetters("productSetFilter", ["filtersApplied"]),
            ...mapGetters("wishList", ["getProductSetByOriginalId"]),

            productSetsList() {
                let items = [];
                _forEach(this.productSets, (productSet: ProductSet) => {
                    let wishListProductSet = this.getProductSetByOriginalId(productSet.id);
                    if (wishListProductSet) {
                        items.push(wishListProductSet);
                    } else {
                        items.push(productSet);
                    }
                });

                return items;
            },

        },

        watch: {
            filtersApplied(val) {
                this.loadProductSets({appliedFilters: val, isDealerMode: this.isDealerMode});
            },

            me() {
                if (Object.keys(this.me).length !== 0) {
                    this.loadWishList({uid: this.me.uid})
                }
            }
        },

        methods: {
            ...mapActions("productSet", ["loadProductSets"]),
            ...mapActions("wishList", ["loadWishList"]),
        },

        beforeCreate() {
            if (!this.$store.hasModule("productSet")) {
                this.$store.registerModule("productSet", ProductSetStore);
            }

            if (!this.$store.hasModule("productSetFilter")) {
                this.$store.registerModule("productSetFilter", ProductSetFilterStore);
            }

            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .product-set-list {
        height: 100%;
        @include flexbox();
        @include flex-direction(column);

        &__list {
            overflow-x: hidden;
            overflow-y: auto;
        }
    }
</style>
