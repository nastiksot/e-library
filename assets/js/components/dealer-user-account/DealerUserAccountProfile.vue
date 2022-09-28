<template>
    <div class="user-account-profile">

        <h2 class="user-account-profile__title">
            <translated-text code="DEALER_USER.ACCOUNT.EDIT.TITLE"/>
        </h2>

        <v-alert v-if="done" type="success" dense text>
            <translated-text code="DEALER_USER.ACCOUNT.MESSAGE.SUCCESS"/>
        </v-alert>

        <v-alert v-if="invalid && globalError" type="error" dense text>
            <translated-text :code="globalError"/>
        </v-alert>

        <v-form @submit.prevent="submitDealerUserAccountForm" :class="{'progress': busy}">

            <v-progress-circular v-if="busy" indeterminate color="orange" class="spinner" size="70"/>

            <v-text-field v-model="firstName"
                          :error="Boolean(errors.firstName)"
                          :error-messages="errors.firstName"
                          hide-details="auto"
                          outlined
                          class="mb-4"
            >
                <template v-slot:label>
                    <translated-link prefix="DEALER_USER.ACCOUNT.FIRST_NAME" suffix="*"/>
                </template>
            </v-text-field>

            <v-text-field v-model="lastName"
                          :error="Boolean(errors.lastName)"
                          :error-messages="errors.lastName"
                          hide-details="auto"
                          outlined
                          class="mb-4"
            >
                <template v-slot:label>
                    <translated-link prefix="DEALER_USER.ACCOUNT.LAST_NAME" suffix="*"/>
                </template>
            </v-text-field>

            <v-text-field v-model="email"
                          :error="Boolean(errors.email)"
                          :error-messages="errors.email"
                          hide-details="auto"
                          outlined
                          class="mb-4"
            >
                <template v-slot:label>
                    <translated-link prefix="DEALER_USER.ACCOUNT.EMAIL" suffix="*"/>
                </template>
            </v-text-field>

            <v-text-field v-model="password" :type="marker ? 'text' : 'password'"
                          :error="Boolean(errors.password)"
                          :error-messages="errors.password"
                          hide-details="auto"
                          outlined
                          :append-icon="marker ? 'visibility-on-icon' : 'visibility-off-icon'"
                          @click:append="toggleMarker"
                          autocomplete="new-password"
                          class="mb-4"
            >
                <template v-slot:label>
                    <translated-link prefix="DEALER_USER.ACCOUNT.PASSWORD" suffix="*"/>
                </template>
            </v-text-field>

            <v-select
                :label="roleDropDownTitle"
                v-model="role"
                :items="allowedRoles"
                item-text="title"
                item-value="key"
                :error="Boolean(errors.role)"
                :error-messages="errors.role"
            >
            </v-select>

            <v-checkbox v-model="active"
                        :error="Boolean(errors.active)"
                        :error-messages="errors.active"
                        hide-details
                        class="mt-1"
            >
                <template v-slot:label>
                    <translated-text code="DEALER_USER.ACCOUNT.ACTIVE"/>
                </template>
            </v-checkbox>

            <div class="user-account-profile__required mt-5">
                <translated-text code="GENERAL.REQUIRED_FIELDS"/>
            </div>

            <div class="actions-btn">
                <v-btn type="submit" :disabled="busy"
                       outlined
                       block large depressed color="orange" class="mt-5">
                    <translated-text :code="'DEALER_USER.ACCOUNT.BUTTON.' + (dealerUserId ? 'UPDATE' : 'CREATE') "/>
                </v-btn>

                <v-btn v-if="dealerUserId" type="button" :disabled="busy"
                       @click="openDeleteDialog"
                       outlined
                       block large depressed color="red" class="mt-5">
                    <translated-text code="DEALER_USER.ACCOUNT.BUTTON.DELETE"/>
                </v-btn>
            </div>

        </v-form>

        <dealer-user-delete-dialog
            :dialog-delete="dialogDelete"
            @close-delete="closeDelete"
            @delete-confirm="deleteConfirmed"
        />
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import DealerUserAccountStore from "./store";
import {mapComputed} from "../../modules/VuexMappers";
import TranslatedText from "../translated-text/TranslatedText";
import TranslatedLink from "../translated-text/TranslatedLink";
import DealerUserDeleteDialog from './DealerUserDeleteDialog';
import USER_ROLE from "../../dictionary/user-role";

export default {
    name: "DealerUserAccountProfile",

    props: {
        dealerUserId: {
            type: Number | null,
            required: false,
        },
    },

    components: {TranslatedLink, TranslatedText, DealerUserDeleteDialog},

    data() {
        return {
            marker: true,
            roleTitlePrefix: 'DEALER_USER.ACCOUNT.ROLE.',
            dialogDelete: false,
        };
    },

    computed: {
        ...mapState("global", ["me"]),
        ...mapState("dealerUserAccountModule", {
            done: state => state.done,
            busy: state => state.busy,
            invalid: state => state.invalid,
            errors: state => state.errors,
            globalError: state => state.globalError,
        }),

        ...mapComputed('dealerUserAccountModule',
            'firstName',
            'lastName',
            'email',
            'password',
            'role',
            'active',
        ),

        allowedRoles() {
            let rolesArr = [USER_ROLE.ROLE_DEALER_ADMIN, USER_ROLE.ROLE_DEALER_EMPLOYEE],
                res = [];

            for (let i in rolesArr) {
                res.push({
                    key: rolesArr[i],
                    title: this.$tc(this.roleTitlePrefix + rolesArr[i]),
                });
            }

            return res;
        },

        roleDropDownTitle() {
            return this.$tc('DEALER_USER.ACCOUNT.ROLE') + '*';
        },
    },

    methods: {
        ...mapActions("dealerUserAccountModule", [
            "loadDealerUser",
            "deleteDealerUserAccount",
            "submitDealerUserAccount",
        ]),

        submitDealerUserAccountForm() {
            this.submitDealerUserAccount(this.me);
        },

        toggleMarker() {
            this.marker = !this.marker
        },

        openDeleteDialog() {
            this.dialogDelete = true;
        },

        closeDelete() {
            this.dialogDelete = false
        },

        deleteConfirmed() {
            this.deleteDealerUserAccount(this.me);
        },

    },

    watch: {
        dialogDelete(val) {
            val || this.closeDelete()
        },

        me(val) {
            if (this.dealerUserId && val && val.dealerUid) {
                this.loadDealerUser({dealerUid: val.dealerUid, dealerUserId: this.dealerUserId});
            }
        },
    },

    beforeCreate() {
        if (!this.$store.hasModule('dealerUserAccountModule')) {
            this.$store.registerModule('dealerUserAccountModule', DealerUserAccountStore);
        }
    },
};
</script>

<style scoped lang="scss">

@import "../../../scss/base";

.user-account-profile {
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

    &__required {
        @include normal-font(12px, 14px);
        font-family: $fontSomfySansLight;
        color: #485C74;
        margin-bottom: 15px;
    }

    &__accept {
        &__title {

        }
    }
}
</style>
