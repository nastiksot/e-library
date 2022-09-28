<template>
    <div class="request-table-wrapper">
        <v-data-table
            v-model="checkedRequests"
            :headers="headers"
            :items="dealerRequests"
            :expanded.sync="expandedRequests"
            item-key="id"
            show-expand
            :options.sync="options"
            :server-items-length="totalRequestsQty"
            class="request-table"
            show-select
            :no-data-text="emptyRequestsListMsg"
            :item-class="rowClass"
            @input="onChangeCheckedRequests()"
        >
            <template v-slot:item.data-table-expand="{ item, expand, isExpanded }">
                <!-- hide default Expand icon -->
            </template>

            <template v-slot:item.contactName="{ item }">
                {{ item.contactName !== null ? item.contactName : unknownUserLabel }}
            </template>

            <template v-slot:item.message="{ item }">
                <div class="topic-text" @click="toggleExpandRequest(item)">
                    <span>{{ item.message }}</span>

                    <v-icon v-if="expandedRequests.includes(item)" class="chevron-up">chevron-down-icon</v-icon>
                    <v-icon v-else>chevron-down-icon</v-icon>
                </div>
            </template>

            <template v-slot:item.createdAt="{ item }">
                {{ dateTimeFormat(item.createdAt) }}
            </template>

            <template v-slot:item.updatedAt="{ item }">
                {{ dateTimeFormat(item.updatedAt) }}
            </template>

            <template v-slot:item.status="{ item }">
                <v-select
                    v-model="item.status"
                    :items="statuses"
                    item-text="title"
                    item-value="value"
                    persistent-hint
                    return-object
                    single-line
                    :hide-selected=true
                    :menu-props="menuProps"
                    @click="onClickStatusSelect(item)"
                    @change="onChangeRequestStatus(item)"
                >
                    <template #item="{item}">
                        <div class="v-list-item v-list-item--link theme--light">
                            <div class="v-list-item__content">
                                <div :class="item.value" class="v-list-item__title">{{ item.title }}</div>
                            </div>
                        </div>
                    </template>
                </v-select>
            </template>

            <template v-slot:item.actions="{ item }">
                <div class="actions-block">
                    <v-menu
                        offset-y
                        content-class="action-menu"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                v-bind="attrs"
                                v-on="on"
                            >
                                ...
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item v-if="!isArchivedRequestsPage">
                                <v-list-item-title>
                                    <v-btn
                                        @click="openDialog([item], 'archive')"
                                    >
                                        <v-icon>download-icon</v-icon>
                                        <translated-text code="DEALER_REQUEST.ACTION.ARCHIVE_REQUEST"></translated-text>
                                    </v-btn>
                                </v-list-item-title>
                            </v-list-item>

                            <v-list-item v-if="isArchivedRequestsPage">
                                <v-list-item-title>
                                    <v-btn
                                        @click="openDialog([item], 'restore')"
                                    >
                                        <v-icon>upload-icon</v-icon>
                                        <translated-text code="DEALER_REQUEST.ACTION.RESTORE_REQUEST"></translated-text>
                                    </v-btn>
                                </v-list-item-title>
                            </v-list-item>

                            <v-list-item>
                                <v-list-item-title>
                                    <v-btn
                                        @click="openDialog([item], 'delete')"
                                    >
                                        <v-icon>trash-icon</v-icon>
                                        <translated-text code="DEALER_REQUEST.ACTION.DELETE_REQUEST"></translated-text>
                                    </v-btn>
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </template>

            <template v-slot:expanded-item="{ headers, item }">
                <td class="expanded" :colspan="7">
                    <div class="full-message">
                        <p class="message">
                            {{ item.message }}
                        </p>

                        <div class="contacts">
                            <p v-if="item.phone">
                                <translated-text code="DEALER_REQUEST.PHONE"/>
                                :
                                <a :href="'tel:' + item.phone">{{ item.phone }}</a>
                            </p>
                            <p class="contacts__mail">
                                <translated-text code="DEALER_REQUEST.EMAIL"/>
                                :
                                <a v-if="item.email !== null" :href="'mailto:' + item.email">{{ item.email }}</a>
                                <span v-else>{{ unknownUserLabel }}</span>
                            </p>
                            <p v-if="item.address">
                                <translated-text code="DEALER_REQUEST.ADDRESS"/>
                                :
                                {{ item.address }}
                            </p>
                        </div>
                        <div v-if="item.wishListUid" class="conf-link" @click="openDealerRequestWishList(item)">
                            <v-btn>
                                <translated-text code="DEALER_REQUEST.LINK_TO_CONFIGURATION"></translated-text>
                            </v-btn>
                        </div>

                        <user-dealer-request-item-comment :dealer-request="item"/>
                    </div>
                </td>
            </template>

            <template v-slot:footer.prepend>
                <div class="multi-actions" v-if="checkedRequests.length > 0">
                    <v-btn v-if="!isArchivedRequestsPage" @click="openDialog(checkedRequests, 'archive')">
                        <v-icon>download-icon</v-icon>
                        <translated-text code="DEALER_REQUEST.ACTION.ARCHIVE_REQUESTS"></translated-text>
                    </v-btn>
                    <v-btn v-if="isArchivedRequestsPage" @click="openDialog(checkedRequests, 'restore')">
                        <v-icon>upload-icon</v-icon>
                        <translated-text code="DEALER_REQUEST.ACTION.RESTORE_REQUESTS"></translated-text>
                    </v-btn>
                    <v-btn class="red" @click="openDialog(checkedRequests, 'delete')">
                        <v-icon>trash-icon</v-icon>
                        <translated-text code="DEALER_REQUEST.ACTION.DELETE_REQUESTS"></translated-text>
                    </v-btn>
                </div>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import TranslatedText from "../translated-text/TranslatedText";
