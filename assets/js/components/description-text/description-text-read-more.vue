<template>
    <div class="description-text">
        <div class="description-text__inner">
            <div class="description-text__text" v-html="text"></div>
<!--            <a v-if="!show" @click.prevent="show = !show" href="#" class="description-text__more">-->
<!--                <translated-text code="DESCRIPTION_TEXT.BUTTON.READ"/>-->
<!--            </a>-->
<!--            <a v-else @click.prevent="show = !show" href="#" class="description-text__more">-->
<!--                <translated-text code="DESCRIPTION_TEXT.BUTTON.LESS"/>-->
<!--            </a>-->
        </div>
    </div>
</template>

<script>

    import TranslatedText from "../translated-text/TranslatedText";

    export default {
        name: "DescriptionTextReadMore",

        props: {
            text: {
                type: String,
                required: false,
                default: null,
            }
        },

        components: {TranslatedText},

        data() {
            return {
                // show: false
            };
        },

        computed: {},

        watch: {},

        methods: {},
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .description-text {
        background: #ddd;
        border-radius: 5px;

        a {
            text-decoration: underline;

            &:hover {
                text-decoration: none;
            }
        }

        &__inner{
            padding: 20px;
            color: #343a40;
            @include normal-font(14px, 16px);
            border-bottom: 1px solid #ced4da;
            font-family: $fontSomfySansLight;
        }

        &__text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            //-webkit-line-clamp: 3;
            //-webkit-box-orient: vertical;

            //&.active {
            //    -webkit-line-clamp: 100;
            //}
        }

        &__more {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;

            &.active {
                -webkit-line-clamp: 100;
            }
        }
    }
</style>

