<template>
    <div class="cart-item-tip" :class="parentClass">
        <slot name="icon">
            <v-icon>{{ icon }}</v-icon>
        </slot>
<!--        <div class="cart-item-tip&#45;&#45;label">-->
<!--            <translated-text code="PRODUCT.TIP"/>-->
<!--        </div>-->
        <div class="cart-item-tip--text">
            <slot name="default">
                {{ tipText }}
            </slot>
        </div>
    </div>
</template>

<script>
    import TranslatedText from "../translated-text/TranslatedText";

    export default {
        name: "ProductSetProductTip",

        props: {
            icon: {
                type: String,
                required: false,
                default: 'lamp-icon'
            },

            text: {
                type: String,
                required: false,
                default: null
            },

            code: {
                type: String,
                required: false,
                default: null
            },

            params: {
                type: Object,
                required: false,
                default: () => {
                }
            },
        },

        components: {TranslatedText},

        data() {
            return {};
        },

        computed: {
            tipText() {
                if (this.text) {
                    return this.text;
                }

                return this.code ? this.$t(this.code, this.params) : null;
            },

            parentClass() {
                return this.icon === 'warning-icon' ? 'warning-tip' : null;
            },
        },

        watch: {},

        methods: {}
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .cart-item-tip {
        background: rgba(250, 184, 0, 0.22);
        padding: 8px;
        @include flexbox();
        @include align-items(flex-start);
        margin-top: 16px;

        &.warning-tip {
            background: #FFDDD0;
        }

        .v-icon {
            width: 21px;
            height: 21px;
            min-width: 21px;
            border-radius: 100%;
            background: #fab800;
            color: #fff;
            font-size: 16px;
            margin-right: 5px;

            &.warning-icon{
                background-color: #fab800!important;
                border-color: #fab800!important;
                &:before {
                    transform: translateY(-2px);
                }
            }
        }

        //&--label {
        //    color: #1a1b1c;
        //    font-size: 16px;
        //    font-family: $fontSomfySansMedium;
        //    margin-right: 5px;
        //}

        &--text {
            color: #343a40;
            font-family: $fontSomfySansLight;
            @include normal-font(14px, 20px);
        }
    }
</style>
