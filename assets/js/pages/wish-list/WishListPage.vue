<template>
    <div class="app">
        <app-header active="wishlist" />

        <div class="app__content">
            <div class="app__main">
                <home-planner mode="wish-list" />
            </div>
            <div class="app__aside">
                <h3 class="app__aside--title">
                    <translated-text code="WISHLIST.TITLE"></translated-text>
                </h3>
                <v-tabs centered fixed-tabs slider-color="#fcac22" v-model="tabs" class="app__aside--menu">
                    <v-tab>
                        <translated-text code="WISHLIST.USECASES"></translated-text>
                    </v-tab>
                    <v-tab>
                        <translated-text code="WISHLIST.PRODUCTS"></translated-text>
                    </v-tab>
                </v-tabs>
                <v-tabs-items v-model="tabs">
                    <v-tab-item>
                        <wish-list-empty v-if="wishList.isEmptyProductSets" />
                        <div class="product-set__list">
                            <wish-list-product-set-list />
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <wish-list-empty v-if="wishList.isEmptyProductSets" />
                        <wish-list-product-set-cart-list />
                    </v-tab-item>
                </v-tabs-items>

                <cart-total />
            </div>

            <product-set-details
                v-if="moreDetailsProductSetId"
                :product-set-id="moreDetailsProductSetId"
                :key="moreDetailsProductSetId"
                :wish-list-id="wishList.id"
                is-wish-list-page
            />
        </div>

        <help />
    </div>
</template>

<script>
    import {mapState, mapActions} from 'vuex';
    import {EventBus, events as EVENTS} from '../../modules/EventBus';
    import AppHeader from '../../components/app-header/AppHeader';
    import Help from '../../components/help/Help';
    import HomePlanner from '../../components/home-planner/HomePlanner';
    import ProductSetDetails from '../../components/product-set-details/ProductSetDetails';
    import ProductSetItem from '../../components/product-set/ProductSetItem';
    import Share from '../../components/share/Share';
    import CartTotal from '../../components/cart/CartTotal';
    import TranslatedText from '../../components/translated-text/TranslatedText';
    import WishListProductSetList from '../../components/wish-list/WishListProductSetList';
    import WishListProductSetCartList from '../../components/wish-list/WishListProductSetCartList';
    import ProductSetStore from '../../components/product-set/store';
    import WishListStore from '../../components/wish-list/store';
    import WishListEmpty from '../../components/wish-list/WishListEmpty';
    import RouterGenerator from '../../modules/RouteGenerator';

    export default {
        name: 'WishListPage',

        props: {},

        components: {
            WishListEmpty,
            TranslatedText,
            Help,
            ProductSetDetails,
            WishListProductSetCartList, WishListProductSetList,
            CartTotal, Share, ProductSetItem, HomePlanner, AppHeader,
        },

        data() {
            return {
                tabs: 1, // make active tab "Products"
                marker: true,
            };
        },

        computed: {
            ...mapState('global', ['me',]),
            ...mapState('productSet', ['moreDetailsProductSetId']),
            ...mapState('wishList', ['wishList']),

            wishListLink() {
                return Object.keys(this.me).length > 0
                    ? RouterGenerator.generate('web.wishlist.details', {wishListUid: this.me.uid})
                    : null;
            },
        },

        watch: {
            me(val) {
                if (val && val.uid) {
                    this.loadWishList({uid: this.me.uid})
                }
            }
        },

        methods: {
            ...mapActions("wishList", ["loadWishList"])
        },

        beforeCreate() {
            if (!this.$store.hasModule('productSet')) {
                this.$store.registerModule('productSet', ProductSetStore);
            }

            if (!this.$store.hasModule('wishList')) {
                this.$store.registerModule('wishList', WishListStore);
            }
        },

        created() {
            let vm = this;
            EventBus.on(EVENTS.WISHLIST_SAVED, () => {
                window.location.href = vm.wishListLink;
            });
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";
    @import "../../../scss/components/tabs";

    .app {
        &__content {
            @include flexbox();
            padding-right: 20px;
            position: relative;

            @media (max-width: 991px) {
                right: 0;
                padding-right: 0;
                @include flex-direction(column);
            }

            &::v-deep {
                .product-set-details {
                    &.mobile {
                        top: 0;
                    }
                }
            }
        }

        &__main {
            width: 100%;
            padding-right: 20px;
            position: relative;

            @media (max-width: 991px) {
                padding-right: 0;
            }
        }

        &__aside {
            max-width: 375px;
            width: 100%;
            padding-top: 25px;
            position: relative;

            &--title {
                color: #3c4f64;
                text-align: center;
                @include normal-font(24px, 32px);
                font-family: $fontSomfySansLight;
                margin-bottom: 0;
            }

            &::v-deep {
                .wish-list, .wish-list-products {
                    min-height: 110px;
                    @include calc("max-height", "100vh - 350px");
                    overflow: auto;

                    @media (max-width: 991px) {
                        max-height: none;
                        overflow: visible;
                    }
                }
            }

            &:before {
                content: "";
                height: 1px;
                background: #ececec;
                position: absolute;
                top: 0;
                left: -20px;
                right: -20px;
            }

            .v-tabs-items {
                max-width: none;
                background: #f8f7f5;
                margin: 0 -20px;
                padding: 25px 20px;
            }

            @media (max-width: 991px) {
                height: auto;
                max-width: none;
                padding: 15px 20px 0;
            }
        }
    }
</style>
