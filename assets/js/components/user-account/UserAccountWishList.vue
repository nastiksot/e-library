<template>
    <div class="user-account-wish-list">

        <template v-if="!ready">
            <v-form @submit.prevent="" class="progress mt-10 mb-10">
                <v-progress-circular indeterminate color="orange" class="spinner" size="70"/>
                <v-row></v-row>
            </v-form>
        </template>

        <template v-else>

            <div :class="{'progress': busy}">

                <v-progress-circular v-if="busy" indeterminate color="orange" class="spinner" size="70"/>

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
                            <v-dialog v-model="dialogUpdate" max-width="500px">
                                <v-card class="modal-window">
                                    <v-card-title class="modal-title">
                                        <translated-text code="USER.WISHLISTS.UPDATE.TITLE"/>
                                    </v-card-title>

                                    <v-card-text>

                                        <v-alert v-if="invalid && globalError" type="error" dense text>
                                            <translated-text :code="globalError"/>
                                        </v-alert>

                                        <v-container :class="{'progress': busy}">
                                            <v-progress-circular v-if="busy" indeterminate color="orange"
                                                                 class="spinner" size="70"/>

                                            <v-text-field v-model="wishListName"
                                                          :label="wishListNameLabel"
                                                          :error="null !== errors.wishListName"
                                                          :error-messages="errors.wishListName"
                                                          hide-details="auto"
                                                          outlined
                                            />
                                        </v-container>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>

                                        <v-btn :disabled="!wishListName.trim().length" class="modal-btn" text
                                               @click="updateWishList">
                                            <translated-text code="USER.WISHLISTS.BUTTON.SAVE"/>
                                        </v-btn>

                                        <v-btn class="modal-btn modal-btn_close" text @click="closeUpdateDialog">
                                            <translated-text code="USER.WISHLISTS.BUTTON.CANCEL"/>
                                        </v-btn>

                                        <v-spacer></v-spacer>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>

                            <v-dialog v-model="dialogDelete" max-width="500px">
                                <v-card class="modal-window">
                                    <v-card-title class="modal-title">
                                        <translated-text code="USER.WISHLISTS.DELETE.TITLE"/>
                                    </v-card-title>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>

                                        <v-btn class="modal-btn" text @click="deleteConfirmed">
                                            <translated-text code="USER.WISHLISTS.BUTTON.DELETE"/>
                                        </v-btn>

                                        <v-btn class="modal-btn modal-btn_close" text @click="closeDelete">
                                            <translated-text code="USER.WISHLISTS.BUTTON.CANCEL"/>
                                        </v-btn>

                                        <v-spacer></v-spacer>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>
                    </template>

                    <template v-slot:no-data>
                        <translated-text code="USER.WISHLISTS.NO_DATA"/>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <v-btn class="wish-list_btn" @click="clickWishListUpdate(item)" small>
                            <v-icon>edit-icon</v-icon>
                        </v-btn>

                        <v-btn class="wish-list_btn" @click="copyWishListLink(item)" small>
                            <v-icon>copy-icon</v-icon>
                        </v-btn>

                        <v-btn class="wish-list_btn" @click="clickWishListDetails(item)" small>
                            <v-icon>external-link-icon</v-icon>
                        </v-btn>

                        <v-btn class="wish-list_btn wish-list_btn-delete" @click="clickWishListDelete(item)" small>
                            <v-icon>close-icon</v-icon>
                        </v-btn>
                    </template>

                </v-data-table>

            </div>
        </template>

    </div>
</template>

<script>
import {mapActions, mapState, mapGetters} from "vuex";
import UserAccountStore from "./store";
import TranslatedText from "../translated-text/TranslatedText";
import TranslatedLink from "../translated-text/TranslatedLink";
import WishList from "../../models/wish-list";

