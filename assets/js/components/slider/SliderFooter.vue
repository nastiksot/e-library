<template>
    <div class="slider-footer section">
        <div class="slider-footer__actions fp-auto-height">
            <v-btn depressed large color="orange" :href="homePlannerUrl">
                <translated-text code="SLIDER.BUTTON.HOME_PLANER" />
            </v-btn>
            <!-- <save-configuration/> -->
        </div>
    </div>
</template>

<script>
    import Slider from '../../models/slider';
    import RouteGenerator from '../../modules/RouteGenerator';
    import TranslatedText from '../translated-text/TranslatedText';
    import SliderProductSet from './SliderProductSet';
    import SaveConfiguration from '../save-configuration/SaveConfiguration';

    export default {
        name: 'SliderFooter',

        components: {SaveConfiguration, SliderProductSet, TranslatedText},

        data() {
            return {};
        },

        computed: {
            isSlider() {
                return this.slide instanceof Slider;
            },

            homePlannerUrl() {
                return RouteGenerator.generate('web.home_planner');
            },
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .v-window-item {
        height: 350px;
        display: none;

        &.v-window-item--active {
            display: block;
        }
    }

    .slider-footer {
        position: relative;
        @include flexbox();
        padding-top: 74px;
        padding-bottom: 74px;
        max-height: 350px;

        &:before {
            content: "";
            height: 25%;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1;
            background: -moz-linear-gradient(top, rgba(0, 0, 0, 0.65) 0%, rgba(0, 0, 0, 0) 100%);
            background: -webkit-linear-gradient(top, rgba(0, 0, 0, 0.65) 0%, rgba(0, 0, 0, 0) 100%);
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.65) 0%, rgba(0, 0, 0, 0) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#a6000000", endColorstr="#00000000", GradientType=0);
        }

        &__actions {
            max-width: 300px;
            margin: auto;
            position: relative;
            z-index: 2;

            &::v-deep {
                .v-btn {
                    text-decoration: none;
                    width: 100%;

                    .save-icon {
                        margin-top: 0;
                    }
                }

                .save {
                    margin-top: 20px;
                }
            }
        }
    }
</style>
