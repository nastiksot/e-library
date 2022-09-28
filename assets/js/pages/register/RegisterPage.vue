<template>
    <div class="app">
        <app-header active="account"/>
        <div class="app__account">
            <div class="app__account--content">
                <user-register/>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState, mapActions} from "vuex";
    import AppHeader from "../../components/app-header/AppHeader";
    import ProductSetItem from "../../components/product-set/ProductSetItem";
    import UserRegister from "../../components/user-register/UserRegister";
    import WishListStore from "../../components/wish-list/store";

    export default {
        name: "RegisterPage",

        props: {},

        components: {UserRegister, ProductSetItem, AppHeader},

        data() {
            return {};
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

    .app {
        &__account{
            @include flexbox();
            background: #F8F7F5;
            padding-top: 20px;

            &--content {
                padding: 20px;
                max-width: 500px;
                width: 100%;
                margin: auto;
                background: #fff;

                .v-window {
                    overflow: visible;
                }

                &-title {
                    color: #485c74;
                    @include normal-font(28px, 37px);
                    font-family: $fontSomfySansLight;
                    margin-bottom: 7px;
                }

                &-subtitle {
                    color: #485c74;
                    @include normal-font(22px, 30px);
                    font-family: $fontSomfySansLight;
                    padding-bottom: 8px;
                    border-bottom: 1px solid #cacfd5;
                    margin-bottom: 23px;
                }

                &-link {
                    color: #fcac22;
                    @include normal-font(14px, 17px);
                    margin-top: 3px;
                    font-family: $fontSomfySansLight;
                    text-decoration: underline;

                    &:hover {
                        text-decoration: none;
                    }
                }

                &-sort {
                    margin-bottom: 20px;
                }

                &::v-deep {
                    .v-input {
                        &__icon--append {
                            .v-icon {
                                &:before {
                                    font-family: 'somfy' !important;
                                }
                            }
                        }
                    }
                }

                .v-form {
                    &__actions {
                        margin-top: 40px;
                        text-align: right;

                        .v-btn {
                            min-width: 215px;
                        }
                    }
                }
            }

            @media (max-width: 991px) {
                @include flex-direction(column);

                &--content {
                    padding: 20px;
                }
            }
        }
    }
</style>