import UserDealerRequestItemComment from "./UserDealerRequestItemComment";
import DealerRequestModel from "../../models/dealer-request/index";
import RouterGenerator from '../../modules/RouteGenerator';

export default {
    name: "UserDealerRequestListTable",

    props: {
        dealerRequests: {
            type: Array,
            required: true,
        },

        totalRequestsQty: {
            type: Number,
            required: true,
        },

        tableListOptions: {
            type: Object,
            required: true,
        },

        statuses: {
            type: Array,
            required: true,
        },

        isArchivedRequestsPage: {
            type: Boolean,
            required: true,
        },
    },

    components: {
        TranslatedText,
        UserDealerRequestItemComment,
    },

    data() {
        return {
            options: this.tableListOptions,
            requestStatusBeforeChange: null,
            menuProps: {
                offsetY: true,
                contentClass: "status-menu"
            },
            checkedRequests: [],
            expandedRequests: [],
        };
    },

    computed: {
        ...mapGetters("dealerRequests", ["dateTimeFormat",]),

        headers() {
            return [
                {text: this.$tc('DEALER_REQUEST.CONTACT_NAME'), value: 'contactName',},
                {text: this.$tc('DEALER_REQUEST.TOPIC'), value: 'message'},
                {text: this.$tc('DEALER_REQUEST.DATE_RECEIVED'), value: 'createdAt'},
                {text: this.$tc('DEALER_REQUEST.DATE_OF_REVIEW'), value: 'updatedAt'},
                {text: this.$tc('DEALER_REQUEST.REQUEST_STATUS'), value: 'status'},
                {text: '', sortable: false, value: 'actions'},
            ];
        },

        emptyRequestsListMsg() {
            return this.$tc('DEALER_REQUEST.THERE_ARE_NO_REQUESTS');
        },

        unknownUserLabel() {
            return this.$tc('DEALER_REQUEST.UNKNOWN_USER');
        },
    },

    watch: {
        options: {
            handler(newVal, oldVal) {
                // console.info('      ');
                // console.info('change Options22');
                // console.info(JSON.stringify(newVal));
                // console.info(JSON.stringify(oldVal));

                if (JSON.stringify(newVal) === JSON.stringify(oldVal)) {
                    return;
                }

                this.$emit('on-change-list-table-options', newVal);
            },
            deep: true,
        },

        dealerRequests(newV, oldV) {
            if (JSON.stringify(newV) !== JSON.stringify(oldV)) {
                if (this.checkedRequests.length) {
                    // check that checkedRequests really exist in base this.dealerRequests
                    this.checkedRequests = this.checkedRequests.filter(x => this.dealerRequests.includes(x));
                }
            }
        },
    },

    methods: {
        rowClass(dealerRequest: DealerRequestModel) {
            return dealerRequest.status;
        },

        onClickStatusSelect(dealerRequest: DealerRequestModel) {
            this.requestStatusBeforeChange = dealerRequest.status;
        },

        onChangeRequestStatus(dealerRequest: DealerRequestModel) {
            this.$emit('on-change-request-status', {
                dealerRequest: dealerRequest,
                oldStatusKey: this.requestStatusBeforeChange,
            });
        },

        openDealerRequestWishList(dealerRequest: DealerRequestModel) {
            let url = RouterGenerator.generate('web.dealer.wishlist.details', {
                dealerSlug: dealerRequest.dealerSlug,
                wishListUid: dealerRequest.wishListUid,
            });
            window.open(url, '_blank').focus();
        },

        openDialog(dealerRequests: Array<DealerRequestModel>, actionOnRequest) {
            this.$emit('open-dialog', {
                dealerRequests: dealerRequests,
                actionOnRequest: actionOnRequest
            });
        },

        toggleExpandRequest(dealerRequest: DealerRequestModel) {
            let ind = this.expandedRequests.indexOf(dealerRequest);

            if (ind > -1) {
                this.expandedRequests.splice(ind, 1);
            } else {
                this.expandedRequests.push(dealerRequest);
            }
        },

        onChangeCheckedRequests() {
            let tables = document.getElementsByClassName('request-table-wrapper');

            if (!tables.length){
                return;
            }

            let theads = tables[0].getElementsByTagName('thead');

            if (!theads.length){
                return;
            }

            let trs = theads[0].getElementsByTagName('tr');

            if (!trs.length){
                return;
            }

            let trClassName = trs[0].className,
                checkedClass = 'v-data-table__selected';

            if (this.checkedRequests.length === this.dealerRequests.length) {
                // all checkboxes are checked => highlight "Select All" checkbox
                trClassName += ' ' + checkedClass;
            } else {
                trClassName = trClassName.replace(checkedClass, '');
            }

            trs[0].className = trClassName.trim();
        },
    },
};
</script>

