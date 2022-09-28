<template>
    <v-overlay id="overlay-for-product-set-description"
               class="video-overlay"
               :opacity="opacity"
               absolute>
        <div v-if="productSet">
            <div v-if="isDisplayDescription" class="product-details-full-info" :class="{'mobile' : isMobile }">
                <div class="product-details-full-info__details" :style="bgImageStyle">
                    <div class="video-player__panel">
                        <v-btn text @click="closeProductSet">
                            <v-icon>close-icon</v-icon>
                            <translated-text code="VIDEO_PLAYER.BUTTON.CLOSE"/>
                        </v-btn>
                    </div>
                    <div class="product-details-full-info__details--inner">
                        <div class="product-details-full-info__icon">
                            <v-icon>
                                {{ productSet.icon }}
                            </v-icon>
                        </div>
                        <div class="product-details-full-info__title">
                            {{ productSet.title }}
                        </div>
                        <div v-if="productSet.youtubeVideoUrl" class="product-details-full-info__video">
                            <v-btn text @click="onPlayVideo">
                                <v-icon>play-icon</v-icon>
                            </v-btn>
                        </div>
                        <description-text-read-more v-if="productSet.description" :text="productSet.description"/>
                    </div>
                </div>
            </div>
        </div>
    </v-overlay>
</template>

<script>
import {mapActions, mapGetters, mapState} from "vuex";
import RouteGenerator from "../../modules/RouteGenerator";
import TranslatedText from "../translated-text/TranslatedText";
import VideoPlayerStore from "../video-player/store";
import WishListStore from "../wish-list/store";
import ProductSetStore from "../product-set/store";
import DescriptionTextReadMore from "../description-text/description-text-read-more";
import MORE_DETAILS_PRODUCT_SET_STATE from "../../dictionary/more-details-product-set-state";
import {EventBus, events as EVENTS} from "../../modules/EventBus";

