<template>
    <div class="optional-product-header" :class="{active: show}">
        <div class="optional-product-header__title" v-if="!show">
            <translated-text code="PRODUCT.OPTIONAL_PRODUCTS.TITLE"/> ({{ optionalProductsQty }})
        </div>
    </div>
</template>

<script>
import TranslatedText from "../translated-text/TranslatedText";

export default {
    name: "ProductSetOptionalProductHeader",

    props: {
        optionalProductsQty: {
            type: Number,
            required: true
        },
    },

    components: {TranslatedText},

    data() {
        return {
            show: false
        };
    },
}
</script>

<style scoped lang="scss">
@import "../../../scss/base";

.optional-product-header{
    position: relative;
    margin: 17px 0 12px;
    color: #343a40;
    @include normal-font(16px, 21px);
    font-family: $fontSomfySansLight;

    &__btn{
        position: absolute;
        top: -6px;

        .v-icon{
            font-size: 20px;
            background: none!important;
            color: #B1BBC5!important;
        }
    }

    &__close{
        position: absolute;
        top: 0;
        right: 0;
        color: #8996A4!important;
    }
}
</style>
