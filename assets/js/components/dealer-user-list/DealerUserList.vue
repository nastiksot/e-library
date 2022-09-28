<template>
    <div class="dealer-user-list">
        <template v-if="!ready">
            <v-form @submit.prevent="" class="progress mt-10 mb-10">
                <v-progress-circular indeterminate color="orange" class="spinner" size="70"/>
                <v-row></v-row>
            </v-form>
        </template>

        <template v-else>
            <div :class="{'progress': busy}">
                <v-progress-circular v-if="busy" indeterminate color="orange" class="spinner" size="70"/>

                <a :href="RouterGenerator.generate('web.account.dealer.user.create')">
                    <translated-text code="DEALER_USER.LIST.BUTTON.CREATE"/>
                </a>

                <v-data-table
                    :headers="headers"
                    :items="items"
                    :hide-default-footer="true"
                    :disable-pagination="true"
                    :footer-props="{disablePagination: true}"
                >
                    <template v-slot:top>
                        <v-toolbar
                            flat
                        >
                            <dealer-user-delete-dialog
                                :dialog-delete="dialogDelete"
                                @close-delete="closeDelete"
                                @delete-confirm="deleteConfirmed"
                            />
                        </v-toolbar>
                    </template>

                    <template v-slot:item.role="{ item }">
                        <translated-text :code="roleTitlePrefix + item.role"/>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <a :href="RouterGenerator.generate('web.account.dealer.user.get', {id: item.id})" class="edit__btn">
                            <v-icon>edit-icon</v-icon>
                        </a>

                        <v-btn class="dealer-user-list__btn" @click="clickDealerUserDelete(item)" small>
                            <v-icon>close-icon</v-icon>
                        </v-btn>
                    </template>
                </v-data-table>
            </div>
        </template>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import TranslatedText from "../translated-text/TranslatedText";
import TranslatedLink from "../translated-text/TranslatedLink";
import DealerUserListStore from "./store";
import DealerUserModel from "../../models/dealer-user";
import RouterGenerator from '../../modules/RouteGenerator';
import DealerUserDeleteDialog from '../dealer-user-account/DealerUserDeleteDialog';

export default {
    name: "DealerUserList",

    props: {},

    components: {TranslatedLink, TranslatedText, DealerUserDeleteDialog},

    data() {
        return {
            RouterGenerator,
            dialogDelete: false,
            /** @var DealerUserModel | null */
            dealerUser: null,
            roleTitlePrefix: 'DEALER_USER.ACCOUNT.ROLE.',
        };
    },

    computed: {
        ...mapState("global", ["me"]),
        ...mapState("dealerUserModule", {
            busy: state => state.busy,
            ready: state => state.ready,
            dealerUsers: state => state.dealerUsers,
        }),

        headers() {
            return [
                {value: 'firstName', sortable: true, text: this.$tc('DEALER_USER.ACCOUNT.FIRST_NAME')},
                {value: 'lastName', sortable: true, text: this.$tc('DEALER_USER.ACCOUNT.LAST_NAME')},
                {value: 'role', sortable: true, text: this.$tc('DEALER_USER.ACCOUNT.ROLE')},
                {value: 'active', sortable: true, text: this.$tc('DEALER_USER.ACCOUNT.ACTIVE')},
                {value: 'actions', sortable: false, text: this.$tc('DEALER_USER.LIST.ACTIONS')},
            ];
        },

        items() {
            return this.dealerUsers;
        },
    },

    watch: {
        dialogDelete(val) {
            val || this.closeDelete()
        },

        me(val) {
            if (val && val.dealerUid) {
                this.loadDealerUsers(val.dealerUid);
            }
        },
    },

    methods: {
        ...mapActions("dealerUserModule", [
            "loadDealerUsers",
            "dealerUserDelete",
        ]),

        clickDealerUserDelete(dealerUser: DealerUserModel) {
            // open confirm dialog
            this.dialogDelete = true;
            this.dealerUser   = dealerUser;
        },

        closeDelete() {
            this.dialogDelete = false
            this.dealerUser   = null;
        },

        deleteConfirmed() {
            this.dealerUserDelete({
                dealerUserId: this.dealerUser.id,
                me: this.me,
            });
        },
    },

    beforeCreate() {
        if (!this.$store.hasModule("dealerUserModule")) {
            this.$store.registerModule("dealerUserModule", DealerUserListStore);
        }
    },
};
</script>

<style scoped lang="scss">
@import "../../../scss/base";

.dealer-user-list {

    &::v-deep {
        .v-data-table {

            header {
                display: none;
            }

            tbody tr td,
            .v-data-table-header tr th {
                color: #3c4f64;
                font-family: $fontSomfySansRegular;
            }

            .v-data-table-header tr th {
                font-size: 22px;
                font-weight: 100;
                white-space: nowrap;
            }

            .edit__btn {
                i {
                    font-size: 19px;
                    color: #3c4f64;
                }
            }
        }

        .dealer-user-list__btn {
            background: transparent;
            box-shadow: none;
            color: #fcac22;
            height: auto;
            min-width: 0;
            padding: 0 5px;

            .v-icon {
                font-size: 20px;
            }

            i {
                margin: 0;
            }

            &-delete {
                .v-icon {
                    font-size: 30px;
                    transform: translateY(2px);
                }
            }
        }
    }
}
</style>
