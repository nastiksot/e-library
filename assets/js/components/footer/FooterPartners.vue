<template>
    <div class="footer-partners">
        <div class="centered" v-if="ready">
            <h2 class="footer-partners--title">
                <translated-text code="FOOTER.PARTNERS.TITLE" />
            </h2>

            <div class="footer-partners--block" :class="{open: showOthers}">
                <div class="footer-partners--list">
                    <div v-for="partner in partnersTop" class="footer-partners--item">
                        <img :src="image(partner)" :alt="partner.title">
                    </div>
                </div>

                <div class="footer-partners--hidden">
                    <div class="footer-partners--list more">
                        <div v-for="partner in partnersOther" class="footer-partners--item">
                            <img :src="image(partner)" :alt="partner.title">
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="https://www.somfy.de/ueber-somfy/so-open-technologien/tahoma-partner" target="_blank" class="v-btn v-btn--has-bg theme--light v-size--default orange">
                            <translated-text code="FOOTER.PARTNERS.INFO" />
                        </a>
                    </div>
                </div>

                <div v-if="partnersOther.length > 0" class="footer-partners--more">
                    <a @click.prevent="toggleOthers" href="#" class="footer-partners--more-link">
                        <i class="chevron-down-icon"></i>

                        <span v-if="showOthers" class="less">
                            <translated-text code="FOOTER.PARTNERS.LESS" />
                        </span>
                        <span v-else class="more">
                            <translated-text code="FOOTER.PARTNERS.MORE" />
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import {mapActions, mapState} from 'vuex';
    import FooterStore from './store';
    import TranslatedText from '../translated-text/TranslatedText';
    import Partner from '../../models/partner';
    import RouteGenerator from '../../modules/RouteGenerator';

    export default {
        name: 'FooterPartners',

        props: {
            limit: {
                type: Number,
                default: 8,
            },
        },

        components: {
            TranslatedText,
        },

        data() {
            return {
                showOthers: false,
            };
        },

        computed: {
            ...mapState('footer', {
                ready: state => state.ready,
                partners: state => state.partners,
            }),

            partnersTop() {
                return this.partners.slice(0, this.limit);
            },

            partnersOther() {
                return this.partners.slice(this.limit, this.partners.length);
            },
        },

        watch: {},

        methods: {
            ...mapActions('footer', [
                'loadPartners',
            ]),

            image(partner: Partner) {
                return RouteGenerator.generate('web.image', {
                    type: 'partner',
                    crop: 'resize',
                    size: '130x35',
                    name: partner.image ?? 'default.jpg',
                });
            },

            toggleOthers() {
                this.showOthers = !this.showOthers;
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule('footer')) {
                this.$store.registerModule('footer', FooterStore);
            }
        },

        created() {
            this.loadPartners();
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .footer-partners {
        background: #f8f7f5;
        padding-top: 55px;
        padding-bottom: 30px;
        margin-bottom: 55px;

        &--title {
            text-align: center;
            color: #4a4a4a;
            @include normal-font(28px, 35px);
            font-family: $fontSomfySansLight;
            position: relative;
            margin-bottom: 40px;

            &-link {
                font-family: $fontSomfySansRegular;
                color: #8996a4 !important;
                font-size: 14px;
                position: absolute;
                right: 0;
                text-decoration: underline;

                &:hover {
                    text-decoration: none;
                }
            }
        }

        &--list {
            @include flexbox();
            @include justify-content(space-between);
            @include align-items(center);

            img {
                @include transition(filter .5s ease-in-out);
                -webkit-filter: grayscale(100%);
                filter: grayscale(100%);
            }

            &.more {
                max-width: 80%;
                margin: 40px auto;
            }

            @media (max-width: 991px) {
                margin-bottom: 0;

                &.more {
                    max-width: none;
                }
            }
        }

        &--hidden {
            max-height: 0;
            overflow: hidden;
            @include transition(max-height 0.2s ease-in-out);

            .v-btn {
                padding: 0 40px !important;

                &:hover {
                    text-decoration: none;
                }
            }
        }

        &--more {
            text-align: center;
            margin-top: 30px;

            &-link {
                font-size: 14px;
                color: #485c74 !important;
                @include transition(color 0.2s ease-in-out);
                @include flexbox();
                @include justify-content(center);
                @include align-items(center);

                i {
                    font-size: 8px;
                    margin-right: 5px;
                    @include transition(transform 0.2s ease-in-out);
                }

                .less {
                    //display: none;
                }

                &:hover {
                    color: #000 !important;
                    text-decoration: none;
                }
            }
        }

        &--block {
            &.open {
                .footer-partners--hidden {
                    max-height: initial;
                }

                .footer-partners--more-link {
                    i {
                        @include transform(rotate(180deg));

                        //.more {
                        //    display: none;
                        //}

                        //.less {
                        //    display: block;
                        //}
                    }
                }
            }
        }

        @media (max-width: 991px) {
            background: none;

            &--title {
                margin-bottom: 23px;
            }

            &--list {
                @include flex-wrap(wrap);
                @include justify-content(center);
            }

            &--item {
                margin: 10px;
            }
        }

        @media (max-width: 768px) {
            &--title {
                @include flexbox();
                @include justify-content(center);
                @include flex-direction(column);

                &-link {
                    position: static;
                    margin-top: 8px;
                }
            }
        }
    }
</style>
