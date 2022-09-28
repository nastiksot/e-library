<template>
    <div class="wish-list">
        <div v-for="productSet in wishList.productSets">
            <product-set-item :item="productSet" :key="productSet.id" />
        </div>
    </div>
</template>

<script>
    import WishListStore from "./store";
    import {mapState} from "vuex";
    import ProductSetItem from "../product-set/ProductSetItem";

    export default {
        name: "WishListProductSetList",

        props: {},

        components: {ProductSetItem},

        data() {
            return {};
        },

        computed: {
            ...mapState("wishList", ["wishList"]),
        },

        mounted() {
        },

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        }
    };
</script>

<style scoped lang="scss">

</style>
