<template>
    <div class="cart-item-info" :class="{active: show}">
        <v-btn icon @click="show = !show" class="cart-item-info__btn"><v-icon>info-icon</v-icon></v-btn>
        <div class="cart-item-info__content" v-if="show">
            <v-btn small class="cart-item-info__close" icon @click="show = !show"><v-icon>close-icon</v-icon></v-btn>
            <div class="cart-item-info__content--title">{{ item.title }}</div>
            <div class="cart-item-info__content--name">{{ item.name }}</div>
            <div class="cart-item-info__content--text">{{ item.description }}</div>
<!--            <a v-if="item.link" @click.prevent=""-->
<!--               :href="item.link"-->
<!--               class="cart-item-info__content&#45;&#45;link">-->
<!--                <translated-text code="LINK.READ_MORE.CART_ITEM"/>-->
<!--                <v-icon>external-link-icon</v-icon>-->
<!--            </a>-->
        </div>
    </div>
</template>

<script>
    import Product from "../../models/product";
    import TranslatedText from "../translated-text/TranslatedText";

    export default {
        name: "ProductSetProductInfo",

        props: {
            item: {
                type: Product,
                required: true
            },
        },

        components: {TranslatedText},

        data() {
            return {
                show: false
            };
        },

        computed: {},

        watch: {},

        methods: {}
    }
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .cart-item-info{
        &__btn{
            position: absolute;
            top: 5px;
            right: 5px;

            .v-icon{
                font-size: 20px;
                background: none!important;
                color: #B1BBC5!important;
            }
        }

        &__close{
            position: absolute;
            top: 10px;
            right: 10px;
            color: #8996A4!important;
        }

        &__content{
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 5px;
            background: #fff;
            z-index: 2;
            //padding: 20px;

            &--text,
            &--title{
                color: #343a40;
                font-family: $fontSomfySansLight;
                @include normal-font(12px, 16px);
            }

            &--name{
                color: #343a40;
                font-family: $fontSomfySansMedium;
                @include normal-font(14px, 21px);
                margin-bottom: 10px;
            }

            &--text{
                margin-bottom: 15px;
            }

            &--link{
                color: #8996a4;
                font-family: $fontSomfySansLight;
                font-size: 12px;
                text-decoration: underline;
                position: relative;
                padding-right: 20px;

                .v-icon{
                    font-size: 12px;
                    position: absolute;
                    right: 0;
                    top: 3px;
                }

                &:hover{
                    text-decoration: none;
                }
            }
        }

        &.active{
            .cart-item-info__content{
                position: static;
            }
        }
    }
</style>
