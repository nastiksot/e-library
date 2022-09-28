<template>
    <div class="filter">
        <p>
            <translated-text code="DEALER_REQUEST.FILTER_TITLE" width="30px"></translated-text>
        </p>

        <v-chip-group
            column
        >
            <v-chip link :class="{active: !selected.length}" @click="onResetClick">
                <translated-text code="FILTER.CHOICE_ALL" width="30px"></translated-text>
            </v-chip>

            <v-chip link v-for="(statusFilter) in statusFilters"
                    :key="statusFilter.value"
                    :class="{active: selected.indexOf(statusFilter.value) !== -1}"
                    @click="onStatusFilterClick(statusFilter.value)">
                {{ statusFilter.title }}
            </v-chip>

            <v-chip link
                    :class="{active: isArchivedRequestsPage}"
                    @click="goToArchivedRequests">
                <translated-text code="DEALER_REQUEST.ARCHIVE_REQUESTS_CATEGORY" width="30px"></translated-text>
            </v-chip>
        </v-chip-group>
    </div>
</template>

<script>
import TranslatedText from "../translated-text/TranslatedText";
import RouterGenerator from '../../modules/RouteGenerator';

export default {
    name: "UserDealerRequestFilter",

    components: {
        TranslatedText,
    },

    data() {
        return {};
    },

    props: {
        statusFilters: {
            type: Array,
            required: true,
        },

        selected: {
            type: Array,
            required: true,
        },

        isArchivedRequestsPage: {
            type: Boolean,
            required: true,
        },
    },

    computed: {},

    methods: {
        /**
         * @param {String} statusKey
         */
        onStatusFilterClick(statusKey) {
            this.$emit('on-toggle-status-filter', statusKey);
        },

        onResetClick() {
            this.$emit('on-reset-status-filters');
        },

        goToArchivedRequests() {
            window.location = RouterGenerator.generate('web.account.dealer.archivedRequests');
        }
    },
};
</script>

<style scoped lang="scss">
@import "../../../scss/base";

.filter {
    display: flex;
    align-items: center;
    font-family: $fontSomfySansRegular;
    color: #3C4F64;
    margin-bottom: 25px;

    p {
        @include normal-font(16px, 21px);
        margin: 0 5px 0 0;
    }

    .v-chip {
        padding: 10px;
        background: transparent;
        border: 1px solid #CED4DA;
        border-radius: 20px;
        font-size: 14px;
        color: #3C4F64;
        margin: 0 8px;

        &.active,
        &:hover {
            border-color: #FCAC22;
            background: #FCAC22;
            color: #fff;
        }
    }
}
</style>
