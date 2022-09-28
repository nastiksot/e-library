<template>
    <div class="slider">
        <template v-if="!ready">
            <v-form @submit.prevent="" class="progress mt-10 mb-10">
                <v-progress-circular indeterminate color="orange" class="spinner" size="70"/>
            </v-form>
        </template>

        <template v-else-if="slider !== null">
            <v-navigation-drawer
                v-if="showProductSetDetails"
                v-model="showProductSetDetails"
                absolute
                stateless
            >
                <product-set-details
                    v-if="moreDetailsProductSetId"
                    :product-set-id="moreDetailsProductSetId"
                    :key="productSetDetailsKey"
                    :wish-list-id="wishListId"
                    is-day-with-somfy-page
                />
            </v-navigation-drawer>

            <div class="">
                <slider-list
                    :slider="slider"
                    :step="step"
                    @productSetMoreInfo="productSetMoreInfo"
                />
            </div>
        </template>
    </div>




</template>

<script>
    import {mapActions, mapGetters, mapState} from "vuex";
    import SliderStore from "./store";
    import ProductSetStore from "../product-set/store";
    import RouteGenerator from "../../modules/RouteGenerator";
    import SliderList from "./SliderList";
    import ProductSetDetails from "../product-set-details/ProductSetDetails";
    import ProductSetDetailsFullInfo from "../../components/product-set-details/ProductSetDetailsFullInfo";
    import WishListStore from "../../components/wish-list/store";
    import {EventBus, events as EVENTS} from "../../modules/EventBus";

    export default {
        name: "Slider",

        props: {},

        components: {
            ProductSetDetails,
            ProductSetDetailsFullInfo,
            SliderList,
        },

        data() {
            return {
                count: 0,
                step: 0,
                showProductSetDetails: false
            };
        },

        computed: {
            ...mapState("slider", {
                ready: state => state.ready,
                slider: state => state.slider,
            }),

            ...mapState("productSet", {
                moreDetailsProductSetId: state => state.moreDetailsProductSetId,
            }),

            ...mapState("wishList", ["wishList"]),
            ...mapGetters("wishList", ["selectedProductSets"]),

            wishListId() {
                return this.moreDetailsProductSetId && this.selectedProductSets.indexOf(this.moreDetailsProductSetId) !== -1
                    ? this.wishList.id
                    : null;
            },

            productSetDetailsKey() {
                return this.wishListId + '-' + this.moreDetailsProductSetId;
            },

        },

        watch: {
            showProductSetDetails(val) {
                if (!val) {
                    this.resetProductSetMoreInfo();
                }
            },

            moreDetailsProductSetId(val) {
                if (!val) {
                    this.resetProductSetMoreInfo();
                }
            }
        },

        methods: {
            ...mapActions("slider", [
                "loadFirstSlider",
            ]),
            ...mapActions("productSet", [
                "loadProductSets",
                "setMoreDetailsProductSetId"
            ]),

            productSetMoreInfo(productSetId: Number) {
                this.setMoreDetailsProductSetId(productSetId);
                this.showProductSetDetails = true;
            },

            resetProductSetMoreInfo() {
                this.setMoreDetailsProductSetId(null);
                this.showProductSetDetails = false;
            },
        },

        mounted() {
            this.loadProductSets({appliedFilters: []});
            // this.$refs.fullpage.init()
        },

        beforeCreate() {
            if (!this.$store.hasModule("slider")) {
                this.$store.registerModule("slider", SliderStore);
            }

            if (!this.$store.hasModule("productSet")) {
                this.$store.registerModule("productSet", ProductSetStore);
            }

            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        },

        created() {
            this.loadFirstSlider();

            EventBus.on(EVENTS.PRODUCT_SET_DETAILS_CLOSED, () => {
                EventBus.emit(EVENTS.SLIDER_STATE_IS_ACTIVE);
            });
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";
    .slider {
        aside {
            width: 100% !important;
            position: fixed;
            z-index: 20;
            background: none;

            aside {
                background: #fff;
            }
        }
    }

    .progress {
        min-height: 150px;

        &:after {
            display: none;
        }
    }

    .scroller {
        .v-window {
            height: 100%;

            &::v-deep {
                .v-window__container {
                    .v-window-item {
                        width: 100%;
                        height: 100%;
                    }
                }
            }
        }

        @media (max-width: 991px) {
            .v-window {
                &::v-deep {
                    .v-window__container {
                        .v-window-item {
                            padding-bottom: 60px;
                        }
                    }
                }
            }

            &__dots {
                right: 4px;
            }
        }
    }
</style>