export default {
    name: "ProductSetDetailsFullInfo",

    props: {
        productSetId: {
            type: Number | null,
            required: false,
            default: null
        },
        isMobile: {
            type: Boolean,
            required: true,
        },

        isDayWithSomfyPage: {
            type: Boolean,
            required: false,
            default: false,
        },
    },

    components: {DescriptionTextReadMore, TranslatedText},

    data() {
        return {
            /** @type ProductSet|WishListProductSet */
            productSet: null,
        };
    },

    computed: {
        ...mapState("videoPlayer", ["videoCode"]),
        ...mapState("productSet", ["moreDetailsProductSetId", "moreDetailsProductSetState"]),
        ...mapGetters("productSet", [
                "isMoreDetailsProductSetStateDescription",
            ]
        ),
        ...mapGetters("wishList", ["selectedProductSets"]),

        isWishListProductSet() {
            return (this.productSetId && this.selectedProductSets.indexOf(this.productSetId) !== -1);
        },

        bgImageStyle() {
            return "background-image: url(" + RouteGenerator.generate("web.image", {
                type: "product_set",
                crop: "fit",
                size: "415x201",
                name: this.productSet.image ?? "default.jpg"
            }) + ");";
        },

        isDisplayDescription() {
            return this.isMoreDetailsProductSetStateDescription();
        },

        opacity() {
            return this.isDayWithSomfyPage ? '0.95' : '0.76';
        },
    },

    watch: {
        productSetId(val) {
            this.updateProductSet(val);
        },

        videoCode(newVal, oldVal) {
            if (oldVal !== null && newVal === null) {
                this.closeProductSet();
            }
        }
    },

    methods: {
        ...mapActions("productSet", ["setMoreDetailsProductSetState"]),
        ...mapGetters("productSet", ["getProductSetById"]),
        ...mapGetters("wishList", ["getProductSetByOriginalId"]),
        ...mapActions("videoPlayer", ["setVideoUrl"]),

        onPlayVideo() {
            this.setMoreDetailsProductSetState(MORE_DETAILS_PRODUCT_SET_STATE.VIDEO);
            this.setVideoUrl(this.productSet.youtubeVideoUrl);

            this.tcEventClickOnPlayVideo(this.productSet.title);
        },

        tcEventClickOnPlayVideo(productSetTitle) {
            if (typeof (tc_events_global) === 'undefined') {
                return;
            }

            try {
                tc_events_global(this, 'video', {
                    'evt_category': 'video',
                    'evt_button_action': 'video_play',
                    'evt_button_label': productSetTitle
                });
            } catch (error) {
                console.error(error);
            }
        },

        closeProductSet() {
            EventBus.emit(EVENTS.PRODUCT_SET_DETAILS_CLOSED);
        },

        updateProductSet(productSetId) {
            if (productSetId) {
                if (this.isWishListProductSet) {
                    this.productSet = this.getProductSetByOriginalId()(productSetId);
                } else {
                    this.productSet = this.getProductSetById()(productSetId);
                }
            } else {
                this.productSet = null;
            }
        },

        overlayClickListener() {
            let overlayEl = document.getElementById('overlay-for-product-set-description');

            if (!overlayEl) {
                return;
            }

            let overlayContentEls = overlayEl.getElementsByClassName('v-overlay__content');

            if (!overlayContentEls.length) {
                return;
            }

            let overlayContentEl = overlayContentEls[0],
                vm = this;

            overlayEl.addEventListener('click', function(event) {
                let clickedEl = event.target;

                if (overlayContentEl === clickedEl
                    || overlayContentEl.contains(clickedEl)
                    || clickedEl.classList.contains('play-icon') // click on `PlayVideo` icon
                ) {
                    // click inside overlay content => do nothing
                } else {
                    // click on overlay's background => close ProductSet block
                    vm.closeProductSet();
                }
            });
        },
    },

    mounted() {
        this.updateProductSet(this.productSetId);

        this.overlayClickListener();
    },

    beforeCreate() {
        if (!this.$store.hasModule("videoPlayer")) {
            this.$store.registerModule("videoPlayer", VideoPlayerStore);
        }

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

.video-overlay {

    &::v-deep {
        .v-overlay__content {
            @media all and (max-width: 992px){
                align-self: flex-start;
                padding-top: 10px;
                padding-left: 20px;
                padding-right: 20px;
            }
        }
    }
}

.product-details-full-info {
    width: 100%;
    max-width: 810px;

    .video-player__panel {
        padding: 7px 15px;
        background-image: linear-gradient(to right, rgba(60, 79, 100, 0) 15%, #1e2832 83%);
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 11;
        text-align: right;
    }

    &__details {
        padding: 113px 22px 34px;
        position: relative;
        background-position: center center;
        background-size: cover;
        @include transition(padding 0.4s ease-in-out);

        @media all and (max-height: 655px) and (min-width: 992px){
            padding-top: 60px;
        }

        @media all and (max-width: 992px){
           padding: 73px 15px 20px;
        }

        &--inner {
            position: relative;
            z-index: 2;
            color: #fff;
            text-align: center;
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
        }
    }


    &__icon {
        margin-bottom: 6px;
        @include transition(margin 0.4s ease-in-out);

        .v-icon {
            color: #fff;
        }
    }

    &__title {
        @include normal-font(24px, 26px);
        margin-bottom: 22px;
    }

    &__text {
        @include normal-font(14px, 16px);
        margin-bottom: 25px;
        @include transition(margin 0.4s ease-in-out);
    }

    &__video {
        margin-bottom: 24px;
        .v-btn {
            color: #fff;
            height: 81px!important;

            .v-icon {
                font-size: 81px;
                margin-right: 3px;
            }
        }
    }

    &::v-deep {
        .description-text {
            background: rgba(248, 247, 245, .75);
            max-height: 225px;
            overflow-y: auto;

            &__text {
                @media all and (max-width: 1200px){
                    max-height: 120px;
                    overflow-y: auto;
                }
            }

            .description-text__inner {
                @include normal-font(18px, 24px);
                font-family: $fontSomfySansLight;
                padding: 20px 25px 12px;
                color: #3C4F64;
                border-radius: 5px;
            }
        }
    }

    &.mobile {
        .product-details-full-info__details {
            -webkit-transition: padding 0.4s ease-in-out;
            -o-transition: padding 0.4s ease-in-out;
            transition: padding 0.4s ease-in-out;
        }
    }
}
</style>

