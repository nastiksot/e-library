<template>
    <div class="wish-list-empty">
        <img src="../../../images/empty-list.svg" :alt="$tc('WISHLIST.EMPTY.TITLE')">
        <h5>
            <translated-text code="WISHLIST.EMPTY.TITLE"/>
        </h5>
        <p>
            <translated-text code="WISHLIST.EMPTY.DESCRIPTION" as-html/>
        </p>
        <div class="wish-list-empt_tip">
            <h6>
                <translated-text code="WISHLIST.EMPTY.REGISTER.TITLE"/>
            </h6>
            <p>
                <translated-link @click="clickRegister"
                                 :link="registerLink"
                                 prefix="WISHLIST.EMPTY.REGISTER.LINK_PREFIX"
                                 label="WISHLIST.EMPTY.REGISTER.LINK_LABEL"
                                 title="WISHLIST.EMPTY.REGISTER.LINK_TITLE"
                                 suffix="WISHLIST.EMPTY.REGISTER.LINK_SUFFIX"
                />
            </p>
        </div>

    </div>
</template>

<script>
    import TranslatedText from "../translated-text/TranslatedText";
    import TranslatedLink from "../translated-text/TranslatedLink";
    import RouteGenerator from "../../modules/RouteGenerator";

    export default {
        name: "WishListEmpty",

        props: {},

        components: {TranslatedLink, TranslatedText},

        data() {
            return {};
        },

        computed: {
            registerLink() {
                return RouteGenerator.generate('web.auth.register');
            }
        },

        watch: {},

        methods: {
            clickRegister() {
                window.location.href = this.registerLink;
            },
        },

    };
</script>

<style scoped lang="scss">

    @import "../../../scss/base";

    .wish-list-empty {
        text-align: center;

        img {
            display: block;
            margin: 0 auto 14px;
        }

        h5 {
            @include normal-font(18px, 27px);
            color: #8996A4;
            margin-bottom: 15px;
        }

        p {
            @include normal-font(16px, 26px);
            color: #8996A4;
            font-family: $fontSomfySansLight;
            margin-bottom: 48px;
        }

        .wish-list-empt_tip {
            border-radius: 5px;
            background: #CED4DA;
            padding: 7px 8px 14px;
            text-align: left;

            h6 {
                @include normal-font(16px, 21px);
                font-family: $fontSomfySansMedium;
                color: #1A1B1C;
                margin-bottom: 12px;
            }

            p {
                @include normal-font(14px, 18px);
                color: #343A40;
                font-family: $fontSomfySansLight;
                margin-bottom: 14px;

                &::v-deep {
                    a {
                        text-decoration: underline;
                        @include normal-font(14px, 18px);
                        color: #343A40;
                    }
                }
            }
        }
    }

</style>
