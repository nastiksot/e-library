<template>
    <div class="app">
        <app-header active="account"/>

        <v-dialog
            v-model="dialog"
            width="550"
            overlay-color="#000"
            overlay-opacity="0.65"
            content-class="register-confirm-dialog"
            persistent
        >
            <v-card>
                <div>
                    <v-card-title>
                        <v-btn icon small class="v-dialog__close" @click="dialog = false">
                            <v-icon>close-icon</v-icon>
                        </v-btn>
                    </v-card-title>

                    <v-card-text>

                        <user-register-confirm :token="token"/>

                    </v-card-text>
                </div>

            </v-card>
        </v-dialog>

    </div>
</template>

<script>
    import {mapState, mapActions} from "vuex";
    import AppHeader from "../../components/app-header/AppHeader";
    import UserRegisterConfirm from "../../components/user-register-confirm/UserRegisterConfirm";
    import TranslatedText from "../../components/translated-text/TranslatedText";
    import WishListStore from "../../components/wish-list/store";

    export default {
        name: "RegisterConfirmPage",

        props: {
            token: {
                type: String,
                required: true
            },
        },

        components: {TranslatedText, UserRegisterConfirm, AppHeader},

        data() {
            return {
                dialog: true,
            };
        },

        computed: {
            ...mapState("global", ["me",]),
        },

        watch: {
            me() {
                if (Object.keys(this.me).length !== 0) {
                    this.loadWishList({uid: this.me.uid})
                }
            }
        },

        methods: {
            ...mapActions("wishList", ["loadWishList"]),
        },

        beforeCreate() {
            if (!this.$store.hasModule("wishList")) {
                this.$store.registerModule("wishList", WishListStore);
            }
        },
    };
</script>

<style scoped lang="scss">

    @import "../../../scss/base";
    @import "../../../scss/components/dialog";

    .register-confirm-dialog {

    }

</style>
