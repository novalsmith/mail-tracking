<template>
    <v-container>
        <v-row class="my-5">
            <v-col md="6">
                <h3 class="font-weight-regular">
                    <v-icon>
                        mdi-calendar-multiple-check
                    </v-icon>
                    Agenda
                </h3>
            </v-col>
            <v-col md="6">
                <v-btn v-show="isShowAgenda" small text :color="settings.color" class="white-text float-right"
                    to="/agenda">
                    Semua <v-icon> mdi-chevron-right</v-icon>
                </v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-col md="6" v-show="isShowAgenda" v-for="item in listData">
                <v-flex xs12>
                    <v-hover v-slot="{ hover }" open-delay="200">
                        <router-link :to="'/informasi/detail/' + item.slugTitle" class="text-decoration-none">
                            <v-card :elevation="hover ? 18 : 1" :class="{ 'on-hover': hover }">
                                <v-container fluid grid-list-lg>
                                    <v-layout row>
                                        <v-flex xs5>
                                            <v-img src="../assets/profilemefkajim.jpg" width="300"
                                                :height="settings.defaultImageSmallContentHeight"
                                                lazy-src="../assets/profilemefkajim.jpg"
                                                class="grey darken-4 rounded-lg">
                                                <template v-slot:placeholder>
                                                    <v-row class="fill-height ma-0" align="center" justify="center">
                                                        <v-progress-circular indeterminate color="grey lighten-5">
                                                        </v-progress-circular>
                                                    </v-row>
                                                </template>
                                            </v-img>
                                        </v-flex>
                                        <v-flex xs7>
                                            <div>

                                                <h2 class="font-weight-medium grey--text">
                                                    <v-icon class="grey--text">
                                                        mdi-bell
                                                    </v-icon> {{ item.date }}
                                                </h2>

                                            </div>
                                            <div class="subheading font-weight-medium mt-1">
                                                {{ item.title }}
                                            </div>
                                            <h5 class="float-left font-weight-regular my-2">
                                                <v-chip class="ma-2" small outlined :color="settings.color">
                                                    {{ item.category }}
                                                </v-chip> {{ item.datePublish }}
                                            </h5>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </router-link>
                    </v-hover>
                </v-flex>
            </v-col>
            <v-col md="12" v-show="isShowAgenda == false">
                <v-alert :color="settings.color + ' lighten-5'" icon="mdi-information-outline" dense>
                    Sementara belum ada agenda
                </v-alert>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { mapState } from "vuex";
export default {
    name: "Agenda",
    computed: {
        ...mapState(['settings'])
    },
    data: () => ({
        model: null,

        isShowAgenda: false,
        listData: [
            {
                title: "Ibadah Natal 2021",
                category: "PKB",
                date: "25 Des 2021",
                datePublish: "16 Des 2021"
            },
            {
                title: "Pesparawi Nasional diadakan di maybrat",
                category: "Pesparawi",
                date: "25 Sep 2022",
                datePublish: "16 Des 2021"
            },
            {
                title: "Peresmian Gedung Gereja Cabang di Maybrat",
                category: "Peresmian",
                date: "14 Nov 2022",
                datePublish: "16 Des 2021"
            },
            {
                title: "Sidang Klasis di Maybrat",
                category: "Sidang Klasis",
                date: "02 Jan 2022",
                datePublish: "16 Des 2021"
            }
        ]
    }),
    mounted() {
        this.isShowAgenda = this.listData.length > 0 ? true : false;
    }
}
</script>