export default {
    name: "UserAccountWishList",

    props: {},

    components: {TranslatedLink, TranslatedText},

    data() {
        return {
            dialogDelete: false,
            dialogUpdate: false,
            /** @var {wishList|undefined} */
            wishList: null,
            wishListName: '',
        };
    },

    computed: {
        ...mapState("userAccountStore", {
            busy: state => state.busy,
            ready: state => state.ready,
            invalid: state => state.invalid,
            doneUpdateWishList: state => state.doneUpdateWishList,
            globalError: state => state.globalError,
            commonFieldErrors: state => state.commonFieldErrors,
            wishLists: state => state.wishLists,
        }),
        ...mapGetters("userAccountStore", ["wishListUrl"]),

        headers() {
            return [
                {value: 'name', sortable: true, text: this.wishListNameLabel},
                //{value: 'createdAt', sortable: true, text: this.$tc('USER.WISHLISTS.LABEL.CREATED_AT')},
                {value: 'updatedAt', sortable: true, text: this.$tc('USER.WISHLISTS.LABEL.UPDATED_AT')},
                {value: 'actions', sortable: false, text: this.$tc('USER.WISHLISTS.ACTIONS')},
            ];
        },

        wishListNameLabel() {
            return this.$tc('USER.WISHLISTS.LABEL.NAME');
        },

        items() {
            return this.wishLists;
        },

        errors() {
            return {
                wishListName: this.commonFieldErrors.hasOwnProperty('wishListName') ? this.commonFieldErrors.wishListName : null,
            }
        },
    },

    watch: {
        dialogDelete(val) {
            val || this.closeDelete()
        },

        dialogUpdate(val) {
            val || this.closeUpdateDialog()
        },
    },

    methods: {
        ...mapActions("userAccountStore", [
            "loadWishLists",
            "wishListDetails",
            "wishListDelete",
            "updateUserWishList",
        ]),

        clickWishListUpdate(wishList: WishList) {
            // open dialog to update WishList
            this.wishList     = wishList;
            this.wishListName = this.wishList.name;
            this.dialogUpdate = true;
        },

        closeUpdateDialog() {
            this.wishList     = null;
            this.wishListName = '';
            this.dialogUpdate = false;
        },

        async updateWishList() {
            await this.updateUserWishList({
                wishListUid: this.wishList.uid,
                wishListName: this.wishListName.trim(),
            });

            if (this.doneUpdateWishList) {
                this.closeUpdateDialog();

                this.loadWishLists();
            }
        },

        copyWishListLink(wishList) {
            let fullUrl = window.location.origin + this.wishListUrl(wishList.uid);

            if (!navigator.clipboard) {
                document.addEventListener('copy', function(e) {
                    e.clipboardData.setData('text/plain', fullUrl);
                    e.preventDefault();
                }, true);

                document.execCommand('copy');
                return;
            }

            navigator.clipboard.writeText(fullUrl).then(function() {
                // console.log('Async: Copying to clipboard was successful!');
            }, function(err) {
                console.error('Async: Could not copy text: ', err);
            });
        },

        clickWishListDetails(wishList: WishList) {
            this.wishListDetails(wishList.uid);
        },

        clickWishListDelete(wishList: WishList) {
            // open confirm dialog
            this.dialogDelete = true;
            this.wishList = wishList;
        },

        closeDelete() {
            this.dialogDelete = false
            this.wishList = null;
        },

        deleteConfirmed() {
            this.wishListDelete(this.wishList.id);
            this.closeDelete();
        },
    },

    beforeCreate() {
        if (!this.$store.hasModule("userAccountStore")) {
            this.$store.registerModule("userAccountStore", UserAccountStore);
        }
    },

    created() {
        this.loadWishLists();
    }
};
</script>

<style scoped lang="scss">

@import "../../../scss/base";

.user-account-wish-list {

    &::v-deep {
        .v-data-table {

            tbody tr td,
            .v-data-table-header tr th {
                color: #3c4f64;
                font-family: $fontSomfySansRegular;
            }

            .v-data-table-header tr th {
                font-size: 22px;
                font-weight: 100;
            }
        }

        .wish-list_btn {
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

.modal-window {
    padding: 20px;

    .modal-title {
        font-size: 21px;
        justify-content: center;
        padding: 0;
        margin-bottom: 15px;
        color: #3c4f64;
    }

    .modal-btn {
        transition: color 0.2s ease-in-out, border 0.2s ease-in-out, background 0.2s ease-in-out;
        padding: 10px 30px;
        font-size: 14px;
        line-height: 22px;
        border: 1px solid #fcac22;
        border-radius: 4px;
        color: #fcac22 !important;
        background: none;
        display: flex;
        justify-content: center;
        font-family: $fontSomfySansRegular;

        &.v-btn--disabled {
            color: #fcac22 !important;
            opacity: 0.5;
        }

        &:hover {
            background: #fcac22;
            color: #fff !important;
        }

        &_close {
            background: #fcac22;
            color: #fff !important;

            &:hover {
                background: transparent;
                color: #fcac22 !important;
            }
        }
    }
}
</style>
