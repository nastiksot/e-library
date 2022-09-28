<template>
    <v-dialog
        v-model="dialog.isActive"
        max-width="320"
    >
        <div class="request-modal">
            <v-btn
                @click="dialog.isActive = false"
                class="btn__close"
            >
                <v-icon>close-icon</v-icon>
            </v-btn>
            <v-card>
                <v-card-title>
                    <translated-text :code="title"/>
                </v-card-title>
                <v-card-text>
                    <translated-text :code="description" as-html/>
                </v-card-text>
                <v-card-actions>
                    <v-btn
                        @click="execAction"
                        class="btn__orange"
                    >
                        <translated-text :code="yes"/>
                    </v-btn>

                    <v-btn
                        @click="dialog.isActive = false"
                    >
                        <translated-text :code="no"/>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </div>
    </v-dialog>
</template>

<script>
import {EventBus, events as EVENTS} from "../../modules/EventBus";
import TranslatedText from "../translated-text/TranslatedText";

export default {
    name: "UserDealerRequest",

    props: {
        inputDialog: {
            type: Object,
            required: true,
        },
    },

    components: {
        TranslatedText,
    },

    data() {
        return {
            dialog: this.inputDialog,
            title: '',
            description: '',
            yes: '',
            no: '',
        };
    },

    computed: {
        isDeleteAction() {
            return this.dialog.action === 'delete';
        },

        isArchiveAction() {
            return this.dialog.action === 'archive';
        },

        isRestoreAction() {
            return this.dialog.action === 'restore';
        },
    },

    watch: {
        dialog: {
            handler(newVal, oldVal) {
                if (this.dialog.isActive && this.dialog.action) {
                    // display corresponding popup
                    this.initDataBasedAction();
                }

            },
            deep: true,
        },
    },

    methods: {
        initDataBasedAction() {
            let prefix = 'DEALER_REQUEST.',
                actionCode;

            if (this.isDeleteAction) {
                actionCode = 'DELETE_REQUEST';
            } else if (this.isArchiveAction) {
                actionCode = 'ARCHIVE_REQUEST';
            } else if (this.isRestoreAction) {
                actionCode = 'RESTORE_REQUEST';
            }

            if (actionCode) {
                if (this.dialog.dealerRequests.length > 1) {
                    actionCode += 'S';
                }

                this.title = prefix + actionCode + '.TITLE';
                this.description = prefix + actionCode + '.DESCRIPTION';
                this.yes = prefix + actionCode + '.YES';
                this.no = prefix + actionCode + '.NO';
            }
        },

        execAction() {
            let eventName,
                event = {
                    dealerRequests: this.dialog.dealerRequests,
                };

            if (this.isDeleteAction) {
                eventName = 'DELETE';
            } else {
                if (this.isArchiveAction) {
                    event.isArchived = true;
                } else if (this.isRestoreAction) {
                    event.isArchived = false;
                }

                if (event.isArchived !== undefined) {
                    eventName = 'UPDATE_ARCHIVED';
                }
            }

            if (eventName) {
                EventBus.emit(EVENTS['USER_DEALER_REQUEST_' + eventName], event);
            }
        },
    },
};
</script>

<style scoped lang="scss">
@import "../../../scss/base";
@import "../../../scss/components/dialog";

.request-modal {
    position: relative;

    .btn__close {
        height: auto !important;
        padding: 0;
        min-width: 0;
        box-shadow: none;
        color: #8996A4;
        background: transparent;
        border: none;
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 9;

        .v-icon {
            margin: 0;
            font-size: 20px;
        }
    }

    .v-card {
        padding: 20px 20px 20px 15px;

        .v-card__title {
            @include normal-font(28px, 32px);
            color: #485C74;
            font-family: $fontSomfySansLight;
            margin-bottom: 23px;
        }

        .v-card__text {
            @include normal-font(16px, 20px);
            text-align: center;
            color: #343A40;
            font-family: $fontSomfySansLight;
            margin-bottom: 28px;
        }

        .v-card__actions {
            flex-wrap: wrap;

            .v-btn {
                width: 100%;
                height: auto;
                flex: 0 0 100%;
                margin: 0 0 15px;
                box-shadow: none;
                padding: 12px;
                @include normal-font(16px, 21px);
                color: #FCAC22;
                background: #fff;
                border: 1px solid #FCAC22;

                &.btn__orange {
                    color: #fff;
                    background: #FCAC22;
                }
            }
        }
    }
}
</style>
