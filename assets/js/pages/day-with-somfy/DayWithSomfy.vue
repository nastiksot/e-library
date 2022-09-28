<template>
    <div class="app">
        <app-header class="header-day-with-somfy" active="day-with-somfy"/>

        <div class="day-with-somfy_slider">
            <slider/>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import AppHeader from "../../components/app-header/AppHeader";
    import Slider from "../../components/slider/Slider";
    import WishListStore from "../../components/wish-list/store";

    export default {
        name: "DayWithSomfy",

        props: {},

        components: {AppHeader, Slider},

        data() {
            return {};
        },

        computed: {
            ...mapState("global", ["me",]),
        },

        watch: {
            me() {
                if (Object.keys(this.me).length !== 0) {
                    this.loadWishList({uid: this.me.uid})
                }
            }
        },

        methods: {
            ...mapActions("wishList", ["loadWishList"]),
        },

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        },

    };
</script>

<style scoped lang="scss">
    .header-day-with-somfy {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 9;
        background: #fff;
    }

    .day-with-somfy_slider {
        &::v-deep {
            .product-set-details.mobile .tabs {
                position: fixed;
            }

            .product-set-details.mobile .v-navigation-drawer {
                top: 72px!important;
            }

            .video-overlay .v-overlay__content {
                @media all and (max-width: 992px){
                    padding-top: 44px;
                }
            }

            .video-player {
                .video-player__panel {
                    @media all and (max-width: 992px) {
                        top: 40px;
                    }
                }
            }

            .product-set-details__content {
                @media all and (max-width: 992px){
                    max-height: calc(100vh - 126px);
                    overflow: auto;
                }
            }
        }
    }
</style>