<style scoped lang="scss">
@import "../../../scss/base";
@import "../../../scss/components/dialog";

.request-table-wrapper {

    &::v-deep {
        .request-table {

            table {
                padding: 0 5px;

                .v-data-table-header {
                    tr {
                        background: #F8F7F5;
                        th {
                            text-align: center !important;

                            &.sortable {
                                .v-icon__svg {
                                    display: none;
                                }

                                span:not(.v-icon) {
                                    position: relative;

                                    &:before {
                                        display: block;
                                        content: '';
                                        border-left: 3px solid transparent;
                                        border-right: 3px solid transparent;
                                        border-bottom: 4px solid #A3A6B4;
                                        position: absolute;
                                        top: -1px;
                                        left: -14px;
                                    }
                                    &:after {
                                        display: block;
                                        content: '';
                                        border-left: 3px solid transparent;
                                        border-right: 3px solid transparent;
                                        border-top: 4px solid #A3A6B4;
                                        opacity: 1;
                                        background: none;
                                        transform: inherit;
                                        border-radius: 0;
                                        width: auto;
                                        position: absolute;
                                        top: 10px;
                                        left: -14px;
                                    }
                                }

                                .v-icon {
                                    display: none;
                                }

                                &.asc {
                                    span:not(.v-icon) {
                                        &:after {
                                            border-top: 4px solid #606060;
                                        }
                                    }
                                }
                                &.desc{
                                    span:not(.v-icon) {
                                        &:before {
                                            border-bottom: 4px solid #606060;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    span {
                        @include normal-font(10px, 16px);
                        text-transform: uppercase;
                        font-family: $fontSomfySansMedium;
                        font-weight: 500;
                        color: #3C4F64;
                        letter-spacing: 0.1px;
                    }
                }

                tr {
                    box-shadow: 0 0 6px rgba(0, 0, 0, .1);
                    border-radius: 5px;
                    padding: 0 10px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 15px;

                    &:hover {
                        background: none !important;
                    }

                    &.v-data-table__empty-wrapper {
                        box-shadow: none;
                        display: flex;
                        justify-content: center;

                        td {
                            font-size: 16px;
                            font-family: $fontSomfySansLight;
                            color: #8996A4!important;
                        }
                    }

                    td, th {
                        border-bottom: none !important;
                        border-radius: 5px;
                        padding: 17px 10px;
                        height: 100%;

                        &:nth-child(2) {
                            flex: 0 0 5%;
                        }

                        &:nth-child(3) {
                            flex: 0 0 15%;
                        }

                        &:nth-child(4) {
                            flex: 0 0 25%;
                        }

                        &:nth-child(5) {
                            flex: 0 0 15%;
                        }

                        &:nth-child(6) {
                            flex: 0 0 15%;
                        }

                        &:nth-child(7) {
                            flex: 0 0 20%;
                        }

                        &:nth-child(8) {
                            flex: 0 0 5%;
                        }

                        &.text-start {
                            &:first-child {
                                display: none;
                            }
                        }
                    }

                    &.closed, &.v-data-table__empty-wrapper {
                        td {
                            color: #A2ABB6;
                        }
                    }

                    .v-data-table__checkbox {
                        .v-input--selection-controls__input {
                            width: 20px;
                            height: 20px;
                            border: 1px solid #CED4DA;
                            border-radius: 4px;
                        }
                        span {
                           display: none;
                        }
                    }

                    .v-data-table__checkbox {
                        .v-input--selection-controls__input {
                            .v-input--selection-controls__ripple {
                                left: -15px;
                            }
                        }
                    }

                    &.v-data-table__selected {
                        .v-data-table__checkbox {
                            .v-input--selection-controls__input {
                                border-color: #FCAC22;
                                background: #FCAC22;
                                position: relative;
                                margin: 0;

                                &:before {
                                    content: "";
                                    display: block;
                                    height: 2px;
                                    width: 8px;
                                    background: #fff;
                                    transform: rotate(45deg);
                                    position: absolute;
                                    left: 1px;
                                    top: 10px
                                }

                                &:after {
                                    content: "";
                                    height: 2px;
                                    width: 14px;
                                    background: #fff;
                                    transform: rotate(-52deg);
                                    position: absolute;
                                    right: -1px;
                                    top: 8px;
                                }
                            }
                        }
                    }

                    &.v-data-table__expanded__row {
                        margin-bottom: 0;
                        box-shadow: 0 -3px 6px rgba(0, 0, 0, .1);
                        border-radius: 5px 5px 0 0;
                    }

                    &.v-data-table__expanded__content {
                        box-shadow: 0 3px 6px rgba(0, 0, 0, .1);
                        border-radius: 0 0 5px 5px;
                        margin-top: -12px;

                        td {
                            flex: 0 0 100%;
                        }
                    }

                    &.new {
                        td {
                            .v-select {
                                .v-input__slot {
                                    .v-select__selection {
                                        &:before {
                                            background: #FCAC22;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    &.answered {
                        td {
                            .v-select {
                                .v-input__slot {
                                    .v-select__selection {
                                        &:before {
                                            background: #3C4F64;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    &.meeting_planned {
                        td {
                            .v-select {
                                .v-input__slot {
                                    .v-select__selection {
                                        &:before {
                                            background: #007BFF;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    &.closed {
                        td {
                            .v-select {
                                .v-input__slot {
                                    .v-select__selection {
                                        &:before {
                                            background: #9CBF59;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    td {
                        @include normal-font(12px, 18px);
                        color: #485C74;

                        .topic-text {
                            cursor: pointer;
                            display: flex;
                            align-items: center;

                            span {
                                display: block;
                                max-width: 195px;
                                @include normal-font(16px, 20px);
                                position: relative;
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;

                                @media all and (max-width: 1380px){
                                    max-width: 150px;
                                }

                                &:after {
                                    content: '...';
                                }
                            }

                            .v-icon {
                                color: #8996A4;
                                font-size: 8px;

                                &.chevron-up {
                                    transform: rotateX(180deg);
                                }
                            }
                        }

                        .v-input--checkbox {
                            .v-messages {
                                display: none;
                            }
                        }

                        .v-input {
                            padding: 0;
                            margin: 0;
                        }

                        .v-select {
                            max-width: 158px;

                            .v-input__slot {
                                margin: 0;
                                border: 1px solid #8996A4;
                                border-radius: 5px;

                                &:after,
                                &:before {
                                    display: none;
                                }

                                ~ .v-text-field__details {
                                    display: none;
                                }

                                .v-select__slot {
                                    padding: 2px 10px;

                                    .v-label {
                                        left: 8px !important;
                                        top: 8px;
                                        color: #4D4F5C;
                                        @include normal-font(13px, 18px);
                                        overflow: inherit;
                                    }
                                }

                                .v-select__selection {
                                    margin: 0;
                                    @include normal-font(13px, 18px);
                                    position: relative;
                                    padding-left: 20px;

                                    &:before {
                                        content: '';
                                        display: block;
                                        width: 10px;
                                        height: 10px;
                                        border-radius: 100%;
                                        position: absolute;
                                        top: 4px;
                                        left: 0;
                                    }
                                }

                                .v-select__selections {
                                    input {
                                        padding: 0;
                                    }
                                }
                            }
                        }
                    }
                }

                .full-message {
                    font-family: $fontSomfySansRegular;
                    padding: 0 16px 0 57px;

                    .message {
                        @include normal-font(16px, 20px);
                        color: #485C74;
                        margin-bottom: 14px;
                    }

                    .contacts {
                        p {
                            margin-bottom: 5px;
                            @include normal-font(14px, 19px);
                            color: #3C4F64;

                            &:last-child {
                                margin-bottom: 0;
                            }

                            a {
                                color: #FCAC22;
                            }
                        }

                        &__mail {
                            a {
                                text-decoration: underline;
                            }
                        }
                    }

                    .conf-link {
                        display: flex;
                        justify-content: flex-end;
                        margin-top: -10px;

                        .v-btn {
                            width: 100%;
                            max-width: 227px;
                            height: auto;
                            flex: 0 0 100%;
                            margin: 0;
                            box-shadow: none;
                            padding: 12px;
                            @include normal-font(16px, 21px);
                            color: #FCAC22;
                            background: #fff;
                            border: 1px solid #FCAC22;
                        }
                    }

                    .comments-wrapper {
                        margin-top: 20PX;
                    }
                    .conf-link ~ .comments-wrapper {
                        margin-top: 0;
                    }
                }
            }

            .actions-block {
                .v-btn {
                    padding: 0;
                    min-width: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 25px;
                    height: 25px;
                    border: 1px solid #8996A4;
                    box-shadow: none;

                    span {
                        color: #8996A4;
                        font-size: 30px;
                        transform: translateY(-9px) translateX(-1px);
                        letter-spacing: -1.1px;
                    }
                }
            }
        }

        .multi-actions {
            .v-btn {
                float: left;
                display: inline-block;
                margin: 0 10px 0 0;
                color: #8996a4;
                background: #fff;
                border: 1px solid #8996a4;

                &.red {
                    color: #F44336;
                    background: #fff !important;
                    border: 1px solid #F44336;
                }

                span {
                    font-size: 14px;
                }

                i {
                    font-size: 18px;
                }
            }
        }
    }
}
</style>
