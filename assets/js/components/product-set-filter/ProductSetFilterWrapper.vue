<template>
    <div class="product-filter-wrapper">
        <div class="product-filter">
            <div class="product-filter__header">
                <h2 class="product-filter__title">
                    <translated-text code="SIDEBAR.SAMPLE_CONFIGURATIONS" width="150px"></translated-text>
                </h2>
                <product-set-filter-button :is-show-filters="isShowFilters" @click="toggleShowFilter()"/>
            </div>

            <product-set-filter-list :show="isShowFilters"/>
        </div>

        <v-row class="product-filter-wrapper__legend" v-if="isShowFilters && filterGroups.length">
            <v-col><strong>{{ $t("FILTER.RESULTS") }} ({{ productSets.length }})</strong></v-col>
            <v-col align="right"><a v-if="filtersApplied.length" href="javascript:void(0);" class="product-filter-wrapper__reset" @click="resetFilters(null);">{{ $t("FILTER.RESET") }}</a></v-col>
        </v-row>
    </div>
</template>

<script>
import ProductSetFilterButton from "../product-set-filter/ProductSetFilterButton";
import ProductSetFilterList from "../product-set-filter/ProductSetFilterList";
import TranslatedText from "../translated-text/TranslatedText";
import ProductSetFilterStore from "./store";
import ProductSetStore from "../product-set/store";
import {mapActions, mapGetters, mapState} from "vuex";

export default {
    name: "ProductSetFilterWrapper",

    props: {},

    components: {ProductSetFilterButton, ProductSetFilterList, TranslatedText,},

    data() {
        return {
            isShowFilters: true,
        };
    },

    computed: {
        ...mapState("productSetFilter", ["filterGroups", "selectedFilters"]),
        ...mapState("productSet", ["productSets"]),
        ...mapGetters("productSetFilter", ["filtersApplied"]),
    },

    watch: {},

    methods: {
        ...mapActions("productSetFilter", ["loadFilters", "resetFilters"]),

        toggleShowFilter() {
            this.isShowFilters = !this.isShowFilters;
            this.setIsShowFiltersCookie(this.isShowFilters);
        },

        setIsShowFiltersCookie(isShowFilters) {
            this.$cookies.set('isShowFilters', isShowFilters, '1m');  // 1 month after, expire
        },

        initIsShowFilters() {
            if (!this.$cookies.keys().includes('isShowFilters')) {
                // the cookie not exists yet
                this.setIsShowFiltersCookie(true);
            }

            this.isShowFilters = this.$cookies.get('isShowFilters') === 'true';
        },
    },

    mounted() {
        this.loadFilters();
    },

    beforeCreate() {
        if (!this.$store.hasModule("productSet")) {
            this.$store.registerModule("productSet", ProductSetStore);
        }

        if (!this.$store.hasModule("productSetFilter")) {
            this.$store.registerModule("productSetFilter", ProductSetFilterStore);
        }
    },

    created() {
        this.initIsShowFilters();
    },
};
</script>

<style scoped lang="scss">
@import "../../../scss/base";

.product-filter-wrapper {
    .product-filter {
        &__header {
            @include flexbox();
            @include align-items(center);
            @include justify-content(space-between);
            margin-bottom: 25px;
        }

        &__title {
            @include normal-font(24px, 32px);
            font-family: $fontSomfySansLight;
            margin-bottom: 0;
        }
    }

    &__legend {
        padding-top: 15px;
        margin-bottom: 8px;
    }

    &__reset {
        color: #8996a4;
        text-decoration: underline;

        &:hover {
            text-decoration: none;
        }
    }
}
</style>
