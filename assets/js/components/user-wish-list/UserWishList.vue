<template>
    <div class="user-wish-lists">

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
                            <v-dialog v-model="dialogDelete" max-width="500px">
                                <v-card>
                                    <v-card-title class="text-h5">
                                        <translated-text code="USER.WISHLISTS.DELETE.TITLE"/>
                                    </v-card-title>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>

                                        <v-btn color="blue darken-1" text @click="deleteConfirmed">
                                            <translated-text code="USER.WISHLISTS.BUTTON.DELETE"/>
                                        </v-btn>

                                        <v-btn color="blue darken-1" text @click="closeDelete">
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
                        <v-btn class="user-wish-list__btn" @click="clickWishListDetails(item)" small>
                            <v-icon>external-link-icon</v-icon>
                        </v-btn>

                        <v-btn class="user-wish-list__btn" @click="clickWishListDelete(item)" small>
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
    import UserWishListStore from "./store";
    import TranslatedText from "../translated-text/TranslatedText";
    import TranslatedLink from "../translated-text/TranslatedLink";
    import WishList from "../../models/wish-list";

    export default {
        name: "UserWishList",

        props: {},

        components: {TranslatedLink, TranslatedText},

        data() {
            return {
                dialogDelete: false,
                /** @var {wishList|undefined} */
                wishList: null,
            };
        },

        computed: {
            ...mapState("userWishList", {
                busy: state => state.busy,
                ready: state => state.ready,
                wishLists: state => state.wishLists,
            }),

            headers() {
                return [
                    //{value: 'createdAt', sortable: true, text: this.$tc('USER.WISHLISTS.LABEL.CREATED_AT')},
                    {value: 'updatedAt', sortable: true, text: this.$tc('USER.WISHLISTS.LABEL.UPDATED_AT')},
                    {value: 'actions', sortable: false, text: this.$tc('USER.WISHLISTS.ACTIONS')},
                ];
            },

            items() {
                return this.wishLists;
            },
        },

        watch: {
            dialogDelete(val) {
                val || this.closeDelete()
            },
        },

        methods: {
            ...mapActions("userWishList", [
                "loadWishLists",
                "wishListDetails",
                "wishListDelete",
            ]),

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
            if (!this.$store.hasModule("userWishList")) {
                this.$store.registerModule("userWishList", UserWishListStore);
            }
        },

        created() {
            this.loadWishLists();
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .user-wish-lists {
        &::v-deep {
            .user-wish-list__btn {
                background: none;
                height: auto;
                min-width: 0;
                padding: 0;
                color: #fcac22;
                font-size: 20px!important;
                box-shadow: none;
            }
        }
    }z
</style>
