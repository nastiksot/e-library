<template>
    <div class="product-set-filter-button">
        <v-btn depressed color="orange" @click="$emit('click')">
            <v-icon :class="filterIconClass">chevron-down-icon</v-icon>
            <span class="v-btn__text">
                <translated-text :code="filterTitle"></translated-text>
                <span v-if="filtersApplied.length">({{ filtersApplied.length }})</span>
            </span>
        </v-btn>
    </div>
</template>

<script>
    import TranslatedText from "../translated-text/TranslatedText";
    import {mapGetters} from "vuex";
    import ProductSetFilterStore from "./store";

    export default {
        name: "ProductSetFilterButton",

        props: {
            isShowFilters: {
                type: Boolean,
                required: true,
            }
        },

        components: {TranslatedText},

        data() {
            return {};
        },

        computed: {
            ...mapGetters("productSetFilter", ["filtersApplied"]),

            filterIconClass() {
                return this.isShowFilters ? 'close' : 'open';
            },

            filterTitle() {
                return 'SIDEBAR.FILTERS.' + (this.isShowFilters ? 'CLOSE' : 'OPEN');
            },
        },

        watch: {},

        methods: {},

        beforeCreate() {
            if (!this.$store.hasModule("productSetFilter")) {
                this.$store.registerModule("productSetFilter", ProductSetFilterStore);
            }
        }
    };
</script>

<style scoped lang="scss">
.product-set-filter-button {
    .v-btn {
        .v-icon {
            font-size: 8px;
            margin-top: 2px;

            &.close {
                transform: rotateX(180deg);
            }
        }
    }
}
</style>
