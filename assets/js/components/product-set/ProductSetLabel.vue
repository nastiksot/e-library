<template>
    <div
        class="product-set-label"
        :class="{ dark, inactive, expanded }"
        @click="onClick"
        @mouseover="$emit('mouseover')"
        @mouseleave="$emit('mouseleave')"
    >
        <div class="product-set-label__content">
            <v-icon>{{ icon }}</v-icon>
            <div v-if="text" class="product-set-label__text">
                {{ text }}
            </div>
        </div>
        <v-btn v-if="like" icon small>
            <v-icon>heart-icon</v-icon>
        </v-btn>
    </div>
</template>

<script>
    export default {
        name: "ProductSetLabel",

        props: {
            dark: Boolean,
            icon: String,
            text: String,
            like: Boolean,
            inactive: Boolean,
            expanded: Boolean,
        },

        components: {},

        data() {
            return {};
        },

        computed: {},

        watch: {},

        methods: {
            onClick() {
                this.$emit("click");
            }
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .product-set-label {
        background: #fcac22;
        color: #fff;
        @include normal-font(14px, 14px);
        display: inline-flex;
        @include align-items(center);
        position: relative;
        min-height: 38px;
        border-radius: 20px 20px 20px 0;

        .v-btn {
            position: absolute;
            right: -14px;
            top: -9px;

            .v-icon {
                font-size: 19px;
                color: #fcac22;
            }

            &::v-deep {
                .heart-icon {
                    color: #fcac22;
                }

                .v-btn__content {
                    .heart-icon {
                        &:before {
                            content: "\e918";
                            color: #ff6227;
                            text-shadow: -2px 0 #fcac27, 0 2px #fcac27, 2px 0 #fcac27, 0 -2px #fcac27;
                        }
                    }
                }
            }
        }

        &__content {
            padding: 10px;
            max-width: 38px;
            @include transition(max-width 0.2s ease-in-out);
            white-space: nowrap;
            overflow: hidden;
            @include flexbox();
            @include align-items(center);

            > .v-icon {
                color: #fff;
                font-size: 17px;
                margin-right: 10px;
                min-width: 18px;

                &.thermometer-icon {
                    font-size: 18px;

                    &:before {
                        width: 18px;
                        text-align: center;
                    }
                }

                &.solar-panel {
                    font-size: 15px;
                    margin-left: -3px;
                }

                &.sunrise-icon {
                    font-size: 15px;
                }

                &.fire-icon {
                    font-size: 19px;

                    &:before {
                        width: 19px;
                        text-align: center;
                    }
                }
            }
        }

        &.dark {
            background: #1a1b1c;

            .v-btn {
                .v-icon {
                    color: #1a1b1c;
                }

                &::v-deep {
                    .heart-icon {
                        color: #1a1b1c;
                    }

                    .v-btn__content {
                        .heart-icon {
                            &:before {
                                text-shadow: -2px 0 #1a1b1c, 0 2px #1a1b1c, 2px 0 #1a1b1c, 0 -2px #1a1b1c;
                            }
                        }
                    }
                }
            }
        }

        &.inactive {
            @include opacity(0.5);
        }

        &:hover, &.expanded {
            .product-set-label__content {
                max-width: 250px;
            }
        }
    }
</style>
