<template>
    <div class="comments-wrapper">
        <user-dealer-request-item-comment-edit
            :dealer-request="dealerRequest"/>

        <div v-for="(comment) in dealerRequest.comments"
             :key="comment.id"
             class="comment-item"
        >
            <user-dealer-request-item-comment-edit
                v-if="editedCommentIds.includes(comment.id)"
                :dealer-request="dealerRequest"
                :comment="comment"
                @finish-to-edit-comment="finishToEditComment"/>

            <template v-else>
                <div class="date">
                    {{ dateTimeFormat(comment.createdAt) }}

                    <div class="actions">
                        <span @click="editComment(comment)">
                            <translated-text code="DEALER_REQUEST.EXPERT_COMMENT.EDIT"></translated-text>
                        </span>

                        <span @click="deleteComment(comment)">
                            <translated-text code="DEALER_REQUEST.EXPERT_COMMENT.DELETE"></translated-text>
                        </span>
                    </div>
                </div>

                <div class="text">{{ comment.message }}</div>
            </template>

        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import {EventBus, events as EVENTS} from "../../modules/EventBus";
import TranslatedText from "../translated-text/TranslatedText";
import DealerRequestModel from "../../models/dealer-request/index";
import DealerRequestCommentModel from "../../models/dealer-request-comment";
import UserDealerRequestItemCommentEdit from "./UserDealerRequestItemCommentEdit";

export default {
    name: "UserDealerRequestItemComment",

    props: {
        dealerRequest: {
            type: DealerRequestModel,
            required: true,
        },
    },

    components: {
        TranslatedText,
        UserDealerRequestItemCommentEdit,
    },

    data() {
        return {
            editedCommentIds: [],
        };
    },

    computed: {
        ...mapGetters("dealerRequests", ["dateTimeFormat",]),
    },

    methods: {
        editComment(comment: DealerRequestCommentModel) {
            this.editedCommentIds.push(comment.id);
        },

        deleteComment(comment: DealerRequestCommentModel) {
            EventBus.emit(EVENTS.USER_DEALER_REQUEST_COMMENT_DELETE, {
                dealerRequestId: this.dealerRequest.id,
                dealerRequestCommentId: comment.id,
            });
        },

        finishToEditComment(comment: DealerRequestCommentModel) {
            let index = this.editedCommentIds.indexOf(comment.id);

            if (index !== -1) {
                this.editedCommentIds.splice(index, 1);
            }
        },
    },
};
</script>

<style scoped lang="scss">
@import "../../../scss/base";

.comments-wrapper {
    .comment-item {
        margin: 0;
        padding: 0 0 15px;

        .date {
            margin: 0 0 7px;
            color: #ccc;

            .actions {
                display: inline-block;
                margin: 0 0 0 5px;
                padding: 0 0 0 9px;
                border-left: 1px solid #ccc;

                & > span {
                    display: inline-block;
                    cursor: pointer;

                    &:first-child {
                        &:after {
                            content: '.';
                            margin: 0 5px;
                        }
                    }

                    &:hover {
                        text-decoration: underline;
                    }
                }
            }
        }

        .text {
            @include normal-font(14px, 19px);
            color: #3C4F64;
        }
    }
}
</style>
