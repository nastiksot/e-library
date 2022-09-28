<template>
    <div class="product-set-filter-item">
        <h3 class="product-set-filter-item__title">{{ filterGroup.title }}</h3>
        <div class="product-set-filter-item__list">
            <v-chip link :class="{active: !selected.length}" @click="onAll">
                <translated-text code="FILTER.CHOICE_ALL" width="30px"></translated-text>
            </v-chip>

            <v-chip link v-for="filter in filterGroup.filters"
                    :key="filter.id"
                    @click="onClick(filter)"
                    :class="{active: selected.indexOf(filter.id) !== -1}">
                {{ filter.title }}
            </v-chip>
        </div>
    </div>
</template>

<script>
    import TranslatedText from "../translated-text/TranslatedText";
    import FilterGroup from "../../models/filter-group";

    export default {
        name: "ProductSetFilterItem",

        props: {
            filterGroup: {
                type: FilterGroup,
                required: true
            },

            isFirstFilterGroup: {
                type: Boolean,
                required: true
            },

            selected: {
                type: Array,
                required: false,
                default: () => []
            }
        },

        components: {TranslatedText},

        data() {
            return {};
        },

        computed: {
            allTitle() {
                return this.$tc('FILTER.CHOICE_ALL');
            },
        },

        watch: {},

        methods: {
            onAll(id: Number) {
                this.$emit('all');

                this.tcEventClickOnFilter(this.allTitle);
            },

            onClick(filter) {
                if (this.selected.indexOf(filter.id) === -1) {
                    // at the moment the filter isn't active
                    // but this click will activate it
                    this.tcEventClickOnFilter(filter.title);
                }

                this.$emit('toggle', filter.id);
            },

            tcEventClickOnFilter(filterTitle) {
                if (typeof (tc_events_global) !== 'undefined') {
                    let id, action;

                    if (this.isFirstFilterGroup) {
                        id = 'smartConfiguration_filterClick';
                        action = 'benefit_filterClick';
                    } else {
                        id = 'application_filterClick';
                        action = 'application_filterClick';
                    }

                    try {
                        tc_events_global(this, id, {
                            'evt_category': 'SmartHome_planner',
                            'evt_button_action': action,
                            'evt_button_label': filterTitle
                        });
                    } catch (error) {
                        console.error(error);
                    }
                }
            },
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .product-set-filter-item {
        &__title {
            @include normal-font(16px, 21px);
            margin-bottom: 15px;
        }

        .v-chip {
            background: none;
            border: 1px solid #ced4da;
            color: #3c4f64;
            font-size: 14px;
            margin-bottom: 8px;
            margin-right: 10px;

            &.active {
                background: #fcac22;
                border-color: #fcac22;
                color: #fff;
            }
        }
    }
</style>
