<template>
    <div class="cart-item-alternative">
        <h3 class="cart-item-alternative--title">
            <translated-text code="PRODUCT.ALTERNATIVE_PRODUCTS"/>
        </h3>

        <div v-for="(product, index) in products" :key="product.id" class="cart-item-alternative--item">
            <a @click.prevent="" href="#" class="cart-item-alternative--item-image">
                <img :src="images[index]" :alt="product.title">
            </a>
            <div class="cart-item-alternative--item-details">
                <a v-if="product.title" @click.prevent="" href="#"
                   class="cart-item-alternative--item-details-title">
                    {{ product.title }}
                </a>

                <a v-if="product.name" @click.prevent="" href="#"
                   class="cart-item-alternative--item-details-name">
                    {{ product.name }}
                </a>

                <div v-if="product.whereToBuy !== 'online_and_retail'" class="cart-item-alternative--item-details-where-to-buy">
                    <translated-text :code="`PRODUCT.AVAILABLE_${product.whereToBuy.toUpperCase()}`"/>
                </div>
                <!--                <div v-if="product.specialShop"-->
                <!--                     class="cart-item-alternative&#45;&#45;item-details-special-shop">-->
                <!--                    <translated-text code="PRODUCT.SPECIAL_SHOP"/>-->
                <!--                </div>-->
            </div>
        </div>
    </div>
</template>

<script>
    //import AlternativeProduct from "../../models/alternative-product";
    import Product from "../../models/product";
    import TranslatedText from "../translated-text/TranslatedText";
    import RouteGenerator from "../../modules/RouteGenerator";
    import _forEach from "lodash/forEach";

    export default {
        name: "ProductSetProductAlternative",

        props: {
            /** @type Array<Product> */
            products: {
                type: Array,
                required: true,
                validator: (value) => {
                    let isValid = true;
                    _forEach(value, (item) => {
                        // only Product instance allowed
                        if (!(item instanceof Product)) {
                            isValid = false;
                        }
                    });

                    return isValid;


                    // let isValid = true;
                    // _forEach(value, (item) => {
                    //
                    //     console.log('item: ', item);
                    //     console.log('item2: ', item instanceof AlternativeProduct);
                    //
                    //     // only AlternativeProduct instance allowed
                    //     if (!item instanceof AlternativeProduct) {
                    //         isValid = false;
                    //         return false;
                    //     }
                    // });
                    //
                    // return isValid;
                },
            }
        },

        components: {TranslatedText},

        data() {
            return {};
        },

        computed: {

            images() {
                return this.products.map((product) => {
                    return RouteGenerator.generate("web.image", {
                        type: "product",
                        crop: "fit",
                        size: "53x53",
                        name: product.image ?? "default.jpg"
                    });
                });
            },
        },

        watch: {},

        methods: {},

        created() {
            console.log('this.products: ', this.products);
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .cart-item-alternative {
        margin-top: 10px;

        &--title {
            color: #000;
            @include normal-font(16px, 21px);
            font-family: $fontSomfySansLight;
            margin-bottom: 12px;
        }

        &--item {
            @include flexbox();
            @include align-items(center);
            padding: 15px;
            background: #f3f5f8;

            &-image {
                @include flex(none);
                margin-right: 20px;
            }

            &-details {
                &-title {
                    color: #343a40;
                    font-family: $fontSomfySansLight;
                    @include normal-font(12px, 16px);
                }

                &-name {
                    color: #343a40;
                    font-family: $fontSomfySansMedium;
                    @include normal-font(14px, 17px);
                    padding-right: 10px;
                }

                &-where-to-buy {
                    @include normal-font(12px, 14px);
                    color: #007bff;
                }

                //&-special-shop {
                //    @include normal-font(12px, 14px);
                //    color: #007bff;
                //}
            }

            + .cart-item-alternative--item {
                margin-top: 10px;
            }
        }
    }
</style>
