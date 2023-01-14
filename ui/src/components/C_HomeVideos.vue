<template >
    <div>

        <v-col md="12">
            <v-row>
                <v-col md="6">
                    <h3 class="font-weight-regular">
                        <v-icon>
                            mdi-video
                        </v-icon>
                        Video
                    </h3>
                </v-col>
                <v-col md="6">
                    <v-btn v-show="isShowVideo" small text :color="settings.color" class="white-text float-right"
                        to="/media/video">
                        Semua <v-icon> mdi-chevron-right</v-icon>
                    </v-btn>
                </v-col>
            </v-row>
        </v-col>
        <v-col md="12">
            <v-row v-if="settings.screenSize.type.ismd || settings.screenSize.type.islg">
                <v-col md="4" v-for="{ item, i } in listData" :key="i">
                    <v-flex xs12>
                        <v-hover v-slot="{ hover }" open-delay="200">
                            <v-card :elevation="hover ? 5 : 1" :class="{ 'on-hover': hover }">
                                <v-container fluid grid-list-lg>
                                    <v-layout row>
                                        <LazyYoutube width="100%" src="https://www.youtube.com/watch?v=TcMBFSGVi1c" />
                                        <div>
                                            <div class="subheading font-weight-medium mt-3 ml-3">
                                                Sidang Klasis di Maybrat
                                            </div>
                                            <h5 class="float-left font-weight-regular my-2">
                                                <v-chip class="ma-2" small outlined :color="settings.color">
                                                    Natal
                                                </v-chip> 25 Sep 2022
                                            </h5>
                                        </div>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-hover>
                    </v-flex>
                </v-col>
            </v-row>
            <div v-else>
                <v-col v-show="isShowVideo" v-for="{ item, i } in listData" :color="settings.color" :key="i">
                    <v-hover v-slot="{ hover }" open-delay="200">
                        <v-card :elevation="hover ? 18 : 1" :class="{ 'on-hover': hover }">
                            <LazyYoutube width="100%" src="https://www.youtube.com/watch?v=TcMBFSGVi1c" />
                            <v-row>
                                <v-col md="6">
                                    <v-chip class="mx-5 my-5" small outlined :color="settings.color"
                                        :to="'/media/video'">
                                        Natal
                                    </v-chip>
                                </v-col>
                                <v-col md="6">
                                    <v-card-subtitle class="float-right">
                                        25 Sep 2022
                                    </v-card-subtitle>
                                </v-col>
                            </v-row>
                            <v-divider class="mx-5"></v-divider>
                            <v-card-title class="font-weight-regular">Sidang Klasis di Maybrat</v-card-title>
                        </v-card>
                    </v-hover>
                </v-col>
            </div>
        </v-col>
        <v-col md="12" v-show="isShowVideo == false">
            <v-alert :color="settings.color + ' lighten-5'" icon="mdi-information-outline" dense>
                Sementara belum ada video
            </v-alert>
        </v-col>

    </div>
</template>

<script>
import { mapState } from "vuex";
import Vue from 'vue'
// import Embed from 'v-video-embed'
import { LazyYoutube } from 'vue-lazytube'


// global register
// Vue.use(Embed);
export default {
    name: "Video",
    computed: {
        ...mapState(['settings'])
    },
    data() {
        return {
            result: "",
            isShowVideo: false,
            youtubeURL: "https://www.youtube.com/watch?v=qZXt1Aom3Cs",
            listData: [
                {
                    youtubeURL: "qZXt1Aom3Cs"
                },
                {
                    youtubeURL: "qZXt1Aom3Cs"
                },
                {
                    youtubeURL: "qZXt1Aom3Cs"
                },
                {
                    youtubeURL: "qZXt1Aom3Cs"
                },

                {
                    youtubeURL: "qZXt1Aom3Cs"
                },
                {
                    youtubeURL: "qZXt1Aom3Cs"
                }
            ]
        }
    },
    mounted() {
        this.isShowVideo = this.listData.length > 0 ? true : false;
        this.loadURL();
    },
    methods: {
        loadURL() {

            const youtubeEmbedTemplate = 'https://www.youtube.com/embed/'
            const url = this.youtubeURL.split(/(vi\/|v%3D|v=|\/v\/|youtu\.be\/|\/embed\/)/)
            const YId = undefined !== url[2] ? url[2].split(/[^0-9a-z_/\\-]/i)[0] : url[0]
            const topOfQueue = youtubeEmbedTemplate.concat(YId)
            this.result = topOfQueue
            this.youtubeURL = ''
        }
    },
    components: {
        LazyYoutube
    }
}
</script>

<style>
iframe {
    width: 100% !important;
    min-width: 300px;
}
</style>