<template>
    <div v-if="productSet" class="slider-product-set__widget" :class="position">
        <v-icon>{{ productSet.icon }}</v-icon>
        <h3 class="slider-product-set__widget--title">{{ productSet.title }}</h3>
        <div class="slider-product-set__widget--text" v-html="productSet.description"/>

        <div class="slider-product-set__widget--actions">
            <v-btn text large block @click="clickMoreInfoButton" class="more-info">
                <translated-text code="USECASE.MORE_INFO"/>
            </v-btn>

            <product-set-save :product-set="productSet" :base-product-set-id="productSetId" is-day-with-somfy-page/>
        </div>
    </div>
</template>

<script>
    import ProductSetStore from "../product-set/store";
    import TranslatedText from "../translated-text/TranslatedText";
    import ProductSet from "../../models/product-set";
    import {mapGetters} from "vuex";
    import ProductSetSave from "../product-set/ProductSetSave";

    export default {
        name: "SliderProductSet",

        props: {
            productSetId: {
                type: Number,
                required: true,
            },

            position: {
                type: String,
                required: false,
                default: null,
            },
        },

        components: {ProductSetSave, TranslatedText},

        data() {
            return {};
        },

        computed: {
            ...mapGetters("productSet", ["getProductSetById"]),

            productSet(): ?ProductSet {
                return this.getProductSetById(this.productSetId);
            },
        },

        watch: {},

        methods: {
            clickMoreInfoButton() {
                this.$emit("clickMoreInfo", this.productSetId);

                this.tcEventClickOnSliderProductSet(this.productSet ? this.productSet.title : 'not found');
            },

            tcEventClickOnSliderProductSet(productSetTitle) {
                if (typeof (tc_events_global) !== 'undefined') {
                    try {
                        tc_events_global(this, 'inspiration_see_productList', {
                            'evt_category': 'inspiration',
                            'evt_button_action': 'seeProductlist_click',
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
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .more-info {
        border: 1px solid #FCAC22;
        color: #FCAC22!important;
        border-radius: 4px;
        @include normal-font(16px, 20px);
        margin-bottom: 10px;
        text-decoration: none!important;
    }

    .slider-product-set {
        &__widget {
            min-width: 295px;
            max-width: 350px;
            background: #fff;
            border-radius: 6px;
            padding: 20px 25px;
            text-align: center;
            color: #3C4F64;
            z-index: 5;

            .v-icon {
                font-size: 61px;
                color: #FCAC22;
                margin-bottom: 13px;

                @media all and (max-width: 768px){
                    font-size: 40px;
                }
            }

            &--title {
                color: #3C4F64;
                @include normal-font(28px, 30px);
                font-family: $fontSomfySansMedium;
                margin-bottom: 10px;

                @media all and (max-width: 992px){
                    margin-bottom: 5px;
                }
            }

            &--text {
                font-family: $fontSomfySansLight;
                @include normal-font(16px, 24px);
                margin-bottom: 20px;
                max-height: calc(100vh - 404px);
                overflow-y: auto;

                @media all and (max-width: 992px){
                    margin-bottom: 10px;
                }
            }

            &--actions {
                .v-btn--text {
                    color: #3C4F64;
                    text-decoration: underline;

                    &:hover {
                        text-decoration: none;
                    }
                }
            }

            &.right {
                margin-left: auto;
            }

            &.center {
                margin: auto;
            }

            @media (max-width: 768px) {
                .fire-icon {
                    font-size: 32px;
                    margin-bottom: 5px;
                }

                &--title {
                    font-size: 22px;
                    margin-bottom: 3px;
                }
            }
        }
    }
</style>
