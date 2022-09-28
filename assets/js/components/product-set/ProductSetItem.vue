<template>
    <div class="product-set-item"
         :style="bgImageStyle"
         @mouseover="isHover=true"
         @mouseleave="isHover=false"
         :class="{active: isActive}"
    >
        <product-set-like :product-set="item" :active="isLiked" v-if="!isInWishList"/>
        <product-set-remove :product-set-id="originalId" v-if="isInWishList"/>

        <div class="product-set-item__details">
            <div class="product-set-item__details--icons">
                <v-icon>{{ item.icon }}</v-icon>
            </div>
            <h3 class="product-set-item__details--title">{{ item.title }}</h3>
            <div class="product-set-item__details--text" v-if="!isInWishList">
                {{ this.item.displayedProducts.length }}
                <translated-text code="SIDEBAR.PRODUCTS"></translated-text>
            </div>
        </div>

        <v-btn depressed color="orange" class="product-set-item__more-btn" @click="onMoreInfo()">
            <translated-text code="USECASE.MORE_INFO"></translated-text>
        </v-btn>
    </div>
</template>

<script>
    import {mapActions, mapGetters, mapState} from "vuex";
    import RouteGenerator from "./../../modules/RouteGenerator";
    import ProductSetLike from "./ProductSetLike";
    import ProductSetRemove from "./ProductSetRemove";
    import ProductSet from "../../models/product-set";
    import ProductSetStore from "./store";
    import TranslatedText from "../translated-text/TranslatedText";
    import WishListProductSet from "../../models/wish-list-product-set";
    import VideoPlayerStore from "../video-player/store";
    import WishListStore from "../wish-list/store";

    export default {
        name: "ProductSetItem",

        props: {
            item: {
                type: ProductSet | WishListProductSet,
                required: true
            }
        },

        components: {TranslatedText, ProductSetRemove, ProductSetLike},

        data() {
            return {
                isHover: false,
            };
        },

        computed: {
            ...mapState("global", ["me"]),
            ...mapGetters("wishList", ["selectedProductSets"]),

            isInWishList() {
                return this.item instanceof WishListProductSet;
            },

            isLiked() {
                return this.selectedProductSets.indexOf(this.originalId) !== -1;
            },

            isActive() {
                return this.isHover;
            },

            originalId() {
                return this.isInWishList ? this.item.originalId : this.item.id;
            },

            bgImageStyle() {
                return "background-image: url(" + RouteGenerator.generate("web.image", {
                    type: "product_set",
                    crop: "fit",
                    size: "375x180",
                    name: this.item.image ?? "default.jpg"
                }) + ");";
            },
        },

        methods: {
            ...mapActions("productSet", ["setMoreDetailsProductSetId"]),
            ...mapActions("videoPlayer", ["setVideoUrl"]),

            onMoreInfo() {
                this.setMoreDetailsProductSetId(this.originalId);
                this.setVideoUrl(null);

                this.tcEventClickOnProductSetItem(this.item.title);
            },

            tcEventClickOnProductSetItem(productSetTitle) {
                if (typeof (tc_events_global) !== 'undefined') {
                    try {
                        tc_events_global(this, 'smartConfiguration_listClick', {
                            'evt_category': 'SmartHome_planner',
                            'evt_button_action': 'list_click',
                            'evt_button_label': productSetTitle
                        });
                    } catch (error) {
                        console.error(error);
                    }
                }
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("productSet")) {
                this.$store.registerModule("productSet", ProductSetStore);
            }

            if (!this.$store.hasModule("videoPlayer")) {
                this.$store.registerModule("videoPlayer", VideoPlayerStore);
            }

            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .product-set-item {
        //min-height: 120px;
        border-radius: 5px;
        position: relative;
        margin-bottom: 17px;
        background-position: center center;
        background-size: cover;
        padding: 22px 0 25px;
        @include flexbox();
        @include align-items(center);
        @include flex-direction(column);

        &__more-btn {
            position: relative;
            z-index: 5;
            margin-top: 8px;
            height: 0 !important;
            overflow: hidden;
            @include transition(height 0.2s ease-in-out);
        }

        &__details {
            position: relative;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 4;
            color: #fff;
            @include flexbox();
            @include align-items(center);
            @include justify-content(center);
            @include flex-direction(column);

            .v-icon {
                margin-bottom: 4px;
                color: #fff;
            }

            .v-btn {
                margin-top: 8px;
            }

            &--icons {
                @include flexbox();
                @include align-items(center);
            }

            &--title {
                @include normal-font(18px, 24px);
                margin-bottom: 3px;
            }

            &--text {
                @include normal-font(14px, 19px);
            }
        }

        &.active {
            .product-set-item__more-btn {
                height: 40px !important;
            }
        }

        &:before {
            content: "";
            position: absolute;
            z-index: 1;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(60, 79, 100, 0.45);
            border-radius: 5px;
        }

        &:after {
            content: "";
            height: 50%;
            position: absolute;
            z-index: 3;
            bottom: 0;
            left: 0;
            right: 0;
            @include opacity(0.5);
            background-image: linear-gradient(to top, #1a1b1c, rgba(26, 27, 28, 0) 100%);
            border-radius: 0 0 5px 5px;
        }
    }
</style>
