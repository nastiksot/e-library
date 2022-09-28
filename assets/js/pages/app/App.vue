<template>
    <div class="app">
        <app-header active="home-planner"/>

        <h2 class="app__title">Entdecke & plane dein Smart Home </h2>
        <div class="app__tabs">
            <v-btn text @click="activeTab = 1" :class="{'active' : activeTab === 1 }">
                <translated-text code="NAVIGATION.TAB.HOUSE_VIEW"></translated-text>
            </v-btn>
            <v-btn text @click="activeTab = 2" :class="{'active' : activeTab === 2 }">
                <translated-text code="NAVIGATION.TAB.PRODUCT_SETS_VIEW"></translated-text>
            </v-btn>
        </div>
        <div class="app__content">
            <div class="app__main" :class="{'active' : activeTab === 1 }">
                <alert v-if="0"
                       color="orange"
                       message='Willkommen Farzin Raisstousi, Konfiguration erfolgreich in Ihrer <a href="javascript:void(0);">Hausverwaltung</a>gespeichert.'
                >
                </alert>

                <home-planner/>
            </div>
            <div class="app__aside" :class="{'active' : activeTab === 2 }">
                <product-set-list/>
            </div>

            <product-set-details
                v-if="moreDetailsProductSetId"
                :product-set-id="moreDetailsProductSetId"
                :key="moreDetailsProductSetId"
                :wish-list-id="wishListId"
            />
        </div>

        <help/>
    </div>
</template>

<script>
    import {mapGetters, mapState} from "vuex";
    import AppHeader from "../../components/app-header/AppHeader";
    import HomePlanner from "../../components/home-planner/HomePlanner";
    import ProductSetList from "../../components/product-set/ProductSetList";
    import Help from "../../components/help/Help";
    import ProductSetDetails from "../../components/product-set-details/ProductSetDetails";
    import ProductSetStore from "../../components/product-set/store";
    import WishListStore from "../../components/wish-list/store";
    import Alert from "../../components/alert/Alert";
    import TranslatedText from "../../components/translated-text/TranslatedText";

    export default {
        name: "App",

        props: {},

        components: {ProductSetDetails, Help, ProductSetList, HomePlanner, AppHeader, Alert, TranslatedText},

        data() {
            return {
                activeTab: 1
            };
        },

        computed: {
            ...mapState("productSet", ["moreDetailsProductSetId"]),
            ...mapState("wishList", ["wishList"]),
            ...mapGetters("wishList", ["selectedProductSets"]),

            wishListId() {
                return this.moreDetailsProductSetId && this.selectedProductSets.indexOf(this.moreDetailsProductSetId) !== -1
                    ? this.wishList.id
                    : null;
            },
        },

        watch: {},

        methods: {},

        beforeCreate() {
            if (!this.$store.hasModule("productSet")) {
                this.$store.registerModule("productSet", ProductSetStore);
            }

            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .app {
        &__title {
            @include normal-font(28px, 32px);
            text-align: center;
            color: #1a1b1c;
            font-family: $fontSomfySansLight;
            margin-bottom: 8px;
            display: none;

            @media (max-width: 991px) {
                display: block;
            }
        }

        &__content {
            @include flexbox();
            padding-right: 20px;
            position: relative;
            z-index: 1;

            @media (max-width: 991px) {
                right: 0;
                padding-right: 0;
            }
        }

        &__tabs {
            text-align: center;
            display: none;

            .v-btn {
                color: #1a1b1c;
                font-size: 16px;
                font-family: $fontSomfySansLight;
                border-bottom: 3px solid transparent;
                border-radius: 0;

                &.active {
                    color: #fcac22;
                    border-bottom-color: #fcac22;
                }
            }

            @media (max-width: 991px) {
                display: block;
            }
        }

        &__main {
            width: 100%;
            padding-right: 20px;
            position: relative;

            &.active {
                display: block;
            }

            @media (max-width: 991px) {
                display: none;
                padding-right: 0;
            }
        }

        &__aside {
            max-width: 375px;
            width: 100%;
            padding-top: 25px;
            position: relative;
            @include calc("height", "100vh - 72px");
            min-height: 624px;

            &:before {
                content: "";
                height: 1px;
                background: #ececec;
                position: absolute;
                top: 0;
                left: -20px;
                right: -20px;

                @media all and (max-width: 992px){
                    left: 0;
                    right: 0;
                }
            }

            &.active {
                display: block;
            }

            @media (max-width: 991px) {
                height: auto;
                display: none;
                max-width: none;
                padding: 15px 20px 0;
            }
        }
    }
</style>
