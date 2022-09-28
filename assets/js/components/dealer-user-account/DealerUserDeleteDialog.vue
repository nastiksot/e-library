<template>
    <v-dialog v-model="dialogDelete" max-width="500px">
        <v-card class="modal-delete">
            <v-card-title class="modal-title">
                <translated-text code="DEALER_USER.LIST.DELETE.TITLE"/>
            </v-card-title>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn class="modal-btn" text @click="deleteConfirmed">
                    <translated-text code="DEALER_USER.LIST.BUTTON.DELETE"/>
                </v-btn>

                <v-btn class="modal-btn modal-btn_close" text @click="closeDelete">
                    <translated-text code="DEALER_USER.LIST.BUTTON.CANCEL"/>
                </v-btn>

                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import TranslatedText from "../translated-text/TranslatedText";

export default {
    name: "DealerUserDeleteDialog",

    props: {
        dialogDelete: {
            type: Boolean,
            required: true
        }
    },

    components: {TranslatedText},

    data() {
        return {};
    },

    computed: {},

    methods: {
        closeDelete() {
            this.$emit("close-delete");
        },

        deleteConfirmed() {
            this.$emit("delete-confirm");
            this.closeDelete();
        },
    },
};
</script>

<style scoped lang="scss">
@import "../../../scss/base";

.modal-delete {
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
