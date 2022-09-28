<template>
    <div class="user-dealer-request">
        <template v-if="!ready">
            <v-form @submit.prevent="" class="progress mt-10 mb-10">
                <v-progress-circular indeterminate color="orange" class="spinner" size="70"/>
                <v-row></v-row>
            </v-form>
        </template>

        <template v-else>
            <h2 class="user-dealer-request__title">
                <translated-text code="DEALER_REQUEST.TITLE"/>
            </h2>

            <v-alert v-if="globalError" type="error" dense text>
                <translated-text :code="globalError"/>
            </v-alert>

            <div :class="{'progress': busy}">
                <v-progress-circular v-if="busy" indeterminate color="orange" class="spinner" size="70"/>

                <user-dealer-request-filter
                    :status-filters="statuses"
                    :selected="activeStatusKeys"
                    :is-archived-requests-page="isArchivedRequestsPage"
                    @on-reset-status-filters="onResetStatusFilters"
                    @on-toggle-status-filter="onToggleStatusFilter"
                />

                <user-dealer-request-list-table
                    :dealer-requests="dealerRequests"
                    :statuses="statuses"
                    :is-archived-requests-page="isArchivedRequestsPage"
                    :table-list-options="tableListOptions"
                    :total-requests-qty="totalRequestsQty"
                    @on-change-list-table-options="changeTableListOptions"
                    @on-change-request-status="changeRequestStatus"
                    @open-dialog="openDialog"
                />

                <user-dealer-request-dialog
                    :input-dialog="dialog"
                />

            </div>
        </template>
    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import {EventBus, events as EVENTS} from "../../modules/EventBus";
    import TranslatedText from "../translated-text/TranslatedText";
    import UserDealerRequestFilter from "./UserDealerRequestFilter";
    import UserDealerRequestListTable from "./UserDealerRequestListTable";
    import UserDealerRequestDialog from "./UserDealerRequestDialog";
    import DealerRequestsStore from "./store";
    import DEALER_REQUEST_STATUS from "../../dictionary/dealer-request-status";
    import AlertStore from "../../components/alert/store";
    import RouterGenerator from '../../modules/RouteGenerator';

    export default {
        name: "UserDealerRequest",

        props: {
            isArchivedRequestsPage: {
                type: Boolean,
                required: true,
            },
        },

        components: {
            TranslatedText,
            UserDealerRequestFilter,
            UserDealerRequestListTable,
            UserDealerRequestDialog,
        },

        data() {
            return {
                isOptionsChangedBefore: false,
                dialog: {
                    isActive: false,
                    action: null,
                    dealerRequest: null,
                },
            };
        },

        computed: {
            ...mapState("global", ["me", 'currentLocale',]),
            ...mapState("dealerRequests", {
                doneDelete: state => state.doneDelete,
                doneArchive: state => state.doneArchive,
                busy: state => state.busy,
                ready: state => state.ready,
                globalError: state => state.globalError,
                dealerUid: state => state.dealerUid,
                dealerRequests: state => state.dealerRequests,
                totalRequestsQty: state => state.totalRequestsQty,
                activeStatusKeys: state => state.activeStatusKeys,
                tableListOptions: state => state.tableListOptions,
            }),

            statuses() {
                let res = [];

                for (let key in DEALER_REQUEST_STATUS) {
                    res.push({
                        value: DEALER_REQUEST_STATUS[key],
                        title: this.$tc('DEALER_REQUEST.STATUS.' + key),
                    });
                }

                return res;
            },
        },

        watch: {
            me() {
                if (this.me && this.me.dealerUid) {
                    this.setLang(this.currentLocale.split('_')[0]); // "en_GB" => "en"
                    this.setIsArchivedRequestsPage(this.isArchivedRequestsPage);
                    this.setDealerUid(this.me.dealerUid);
                }
            },

            activeStatusKeys() {
                if (!this.dealerUid) {
                    // required parameter `dealerUid` not initialized yet => exit
                    return false;
                }

                this.execLoadRequests();
            },

            tableListOptions: {
                handler (newVal, oldVal) {
                    // console.info('      ');
                    // console.info('watch: changed options in parent');
                    // console.info('new = ' + JSON.stringify(newVal));
                    // console.info('old = ' + JSON.stringify(oldVal));

                    if (JSON.stringify(newVal) === JSON.stringify(oldVal)) {
                        return;
                    }

                    if (!this.isOptionsChangedBefore) {
                        // Options have been changed by loading Requests on DealerUid initialization => ignore this change
                        this.isOptionsChangedBefore = true;
                        return;
                    }

                    this.execLoadRequests();
                },
                deep: true,
            },
        },

        methods: {
            ...mapActions("dealerRequests", [
                "setDealerUid",
                "setLang",
                "setIsArchivedRequestsPage",
                "loadRequests",
                "updateRequestStatus",
                "toggleStatusFilter",
                "resetStatusFilters",
                "setTableListOptions",
                "actionOnRequests",
                "saveComment",
                "deleteComment",
            ]),
            ...mapActions("alert", ["setNotification"]),

            /**
             * @param {String} statusKey
             */
            onToggleStatusFilter(statusKey) {
                this.toggleStatusFilter(statusKey);
            },

            onResetStatusFilters() {
                this.resetStatusFilters();
            },

            execLoadRequests() {
                this.loadRequests();
            },

            isSingleRequest(dealerRequests) {
                return dealerRequests.length === 1;
            },

            /**
             * @param {Array} dealerRequests
             */
            getDealerRequestIds(dealerRequests) {
                return dealerRequests.map(function(value) {
                    return value.id;
                });
            },

            /**
             * @param {Array} dealerRequests
             */
            async execDeleteRequest({dealerRequests}) {
                // close dialog
                this.dialog.isActive = false;
                let ids = this.getDealerRequestIds(dealerRequests);

                await this.actionOnRequests({
                    dealerRequestIds: ids,
                    action: 'delete',
                });

                if (this.doneDelete) {
                    let message = this.isSingleRequest(dealerRequests)
                        ? dealerRequests[0].contactName + this.$t('DEALER_REQUEST.DELETE_REQUEST.SUCCESS')
                        : this.$t('DEALER_REQUEST.DELETE_REQUESTS.SUCCESS');
                    this.finalActionsAfterRequestsChanged(message);
                }
            },

            finalActionsAfterRequestsChanged(message) {
                this.addNotification(message);
                window.scrollTo(0,0);
            },

            /**
             * @param {Array} dealerRequests
             * @param {Boolean} isArchived
             */
            async execUpdateArchivedRequest({dealerRequests, isArchived}) {
                // close dialog
                this.dialog.isActive = false;
                let ids = this.getDealerRequestIds(dealerRequests);

                await this.actionOnRequests({
                    dealerRequestIds: ids,
                    action: isArchived ? 'archive' : 'restore',
                });

                if (this.doneArchive) {
                    let prefix = '',
                        translationPrefix = 'DEALER_REQUEST.' + (isArchived ? 'ARCHIVE' : 'RESTORE') + '_REQUEST';

                    if (this.isSingleRequest(dealerRequests)) {
                        prefix += dealerRequests[0].contactName;
                    } else {
                        translationPrefix += 'S';
                    }

                    translationPrefix += '.SUCCESS.';
                    prefix += this.$t(translationPrefix + 'PREFIX');

                    let linkLabel = this.$t(translationPrefix + (isArchived ? 'ARCHIVED_' : '') + 'REQUESTS_LINK'),
                        linkUrl   = RouterGenerator.generate('web.account.dealer.' +  (isArchived ? 'archivedRequests' : 'requests')),
                        postfix   = this.$t(translationPrefix + 'POSTFIX');

                    let message = prefix + ' <a href="' + linkUrl + '">' + linkLabel + '</a>' + postfix;

                    this.finalActionsAfterRequestsChanged(message);
                }
            },

            addNotification(message) {
                message = '<v-icon class="round-check-icon"></v-icon>' + message;
                this.setNotification({'message': message, 'icon': 'check-icon'});
            },

            execSaveComment({dealerRequestId, commentText, dealerRequestCommentId}) {
                this.saveComment({
                    dealerRequestId: dealerRequestId,
                    dealerRequestCommentId: dealerRequestCommentId,
                    commentText: commentText,
                });
            },

            execDeleteComment({dealerRequestId, dealerRequestCommentId}) {
                this.deleteComment({
                    dealerRequestId: dealerRequestId,
                    dealerRequestCommentId: dealerRequestCommentId,
                });
            },

            changeTableListOptions(newOptions) {
                this.setTableListOptions(newOptions);
            },

            /**
             * @param {DealerRequest} dealerRequest
             * @param {String} oldStatusKey
             */
            changeRequestStatus({dealerRequest, oldStatusKey}) {
                this.updateRequestStatus({
                    dealerRequest: dealerRequest,
                    oldStatusKey: oldStatusKey,
                });
            },

            /**
             * @param {Array} dealerRequests
             * @param {String} actionOnRequest
             */
            openDialog({dealerRequests, actionOnRequest}) {
                this.dialog.isActive       = true;
                this.dialog.action         = actionOnRequest;
                this.dialog.dealerRequests = dealerRequests;
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("dealerRequests")) {
                this.$store.registerModule("dealerRequests", DealerRequestsStore);
            }

            if (!this.$store.hasModule("alert")) {
                this.$store.registerModule("alert", AlertStore);
            }
        },

        created() {
            EventBus.on(EVENTS.USER_DEALER_REQUEST_DELETE, this.execDeleteRequest);

            EventBus.on(EVENTS.USER_DEALER_REQUEST_UPDATE_ARCHIVED, this.execUpdateArchivedRequest);

            EventBus.on(EVENTS.USER_DEALER_REQUEST_COMMENT_SAVE, this.execSaveComment);

            EventBus.on(EVENTS.USER_DEALER_REQUEST_COMMENT_DELETE, this.execDeleteComment);
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .user-dealer-request {
        &__title {
            color: #485c74;
            text-align: left;
            @include normal-font(28px, 32px);
            font-family: $fontSomfySansLight;
            padding: 0;
            margin-bottom: 15spx;
        }
    }
</style>
