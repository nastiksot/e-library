<template>
    <div class="video-player">
        <div class="video-player__panel">
            <v-btn text @click="clickClose">
                <v-icon>close-icon</v-icon>
                <translated-text code="VIDEO_PLAYER.BUTTON.CLOSE"/>
            </v-btn>
        </div>
        <div class="video-player__iframe">
            <iframe width="560" height="315" :src="embedUrl"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
            ></iframe>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import VideoPlayerStore from "./store";
    import TranslatedText from "../translated-text/TranslatedText";

    export default {
        name: "VideoPlayer",

        props: {},

        components: {TranslatedText},

        data() {
            return {};
        },

        computed: {
            ...mapState("videoPlayer", ["videoCode"]),

            embedUrl() {
                return "https://www.youtube-nocookie.com/embed/" + this.videoCode + "?mode=opaque&amp;rel=0&amp;autoplay=1&amp;autohide=1&amp;showinfo=0&amp;wmode=transparent"
            }

// lWdpcpswT90
        },

        watch: {},

        methods: {
            ...mapActions("videoPlayer", [
                "setVideoUrl",
            ]),

            clickClose() {
                this.setVideoUrl(null);
            }
        },

        beforeCreate() {
            if (!this.$store.hasModule("videoPlayer")) {
                this.$store.registerModule("videoPlayer", VideoPlayerStore);
            }
        }
    };
</script>

<style scoped lang="scss">
    @import "../../../scss/base";

    .video-player {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 100%;
        max-width: 810px;
        max-height: 555px;
        height: 100%;
        transform: translateX(-50%) translateY(-50%);
        //padding-bottom: 56.25%;
        z-index: 998;

        &__iframe{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        embed,
        video,
        iframe {
            margin: 0;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        &__panel{
            padding: 7px 15px;
            background-image: linear-gradient(to right, rgba(60, 79, 100, 0) 15%, #1e2832 83%);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 11;
            text-align: right;

            .v-btn{
                color: #fff;

                &::v-deep{
                    .v-btn__content{
                        @include align-items(flex-start);

                        .v-icon{
                            margin-right: 0;
                        }
                    }
                }

                @media (max-width: 768px) {
                    &__text{
                        display: none;
                    }
                }
            }
        }

        @media (max-width: 991px) {
            right: 0;
            top: 10px;
            transform: translateX(-50%);
        }
    }
</style>
