<template>
    <div class="save-wish-list">

        <h2 class="save-wish-list__title">
            <translated-text code="WISHLIST.SAVE.TITLE"/>
        </h2>

        <template v-if="wishListSaved">
            <v-alert type="success" dense text>
                <translated-text code="WISHLIST.SAVE.MESSAGE.SUCCESS"/>
            </v-alert>

            <v-btn type="submit" :disabled="busy"
                   outlined
                   block large depressed color="orange" class="mt-5"
                   @click="clickClose"
            >
                <translated-text code="WISHLIST.SAVE.BUTTON.CLOSE"/>
            </v-btn>

        </template>

        <template v-else>

            <h3 class="save-wish-list__subtitle">
                <translated-text code="WISHLIST.SAVE.SUB_TITLE"/>
            </h3>

            <v-form @submit.prevent="submitSaveWishListForm" :class="{'progress': busy}">
                <v-progress-circular v-if="busy" indeterminate color="orange" class="spinner" size="70"/>

                <v-btn type="submit" :disabled="busy"
                       block large depressed color="orange" class="mt-5">
                    <translated-text code="WISHLIST.SAVE.BUTTON.SUBMIT"/>
                </v-btn>
            </v-form>
        </template>

    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import SaveConfigurationStore from "./store";
    import TranslatedText from "../translated-text/TranslatedText";

    export default {
        name: "SaveConfigurationWishList",

        props: {},

        components: {TranslatedText},

        data() {
            return {};
        },

        computed: {
            ...mapState("global", ["me", 'isDealerMode',]),
            ...mapState("saveConfigurationStore", {
                done: state => state.done,
                busy: state => state.busy,
                invalid: state => state.invalid,
                globalError: state => state.globalError,
                wishListSaved: state => state.wishListSaved,
            }),
        },

        watch: {
            wishListSaved(val) {
                if (val) {
                    this.$store.dispatch('global/loadMe');
                    this.tcEventClickOnSaveWishListToUser();
                }
            },
        },

        methods: {
            ...mapActions("saveConfigurationStore", [
                "saveWishListConfiguration"
            ]),

            submitSaveWishListForm() {
                this.saveWishListConfiguration({uid: this.me.uid, isDealerMode: this.isDealerMode});
            },

            clickClose() {
                this.$emit("close");
            },

            tcEventClickOnSaveWishListToUser() {
                if (typeof (tc_events_global) === 'undefined') {
                    return;
                }

                try {
                    tc_events_global(this, 'saveto_account_click', {
                        'evt_category': 'account',
                        'evt_button_action': 'saveto_account_click',
                    });
                } catch (error) {
                    console.error(error);
                }
            },
        },

        beforeCreate() {
            if (!this.$store.hasModule("saveConfigurationStore")) {
                this.$store.registerModule("saveConfigurationStore", SaveConfigurationStore);
            }
        },
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .save-wish-list {
        &__title {
            color: #485c74;
            text-align: center;
            @include normal-font(28px, 32px);
            font-family: $fontSomfySansLight;
            padding: 0;
            @include justify-content(center);
            margin-bottom: 10px;
        }

        &__subtitle {
            color: #485c74;
            text-align: center;
            font-family: $fontSomfySansLight;
            @include normal-font(22px, 32px);
        }
    }

</style>
