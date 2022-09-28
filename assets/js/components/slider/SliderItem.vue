<template>
    <div class="slider-item" :style="bgImageStyle">
        <div :class="{'animated-gif-on-mobile': isAnimatedGifFileOnMobile}">
            <div v-if="isVideoFile" class="video-block">
                <video :src="videoSrc" autoplay loop muted></video>
            </div>

            <div v-if="isAnimatedGifFileOnMobile" class="mobile-animated-gif-block_fluid">
                <div class="mobile-animated-gif-block"
                     :style="'background-image: url(' + animatedGifSrc + ')'">
                </div>
            </div>

            <div class="centered">
                <div v-if="isSlider" class="slider-item__intro">
                    <h2 class="slider-item__intro--title">{{ slide.title }}</h2>
                    <div class="slider-item__intro--text">
                        {{ slide.subTitle }}
                    </div>

                    <v-btn
                        v-if="showStartButton"
                        @click="clickStartButton"
                        depressed
                        large
                        color="orange"
                    >
                        <translated-text code="USECASE.MORE_INFO"/>
                    </v-btn>
                </div>

                <slider-product-set
                    v-if="productSetId"
                    :product-set-id="productSetId"
                    :position="slide.productSetPosition"
                    @clickMoreInfo="clickProductSetMoreInfo"
                />
            </div>

            <div v-if="showSlideArrow" class="slider-item__arrow">
                <v-icon>left-icon</v-icon>
            </div>
        </div>
    </div>
</template>

