<template>
    <div class="help">
        <v-btn icon class="help__btn"><v-icon>question-icon</v-icon></v-btn>
    </div>
</template>

<script>
    import "intro.js/minified/introjs.min.css";
    import introJs from 'intro.js/intro.js';

    export default {
        name: "Help",

        props: {},

        components: {},

        data() {
            return {};
        },

        computed: {},

        watch: {},

        methods: {},

        mounted() {

            setTimeout(() => {

                console.info('--since 10000');


            console.info(' ');
            console.info(' --222');
            console.info('document.querySelector(\'.product-filter\') = ', document.querySelector('.product-filter'));
            console.info('document.querySelector(\'.product-set-filter-button\') = ', document.querySelector('.product-set-filter-button'));
            console.info('document.querySelector(\'.home-planner__navigation\') = ', document.querySelector('.home-planner__navigation'));
            console.info('document.querySelector(\'.product-set-item\') = ', document.querySelector('.product-set-item'));

            introJs().setOptions({
                skipLabel: "Skip Tour",
                nextLabel: 'Next',
                prevLabel: 'Prev',
                hidePrev: true,
                hideNext: true,
                showBullets: false,
                steps: [
                    {
                        element: document.querySelector('.product-filter'),
                        title: '1-How to use the filter',
                        intro: 'You can filter smart scenarios by topics, products, and application areas.',
                        position: 'left',
                    },
                    {
                        element: document.querySelector('.product-set-filter-button .v-btn'),
                        title: '2-How to close the filter',
                        intro: 'You can close or open the filter section smart scenarios by clicking here.',
                        position: 'left',
                    },
                    {
                        element: document.querySelector('.home-planner__navigation'),// +2 стрелки (право/лево)
                        title: '3-How to change the house view',
                        intro: 'You can change the house view by clicking on each tab on top or two rotation icons on the sides.',
                        position: 'bottom',
                    },

                    /**** OPEN USE CASE ****/
                    {
                        element: document.querySelector('.product-set-item')[0],// +стрелка (на иконку ProductSet на карте) - БРАТЬ ТОЛЬКО ProductSet с DESICION TREE
                        title: '4-How to open a use case',
                        intro: 'You can open a use case in 2 ways:<br>' +
                            '1 - Hover your mouse on the use case you want to open in the right section and click on learn more.<br>' +
                            '2 - Click on the use case bubble in the configurator.',
                        position: 'left',
                    },
                    {
                        element: document.querySelector('.product-set-decision-tree'),// может и не біть!!
                        title: '5-How to personalize the product list',
                        intro: 'You can adapt products to your home by clicking on this button and completing the questionnaire.',
                        position: 'left',
                    },
                    {
                        element: document.querySelector('.cart-item-info__btn'),
                        title: '6-How to display more product information',
                        intro: 'You can find more information about each product by clicking on this Info icon.',
                        position: 'left',
                    },
                    {
                        element: document.querySelector('.save-btn'),
                        title: '7-How to add a use case to the wish list',
                        intro: 'By clicking on this button, you can add a use case to the wish list.<br>' +
                            'Because every home is different, there is an option to adapt the selection to your local conditions before you add it to your wish list.',
                        position: 'left',
                    },
                    /**** OPEN USE CASE ****/

                    {
                        element: document.querySelector('.save-btn'),
                        title: '7-How to add a use case to the wish list',
                        intro: 'By clicking on this button, you can add a use case to the wish list.<br>' +
                            'Because every home is different, there is an option to adapt the selection to your local conditions before you add it to your wish list.',
                        position: 'left',
                    },




                ]
            }).start();


            }, 20000);
        },
    }
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .help{
        position: fixed;
        right: 10px;
        bottom: 10px;
        z-index: 1112;

        &__btn{
            box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16);
            background: #fcac22;
            color: #fff!important;

            .v-icon{
                font-size: 18px!important;
            }
        }

        @media (max-width: 600px) {
            bottom: 75px;
        }
    }
</style>
