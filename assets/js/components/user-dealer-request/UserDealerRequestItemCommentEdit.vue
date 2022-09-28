<template>
    <div class="edited-comment">
        <p v-if="isNewComment">
            <translated-text code="DEALER_REQUEST.EXPERT_COMMENT.TITLE"></translated-text>
        </p>

        <textarea v-model='commentText'
                  :placeholder="expertCommentPlaceholder"
                  :class="{'filled' : !isNewComment || commentText.length > 0}">
        </textarea>

        <div class="actions" v-if="commentText.length > 0">
            <span class="save" @click="saveComment()">
                <translated-text code="DEALER_REQUEST.EXPERT_COMMENT.SAVE"></translated-text>
            </span>

            <span class="cancel" @click="resetComment()">
                <translated-text code="DEALER_REQUEST.EXPERT_COMMENT.CANCEL"></translated-text>
            </span>
        </div>
    </div>
</template>

<script>
import {EventBus, events as EVENTS} from "../../modules/EventBus";
import TranslatedText from "../translated-text/TranslatedText";
import DealerRequestModel from "../../models/dealer-request/index";
import DealerRequestCommentModel from "../../models/dealer-request-comment";

export default {
    name: "UserDealerRequestItemCommentEdit",

    props: {
        dealerRequest: {
            type: DealerRequestModel,
            required: true,
        },

        comment: {
            type: DealerRequestCommentModel | null,
            required: false,
            default: null,
        },
    },

    components: {
        TranslatedText,
    },

    data() {
        return {
            commentText: '',
        };
    },

    computed: {
        isNewComment() {
            return this.comment === null;
        },

        expertCommentPlaceholder() {
            return this.isNewComment
                ? this.$tc('DEALER_REQUEST.EXPERT_COMMENT.PLACEHOLDER')
                : '';
        },
    },

    watch: {
        dealerRequest: {
            handler(newVal, oldVal) {
                // comments list was changed => reset commentText
                this.resetComment();
            },
            deep: true,
        },
    },

    methods: {
        saveComment() {
            EventBus.emit(EVENTS.USER_DEALER_REQUEST_COMMENT_SAVE, {
                dealerRequestId: this.dealerRequest.id,
                dealerRequestCommentId: this.isNewComment ? null : this.comment.id,
                commentText: this.commentText,
            });
        },

        resetComment() {
            this.commentText = '';

            if (!this.isNewComment) {
                this.$emit("finish-to-edit-comment", this.comment);
            }
        },
    },

    created() {
        if (!this.isNewComment) {
            this.commentText = this.comment.message;
        }
    },

};
</script>

<style scoped lang="scss">
@import "../../../scss/base";

.edited-comment {
    margin: 0 0 20px;

    p {
        @include normal-font(14px, 24px);
        margin-bottom: 0;
    }

    textarea {
        width: 100%;
        height: 48px;
        border: 1px solid #8996A4;
        padding: 10px 15px;
        @include normal-font(14px, 24px);
        color: #485C74;
        font-family: $fontSomfySansLight;
        border-radius: 5px;

        &.filled {
            height: auto;
            min-height: 40px;
        }

        &::-webkit-input-placeholder {
            color: #485C74;
        }

        &::-moz-placeholder {
            color: #485C74;
        }

        &:-moz-placeholder {
            color: #485C74;
        }

        &:-ms-input-placeholder {
            color: #485C74;
        }


        &:focus {
            border: 1px solid #8996A4;
        }
    }

    .actions {
        margin: 15px 0 0;

        & > span {
            display: inline-block;
            padding: 11px 32px 12px;
            border-radius: 5px;
            @include normal-font(16px, 20px);
            cursor: pointer;

            &.save {
                background: #3C4F64;
                color: #fff;
                margin: 0 6px 0 0;
            }

            &.cancel {
                color: #3C4F64;

                &:hover {
                    background: #CED4DA;
                }
            }
        }
    }
}
</style>