<script>
    import Slider from "../../models/slider";
    import Slide from "../../models/slide";
    import RouteGenerator from "../../modules/RouteGenerator";
    import TranslatedText from "../translated-text/TranslatedText";
    import SliderProductSet from "./SliderProductSet";
    import SLIDER_FILE_MIME_TYPE from "../../dictionary/slider-file-mime-type";

    export default {
        name: "SliderItem",

        props: {
            slide: {
                type: Slider | Slide,
                required: true,
            },

            showStartButton: {
                type: Boolean,
                required: false,
                default: false
            },

            showSlideArrow: {
                type: Boolean,
                required: false,
                default: false
            },
        },

        components: {SliderProductSet, TranslatedText},

        data() {
            return {
                fileName: 'default.jpg',
                fileMimeType: SLIDER_FILE_MIME_TYPE.IMAGE,
            };
        },

        computed: {
            isMobile() {
                return this.$vuetify.breakpoint.xs;
            },

            isSlider() {
                return this.slide instanceof Slider;
            },

            isVideoFile() {
                return this.fileMimeType === SLIDER_FILE_MIME_TYPE.VIDEO;
            },

            isAnimatedGifFile() {
                return this.fileMimeType === SLIDER_FILE_MIME_TYPE.ANIMATED_GIF;
            },


            isAnimatedGifFileOnMobile() {
                return this.isMobile && this.isAnimatedGifFile;
            },

            bgImageStyle() {
                if (this.isVideoFile) {
                    return null;
                }

                if (this.isAnimatedGifFileOnMobile) {
                    return null;
                }

                let imgUrl;

                if (this.fileMimeType === SLIDER_FILE_MIME_TYPE.IMAGE) {
                    imgUrl = RouteGenerator.generate("web.file", {
                        type: "slider",
                        crop: "fit",
                        size: "950x640",
                        name: this.fileName
                    });
                } else if (this.isAnimatedGifFile) {
                    imgUrl = this.animatedGifSrc;
                }

                return imgUrl
                    ? "background-image: url(" + imgUrl + ");"
                    : null;
            },

            videoSrc() {
                return RouteGenerator.generate("web.media.original", {
                    type: "slider",
                    name: this.fileName
                });
            },

            animatedGifSrc() {
                return RouteGenerator.generate("web.media.original", {
                    type: "slider",
                    name: this.fileName
                });
            },

            productSetId() {
                return this.slide instanceof Slide && this.slide.productSetId ? this.slide.productSetId : null;
            },
        },

        watch: {
            isMobile(val) {
                this.initFileData();
            },
        },

        methods: {
            clickStartButton() {
                this.$emit("clickStart");
            },

            clickProductSetMoreInfo(productSetId: Number) {
                this.$emit("productSetMoreInfo", productSetId);
            },

            initFileData() {
                if (this.isMobile && this.slide.fileMobileName) {
                    // it's mobile device and current slide has special mobile file
                    this.fileName = this.slide.fileMobileName;
                    this.fileMimeType = this.slide.fileMobileMimeType;
                } else if (this.slide.fileName) {
                    // it's desktop device OR it's mobile device but slide not has special mobile file
                    this.fileName = this.slide.fileName;
                    this.fileMimeType = this.slide.fileMimeType;
                }
            },
        },

        mounted() {
            this.initFileData();
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .v-window-item {
        background-size: cover;
        background-position: center;
    }

    .slider-item {
      /*  @include calc("height", "100vh - 74px");*/
        position: relative;
        background-position: 50%;
        background-size: cover;
        //padding-top: 74px;

        @media all and (max-width: 768px) {
            background-color: #000;

            &:last-child {
                margin-bottom: -2px;
                bottom: 2px;
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        &__arrow {
            position: absolute;
            bottom: 50px;
            left: 0;
            right: 0;
            text-align: center;
            animation: bounce 2s infinite;

            &::v-deep {
                .v-icon {
                    color: #fff;
                    font-size: 13px;
                    @include transform(rotate(270deg));
                }
            }
        }

        .centered {
            max-width: 1140px;
            margin: 0 auto;
            height: 100%;
            @include flexbox();
            @include align-items(center);
        }

        .video-block {
            position: absolute;
            top: 74px;
            left: 0;
            right: 0;
            z-index: 1;
            max-width: 100%;
            height: calc(100vh - 74px);
            text-align: center;

            &:before {
                content: '';
                display: block;
                height: 74px;
                background: #000;
                position: absolute;
                top: -74px;
                left: 0;
                right: 0;
            }

            video {
               width: 100%;
                height: 100%;
                object-fit: cover;
            }

            @media all and (max-width: 767px) {
                position: static;
                height: calc(100vh - 490px);
                padding: 30px 0;
                background: #000000;
                background: -moz-linear-gradient(top,  #000000 0%, #fcac22 25%, #fcac22 75%, #000000 100%);
                background: -webkit-linear-gradient(top,  #000000 0%,#fcac22 25%,#fcac22 75%,#000000 100%);
                background: linear-gradient(to bottom,  #000000 0%,#fcac22 25%,#fcac22 75%,#000000 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#000000', endColorstr='#000000',GradientType=0 );
            }
        }

        &__intro {
            width: 100%;
            text-align: center;
            z-index: 5;

            &--title {
                color: #fff;
                font-family: $fontSomfySansLight;
                @include normal-font(88px, 110px);
                margin-bottom: 15px;

                @media (max-width: 991px) {
                    @include normal-font(35px, 35px);
                    margin-bottom: 10px;
                }
            }

            &--text {
                color: #fff;
                font-family: $fontSomfySansMedium;
                @include normal-font(32px, 43px);
                margin-bottom: 25px;

                @media (max-width: 991px) {
                    font-family: $fontSomfySansLight;
                    @include normal-font(16px, 20px);
                }
            }

            .v-btn {
                width: 224px;
            }
        }

        .mobile-animated-gif-block_fluid {
            padding: 30px 0;
            background: #000000;
            background: -moz-linear-gradient(top,  #000000 0%, #fcac22 25%, #fcac22 75%, #000000 100%);
            background: -webkit-linear-gradient(top,  #000000 0%,#fcac22 25%,#fcac22 75%,#000000 100%);
            background: linear-gradient(to bottom,  #000000 0%,#fcac22 25%,#fcac22 75%,#000000 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#000000', endColorstr='#000000',GradientType=0 );

        }
        .mobile-animated-gif-block {
            height: calc(100vh - 490px);
            width: 100%;
            background-size: cover;
        }

        .animated-gif-on-mobile {
            &::v-deep {
                .slider-product-set__widget--title {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
                .slider-product-set__widget--text {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
            }
        }
    }
</style>
