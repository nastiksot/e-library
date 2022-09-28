<template>
    <div class="product-set-filter-list" v-if="show">
        <template v-for="(filterGroup, index) in filterGroups">
            <product-set-filter-item
                :filter-group="filterGroup"
                :is-first-filter-group="index === 0"
                :selected="selectedFilters[filterGroup.id]"
                @all="onResetFilters(filterGroup.id)"
                @toggle="onToggle(filterGroup.id, $event)" />
        </template>
    </div>
</template>

<script>
    import ProductSetFilterItem from "./ProductSetFilterItem";
    import ProductSetFilterStore from "./store";
    import {mapActions, mapState} from "vuex";

    export default {
        name: "ProductSetFilterList",

        props: {
            show: {
                type: Boolean,
                required: true
            }
        },

        components: {ProductSetFilterItem},

        data() {
            return {};
        },

        computed: {
            ...mapState("productSetFilter", ["filterGroups", "selectedFilters"]),
        },

        watch: {},

        methods: {
            ...mapActions("productSetFilter", ["toggleFilter", "resetFilters"]),

            /**
             * @param {Number|null} filterGroupId
             * @param {Number} filterId
             */
            onToggle(filterGroupId: Number, filterId: Number) {
                this.toggleFilter({filterGroupId: filterGroupId, filterId: filterId});
            },

            /**
             * @param {Number} filterGroupId
             */
            onResetFilters(filterGroupId: Number) {
                this.resetFilters(filterGroupId);
            }
        },

        beforeCreate() {
            if (!this.$store.hasModule("productSetFilter")) {
                this.$store.registerModule("productSetFilter", ProductSetFilterStore);
            }
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .product-set-filter-list {
        margin-bottom: 20px;

        .product-set-filter-item {
            + .product-set-filter-item {
                margin-top: 23px;
            }
        }
    }
</style>
