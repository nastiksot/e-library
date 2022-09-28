<template>
    <div class="footer-social" v-if="ready && generalSettings">
        <a v-if="generalSettings.socialFacebook" :href="generalSettings.socialFacebook"
           class="footer-social-fb-btn">
            <i class="fb-icon"></i>
        </a>

        <a v-if="generalSettings.socialYoutube" :href="generalSettings.socialYoutube"
           class="footer-social-youtube-btn">
            <i class="youtube-icon"></i>
        </a>

        <a v-if="generalSettings.socialInstagram" :href="generalSettings.socialInstagram"
           class="footer-social-instagram-btn">
            <i class="instagram-icon"></i>
        </a>
    </div>
</template>

<script>

import {mapActions, mapState} from 'vuex';
import socialMediaStore from './store';

export default {
    name: 'SocialMedia',

    props: {},

    components: {},

    data() {
        return {};
    },

    computed: {
        ...mapState('socialMedia', {
            ready: state => state.ready,
            generalSettings: state => state.generalSettings,
        }),
    },

    watch: {},

    methods: {
        ...mapActions('socialMedia', [
            'loadGeneralSettings',
        ]),
    },

    beforeCreate() {
        if (!this.$store.hasModule('socialMedia')) {
            this.$store.registerModule('socialMedia', socialMediaStore);
        }
    },

    created() {
        this.loadGeneralSettings();
    },
};
</script>

<style scoped lang="scss">
@import "../../../scss/base";

.footer-social {
    @include flexbox();
    @include align-items(center);
    @include justify-content(center);

    a {
        color: #8996a4 !important;
        @include transition(color 0.2s ease-in-out);
        font-size: 20px;
        margin: 0 0 0 40px;

        &:first-child {
            margin-left: 0;
        }

        &:hover {
            text-decoration: none;
            color: #747F8B !important;
        }
    }

    @media (max-width: 991px) {
        border-top: 1px solid #8996a4;
        padding-top: 20px;
    }
}
</style>
