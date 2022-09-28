<template>
    <div class="decision-tree">
        <template v-if="!ready">
            <v-form @submit.prevent="" class="progress mt-10 mb-10">
                <v-progress-circular indeterminate color="orange" class="spinner" size="70"/>
            </v-form>
        </template>

        <template v-else-if="done">
            <v-alert type="success" dense text>
                <translated-text code="DECISION_TREE.MESSAGE.SUCCESS"/>
            </v-alert>

            <slot name="done-content">
            </slot>

            <div class="decision-tree__actions">
                <v-btn v-if="!hideResetButton" :disabled="isShowFinalCloseAndResetButtons"
                       @click="clickReset"
                       outlined
                       block large depressed color="orange">
                    <translated-text code="DECISION_TREE.BUTTON.RESET"/>
                </v-btn>

                <v-btn :disabled="isShowFinalCloseAndResetButtons"
                       @click="clickClose"
                       outlined
                       block large depressed color="orange">
                    <translated-text code="DECISION_TREE.BUTTON.CLOSE"/>
                </v-btn>

            </div>
        </template>

        <template v-else>
            <v-alert v-if="invalid && globalError" type="error" dense text>
                <translated-text :code="globalError"/>
            </v-alert>

            <v-form @submit.prevent="submitDecisionFinal" :class="{'progress': busy}">
                <v-progress-circular v-if="busy" indeterminate color="grey" class="spinner" size="70"/>

                <v-expansion-panels
                    v-model="panel"
                    multiple
                >
                    <v-expansion-panel
                        v-for="(decision, decisionIndex) in decisionsList"
                        key="decision.id"
                        :key="decisionIndex"
                        :value="0"
                        ref="question"
                    >
                        <v-expansion-panel-header>
                            <div class="decision-tree__header">
                                {{ decisionIndex + 1 }}. {{ decision.question }}

                                <div class="decision-tree__text">
                                    {{ getAnswerTextByDecisionIndex(decisionIndex) }}
                                </div>
                            </div>

                        </v-expansion-panel-header>

                        <v-expansion-panel-content>
                            <v-radio-group
                                column
                                @change="onChooseDecision"
                            >
                                <v-radio v-for="(answer) in decision.answers" :key="answer.id"
                                         :label="answer.answer"
                                         :value="answer"
                                />
                            </v-radio-group>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>

                <div class="decision-tree__help" v-if="isShowNegativeMessage">
                    <div class="decision-tree__help--title">
                        <translated-text code="DECISION_TREE.MESSAGE.NEGATIVE"/>
                    </div>
                    <div class="decision-tree__tip">
                        <v-icon>lamp-icon</v-icon>
                        <div class="decision-tree__tip--text">
                            <translated-link
                                prefix="DECISION_TREE.MESSAGE.CHANGE_OR_CONTACT.PREFIX"
                                label="DECISION_TREE.MESSAGE.CHANGE_OR_CONTACT.LABEL"
                                suffix="DECISION_TREE.MESSAGE.CHANGE_OR_CONTACT.SUFFIX"
                                @click="openDealerContactForm"
                            />
                        </div>
                    </div>
                </div>

                <slot name="form-footer-content">
                </slot>

                <div class="decision-tree__actions" v-if="showSubmitButton || showResetButton">
                    <v-btn v-if="showSubmitButton" type="submit" :disabled="busy"
                           block large depressed color="orange">
                        <translated-text code="DECISION_TREE.BUTTON.SUBMIT"/>
                    </v-btn>

                    <v-btn v-if="showResetButton" :disabled="busy"
                           @click="clickReset"
                           outlined
                           block large depressed color="orange">
                        <translated-text code="DECISION_TREE.BUTTON.RESET"/>
                    </v-btn>
                </div>
            </v-form>

        </template>
    </div>
</template>

