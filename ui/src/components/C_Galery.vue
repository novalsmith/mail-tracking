<template>
    <v-row class="my-5">

        <v-col md="6">
            <h3 class="font-weight-regular">
                <v-icon>
                    mdi-camera
                </v-icon>
                Album Foto
            </h3>
        </v-col>
        <v-col md="6">
            <v-btn small text :color="settings.color" class="white-text float-right" to="/media/foto">
                Semua <v-icon> mdi-chevron-right</v-icon>
            </v-btn>
        </v-col>
        <v-col cols="12">
            <v-row>
                <v-col md="4" v-show="isShowgalery" v-for="( image, i ) in imagesList" :key="i">
                    <v-flex xs12>
                        <v-hover v-slot="{ hover }" open-delay="200">
                            <router-link :to="'/foto/album/' + image.slug" class="text-decoration-none">
                                <v-card :elevation="hover ? 5 : 1" :class="{ 'on-hover': hover }">
                                    <v-container fluid grid-list-lg>
                                        <v-layout row>
                                            <v-flex xs5>
                                                <v-img :height="settings.defaultImageSmallContentHeight"
                                                    :src="require(`../assets/${image.source}`)" :alt="image.alt"
                                                    :lazy-src="require(`../assets/${image.source}`)"
                                                    class="grey darken-4 rounded-lg" width="300">
                                                    <template v-slot:placeholder>
                                                        <v-row class="fill-height ma-0" align="center" justify="center">
                                                            <v-progress-circular indeterminate color="grey lighten-5">
                                                            </v-progress-circular>
                                                        </v-row>
                                                    </template>
                                                </v-img>
                                            </v-flex>
                                            <v-flex xs7>


                                                <div class="mt-3">
                                                    <h3 class="font-weight-medium grey--text">{{ image.total }} Foto {{
                                                        image.title
                                                    }}</h3>
                                                    <h5 class="float-left font-weight-regular my-2">
                                                        <v-chip class="ma-2" small outlined :color="settings.color">
                                                            {{ image.category }}
                                                        </v-chip> {{ image.date }}
                                                    </h5>
                                                </div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                            </router-link>
                        </v-hover>
                    </v-flex>
                </v-col>
            </v-row>
        </v-col>

        <v-col md="12" v-show="isShowgalery == false">
            <v-alert :color="settings.color + ' lighten-5'" icon="mdi-information-outline" dense>
                Sementara belum ada album foto
            </v-alert>
        </v-col>

    </v-row>
</template>


<script>
import { mapState } from "vuex";
export default {
    name: "Galery",
    computed: {
        ...mapState(['settings'])
    },
    data() {
        return {
            isShowgalery: false,
            imagesList: [
                { source: "lahai1.jpeg", category: "Pesparawi", title: "Natal", total: 15, date: "25 Sept 2022", slug: "natal" },
                { source: "lahai2.jpeg", category: "KKR", title: "Sidang Klasis 2022", total: 23, date: "15 Sept 2022", slug: "sidang-klasis" },
                { source: "lahai3.jpeg", category: "Oukumene", title: "Natal", total: 50, date: "6 Agus 2022", slug: "natal" },
                { source: "lahai4.jpeg", category: "Mefkajim", title: "PKB", total: 14, date: "25 Des 2022", slug: "pkb" },
                { source: "lahai5.jpeg", category: "Sidang Klasis", title: "PW", total: 20, date: "2 Sept 2022", slug: "pw" },
                { source: "lahai6.jpeg", category: "Natal", title: "Pesparawi", total: 15, date: "8 Sept 2022", slug: "pesparawi" }
            ]
        }
    },
    mounted() {
        this.isShowgalery = this.imagesList.length > 0 ? true : false;
    }

}
</script>