<template>
    <div class="slider-list">
        <full-page
            ref="fullpage"
            id="fullpage"
            :options="options"
        >
            <slider-item
                class="section section-first"
                :slide="slider"
                :show-start-button="slider.slides.length > 0"
                :show-slide-arrow="slider.slides.length > 0"
                @productSetMoreInfo="productSetMoreInfo"
                @clickStart="clickStart"
            />

            <slider-item
                v-for="(slide, index) in slider.slides"
                ref="fullpage-slides"
                class="section"
                :key="slide.id"
                :slide="slide"
                :show-slide-arrow="false"
                @productSetMoreInfo="productSetMoreInfo"
            />
        </full-page>

        <slider-footer />
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import SliderStore from "./store";
    import Slider from "../../models/slider";
    import SliderItem from "./SliderItem";
    import SliderFooter from "./SliderFooter";
    import {EventBus, events as EVENTS} from "../../modules/EventBus";

    export default {
        name: "SliderList",

        props: {
            step: {
                type: Number,
                required: false,
                default: 0
            },
            slider: {
                type: Slider,
                required: true,
            }
        },

        components: {SliderItem, SliderFooter},

        data() {
            return {
                enabled: true,
                isActiveSlider: true,
                activeSlide: null,

                options: {
                    licenseKey: 'YOUR_KEY_HEERE',
                    scrollBar: true,
                    fitToSection: false,
                    navigation: true,

                    afterLoad: this.afterLoad,
                    onLeave: this.onLeave,

                    css3:true,
                    autoScrolling: true,
                },
            };
        },

        methods: {
            onLeave(origin, destination, direction) {
                this.videoHandlerOnSlideChanged(origin, false);

                let activeSlide = destination.index
                let dotsCount = document.querySelectorAll('#fp-nav li a');
                for (let i = 0; i < dotsCount.length; i++) {
                    if (i < activeSlide){
                        dotsCount[i].classList.add('ex-active');
                    }else {
                        dotsCount[i].classList.remove('ex-active');
                    }
                }
            },

            afterLoad(origin, destination, direction) {
                this.activeSlide = destination;

                this.videoHandlerOnSlideChanged(destination, true);
            },

            videoHandlerOnSlideChanged(source, isPlay) {
                if (!source.item) {
                    return;
                }

                let video = source.item.getElementsByTagName('video');

                if (video.length) {
                    if (isPlay && this.isActiveSlider) {
                        video[0].play();
                    } else {
                        video[0].pause();
                    }
                }
            },

            clickStart() {
                this.$refs.fullpage.api.moveSectionDown();
            },

            productSetMoreInfo(productSetId: Number) {
                this.isActiveSlider = false;

                this.$emit("productSetMoreInfo", productSetId);
            },

            watchFullPage () {
                let vm = this;
                let target = vm.$refs['fullpage-slides'][vm.$refs['fullpage-slides'].length - 1];
                let fullPage = vm.$refs.fullpage;

                window.onscroll = () => {
                    if (target.$el.offsetTop - window.scrollY > 0) {
                        if (!vm.enabled) {
                            fullPage.api.setAutoScrolling(true);
                            vm.enabled = true;
                        }
                    } else {
                        if (vm.enabled) {
                            fullPage.api.setAutoScrolling(false);
                            vm.enabled = false;
                        }
                    }
                }
            },
        },

        watch: {
            isActiveSlider(isActive) {
                this.$refs.fullpage.api.setAllowScrolling(isActive);
                this.$refs.fullpage.api.setKeyboardScrolling(isActive);

                if (this.activeSlide) {
                    // stop video when Product set info shown
                    this.videoHandlerOnSlideChanged(this.activeSlide, isActive);
                }
            },
        },

        mounted() {
            this.watchFullPage();
        },

        beforeCreate() {
            if (!this.$store.hasModule("slider")) {
                this.$store.registerModule("slider", SliderStore);
            }
        },

        created() {
            EventBus.on(EVENTS.SLIDER_STATE_IS_ACTIVE, () => {
                this.isActiveSlider = true;
            });
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../../node_modules/fullpage.js/dist/fullpage.min.css";
</style>