<script>
    import {mapActions, mapGetters, mapState} from "vuex";
    import DecisionTreeStore from "./store";
    import TranslatedText from "../translated-text/TranslatedText";
    import TranslatedLink from "../translated-text/TranslatedLink";
    import _filter from "lodash/filter";
    import DecisionAnswer from "../../models/decision-answer";
    import {DECISION_ACTION} from "../../dictionary/decision-action";

    export default {
        name: "DecisionTree",

        props: {
            isDone: {
                type: Boolean,
                default: false,
            },

            decisionId: {
                type: Number,
                required: true
            },

            hideResetButton: {
                type: Boolean,
                required: false,
                default: false
            },

            isActiveRequestToAddProductSetToWishList: {
                type: Boolean | null,
                required: false,
                default: false
            },
        },

        components: {TranslatedLink, TranslatedText},

        data() {
            return {};
        },

        computed: {
            ...mapState("global", ['isDealerMode',]),
            ...mapState("decisionTree", {
                done: state => state.done,
                busy: state => state.busy,
                invalid: state => state.invalid,
                errors: state => state.errors,
                globalError: state => state.globalError,
                ready: state => state.ready,
                // current decision
                decision: state => state.decision,
                // decisions list
                decisions: state => state.decisions,
            }),
            ...mapGetters("decisionTree", [
                "answers",
                "getDecisionById",
            ]),

            isShowFinalCloseAndResetButtons() {
                return this.busy || this.isActiveRequestToAddProductSetToWishList;
            },

            isShowNegativeMessage() {
                return this.decision.final && (!this.decision.positive || this.IsNotExistReplacedProductsInReplaceAction);
            },

            IsNotExistReplacedProductsInReplaceAction() {
                return DECISION_ACTION.REPLACE_MAIN === this.decision.action &&
                    this.decision.replacedProducts.length === 0;
            },

            showSubmitButton() {
                return this.decision.final &&
                    (this.decision.positive &&
                        (DECISION_ACTION.REPLACE_MAIN !== this.decision.action || !this.IsNotExistReplacedProductsInReplaceAction)
                    );
            },

            showResetButton() {
                return this.decisions.length > 1;
            },

            panel: {
                get() {
                    // open the first question
                    if (0 === this.answers.length) {
                        return [0];
                    }

                    // open the last answers on final step
                    // do not hide last answer (length -1)
                    if (this.decision.final) {
                        return [this.answers.length - 1];
                    }

                    // by default open the last
                    return [this.answers.length];

                    // open all
                    // return [...this.decisions.keys()].map((k, i) => i);
                },
                set(value) {
                },
            },

            getAnswerTextByDecisionIndex() {
                return (index: Number) => {
                    let answer = this.answers[index] ? this.answers[index] : null;
                    let decision = answer ? this.getDecisionById(answer.id) : null;

                    // do not show the answer text for final step
                    return decision && !decision.final && answer && answer.answer ? answer.answer : null;
                };
            },

            decisionsList() {
                // do not show the final step
                return this.decisions.length
                    ? _filter(this.decisions, {final: false})
                    : [];
            },

        },

        watch: {
            busy(val) {
                if (val === false && this.$refs.question && this.$refs.question.length > 0) {
                    this.$refs.question[this.$refs.question.length - 1].$el.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });
                }
            }
        },

        methods: {
            ...mapActions("decisionTree", [
                "setDone",
                'setIsDealerMode',
                "setStartDecision",
                "resetDecisions",
                "loadDecisionData",
            ]),

            clickReset() {
                this.resetDecisions(this.decisionId);
                this.$emit("reset");
            },

            clickClose() {
                this.$emit("close");
            },

            openDealerContactForm() {
                window.location.href = 'https://www.somfy.de/haendlersuche';
            },

            async onChooseDecision(answer: DecisionAnswer) {
                await this.loadDecisionData(answer);
                this.$emit("decision", this.decision);
            },

            submitDecisionFinal() {
                this.setDone(true);
                this.$emit("final", this.decision);
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("decisionTree")) {
                this.$store.registerModule("decisionTree", DecisionTreeStore);
            }
        },

        created() {
            this.setIsDealerMode(this.isDealerMode);
            this.setStartDecision(this.decisionId);
            this.setDone(this.isDone);
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .progress {
        min-height: 150px;

        &:after {
            display: none;
        }
    }

    .decision-tree {
        &__title {
            color: #485c74;
            text-align: center;
            @include normal-font(28px, 32px);
            font-family: $fontSomfySansLight;
            @include justify-content(center);
            margin-bottom: 20px;
        }

        &__text {
            color: #fcac22;
            @include normal-font(16px, 21px);
            font-family: $fontSomfySansMedium;
            padding-left: 17px;
            margin-top: 3px;
        }

        &__tip {
            background: rgba(250, 184, 0, 0.22);
            padding: 8px;
            @include flexbox();
            @include align-items(flex-start);

            .v-icon {
                width: 21px;
                height: 21px;
                border-radius: 100%;
                background: #fab800;
                color: #fff;
                font-size: 16px;
                margin-right: 5px;
                @include flex(none);
            }

            &--text {
                color: #343a40;
                font-family: $fontSomfySansLight;
                @include normal-font(14px, 20px);

                &::v-deep {
                    a {
                        font-weight: bold;
                    }
                }
            }

            span {
                display: inline;
            }

            a {
                text-decoration: underline;

                &:hover {
                    text-decoration: none;
                }
            }
        }

        &__help {
            padding: 0 30px;
            margin-top: 20px;

            &--title {
                text-align: center;
                margin-bottom: 16px;
            }

            @media (max-width: 767px) {
                padding-left: 0;
                padding-right: 0;
            }
        }

        &__actions {
            padding: 20px 30px;
            margin-top: 20px;
            border-top: 1px solid #e0e4e8;

            .v-btn {
                + .v-btn {
                    margin-top: 10px;
                }
            }

            @media (max-width: 767px) {
                padding-left: 0;
                padding-right: 0;
            }
        }

        &::v-deep {
            .v-expansion-panels {
                border-radius: 0;

                .v-input--selection-controls {
                    margin-top: 0;
                }
            }

            .v-expansion-panel {
                padding: 20px 30px;
                border-top: 1px solid #e0e4e8;
                border-radius: 0 !important;
                margin-top: 0 !important;

                .v-expansion-panel-header {
                    padding: 0;
                    min-height: 0;
                    color: #343a40;
                    @include normal-font(16px, 21px);
                    font-family: $fontSomfySansMedium;
                }

                .v-expansion-panel-content__wrap {
                    padding: 15px 0 0 0;
                }

                &:after,
                &:before {
                    display: none !important;
                }

                @media (max-width: 767px) {
                    padding-left: 0;
                    padding-right: 0;
                }
            }

            .v-input--radio-group__input {
                .v-radio {
                    border-radius: 5px;
                    border: 1px solid #fcac22;
                    padding: 13px 15px;
                    @include flex-direction(row-reverse);

                    .v-label {
                        color: #fcac22;
                        @include normal-font(16px, 21px);
                        font-family: $fontSomfySansMedium;
                    }

                    .v-input--selection-controls__input {
                        margin-right: 0;

                        .v-icon {
                            border-radius: 100%;
                            border: 1px solid #fcac22;

                            svg {
                                display: none;
                            }
                        }
                    }

                    &.v-item--active {
                        background: #fcac22;

                        .v-label {
                            color: #fff;
                        }

                        .v-input--selection-controls__input {
                            .v-icon {
                                background: #0dce60;
                                border-color: #0dce60;

                                &:before {
                                    content: "\e921";
                                    font-family: 'somfy' !important;
                                    font-size: 20px;
                                    color: #fff;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

</style>
