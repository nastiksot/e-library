<template>
    <div class="share">
        <v-dialog
            v-model="dialog"
            width="320"
            overlay-color="#000"
            overlay-opacity="0.65"
            content-class="share-dialog"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    outlined
                    color="orange"
                    v-bind="attrs"
                    v-on="on"
                    class="share__btn"
                    :disabled="disabled"
                >
                    <v-icon>share-icon</v-icon>
                    <span class="v-btn__text"><translated-text code="NAVIGATION.SHARE"/></span>
                </v-btn>
            </template>

            <v-card>
                <v-card-title>
                    Teilen

                    <v-btn icon small class="v-dialog__close" @click="dialog = false">
                        <v-icon>close-icon</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text>
                    <h3 class="v-card__subtitle">
                        <translated-text code="WISHLIST.SHARE.TITLE"/>
                    </h3>

                    <a :href="wishListLink" class="v-card__link">
                        {{ wishListLink }}
                    </a>
                    <v-btn @click="copyWishListLinkToClipboard" outlined block large color="orange" class="mb-4">
                        <translated-text code="WISHLIST.SHARE.COPY"/>
                    </v-btn>
                    <!--<div class="social">
                        <h3 class="social__title">
                            <translated-text code="WISHLIST.SHARE.SOCIAL"/>
                        </h3>
                        <ul class="social__list">
                            <li>
                                <a href="javascript:void(0);">
                                    <v-icon>email-icon</v-icon>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <v-icon>whatssapp-icon</v-icon>
                                </a>
                            </li>
                             <li>
                                 <a href="javascript:void(0);">
                                     <v-icon>pin-icon</v-icon>
                                 </a>
                             </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <v-icon>fb-icon</v-icon>
                                </a>
                            </li>
                             <li>
                                 <a href="javascript:void(0);">
                                     <v-icon>tw-icon</v-icon>
                                 </a>
                             </li>
                        </ul>
                    </div>-->
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import WishListStore from "../../components/wish-list/store";
    import TranslatedText from "../translated-text/TranslatedText";
    import RouterGenerator from "../../modules/RouteGenerator";

    export default {
        name: "Share",

        props: {},

        components: {TranslatedText},

        data() {
            return {
                dialog: false
            };
        },

        computed: {
            ...mapState("global", ["me"]),
            ...mapState("wishList", ["wishList"]),

            disabled() {
                return Object.keys(this.wishList).length === 0 || this.wishList.isEmptyProductSets;
            },

            wishListLink() {
                return Object.keys(this.me).length > 0
                    ? RouterGenerator.generate('web.wishlist.details', {wishListUid: this.me.uid}, true)
                    : null;
            },
        },

        watch: {},

        methods: {
            copyWishListLinkToClipboard() {
                navigator.clipboard.writeText(this.wishListLink);

                this.tcEventClickOnCopyWishListLink();
            },

            tcEventClickOnCopyWishListLink() {
                if (typeof (tc_events_global) === 'undefined') {
                    return;
                }

                try {
                    tc_events_global(this, 'share_SmartHomePlanner', {
                        'evt_category': 'Share_click',
                        'evt_button_action': 'copylink_click',
                        'evt_button_label': this.wishListLink,
                    });
                } catch (error) {
                    console.error(error);
                }
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";
    @import "../../../scss/components/dialog";

    .social {
        &__title {
            @include normal-font(16px, 20px);
            font-family: $fontSomfySansLight;
            text-align: center;
            margin-bottom: 30px;
        }

        &__list {
            @extend .list-reset;
            @include flexbox();
            @include align-items(center);
            @include justify-content(center);

            li {
                margin: 0 15px;

                a {
                    .v-icon {
                        color: #8996a4 !important;

                        &:hover {
                            color: #5f6d7d !important;
                            @include transition(color 0.2s ease-in-out);
                        }
                    }

                    &:hover {
                        text-decoration: none;
                    }
                }
            }
        }
    }

    .share {
        &__btn {
            &[disabled] {
                border-color: #CED4DA;

                .v-btn__text {
                    color: #CED4DA;
                }
            }

            .v-icon {
                font-size: 20px;
            }
        }

        @media (max-width: 1502px) {
            .v-btn {
                min-width: 44px;
                padding: 0;

                .v-icon {
                    margin-right: 0;
                }

                &__text {
                    display: none;
                }
            }
        }
    }
</style>
