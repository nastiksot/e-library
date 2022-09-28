<template>
    <div class="cms">

        <app-header/>

        <div class="cms__content">
            <slot name="default"></slot>
        </div>
    </div>
</template>

<script>
    import {mapState, mapActions} from "vuex";
    import AppHeader from "../../components/app-header/AppHeader";
    import WishListStore from "../../components/wish-list/store";

    export default {
        name: "Cms",

        props: {},

        components: {AppHeader},

        data() {
            return {
                tabs: null,
                marker: true
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

    .cms {
        &__content {
        }
    }
</style>
