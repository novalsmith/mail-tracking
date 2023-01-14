 
<template class="mt-1">
    <div class="mb-15">
        <v-slide-group v-model="model" active-class="success" center-active :show-arrows="(settings.screenSize.type.islg)" next-icon="mdi-chevron-right"
            prev-icon="mdi-chevron-left">
            <v-slide-item v-for="item in items" v-slot="{ active, toggle }">
                <v-card class="ma-4">
                    <v-row class="fill-height" align="center" justify="center" @click="toggle">
                        <v-scale-transition>
                            <v-img :src="item.src" height="250" width="280" class="rounded-lg">
                            </v-img>
                        </v-scale-transition>
                    </v-row>
                </v-card>
            </v-slide-item>
        </v-slide-group>
    </div>
</template>
<script>
import { mapState } from "vuex";
export default {
    name: "Poster",
    data() {
        return {
            model: "", 
            items: [
                {
                    category: "NATAL",
                    title: "Ibadah Natal GKI Lahai-Roi Mefkajim Ayamaru",
                    src: 'https://cdn.vuetifyjs.com/images/carousel/squirrel.jpg',
                },
                {
                    category: "KUNJUNGAN",
                    title: "Kunjungan Bupati Maybrat",
                    src: 'https://cdn.vuetifyjs.com/images/carousel/sky.jpg',
                },
                {
                    category: "SIDANG KLASIS",
                    title: "Sidang Klasis di Maybrat",
                    src: 'https://cdn.vuetifyjs.com/images/carousel/bird.jpg',
                },
                {
                    category: "PESPARAWI",
                    title: "Pesparawi se Indonesia di Kabupaten Maybrat 2024",
                    src: 'https://cdn.vuetifyjs.com/images/carousel/planet.jpg',
                }, {
                    category: "NATAL",
                    title: "Ibadah Natal GKI Lahai-Roi Mefkajim Ayamaru",
                    src: 'https://cdn.vuetifyjs.com/images/carousel/squirrel.jpg',
                },
                {
                    category: "KUNJUNGAN",
                    title: "Kunjungan Bupati Maybrat",
                    src: 'https://cdn.vuetifyjs.com/images/carousel/sky.jpg',
                },
                {
                    category: "SIDANG KLASIS",
                    title: "Sidang Klasis di Maybrat",
                    src: 'https://cdn.vuetifyjs.com/images/carousel/bird.jpg',
                },
                {
                    category: "PESPARAWI",
                    title: "Pesparawi se Indonesia di Kabupaten Maybrat 2024",
                    src: 'https://cdn.vuetifyjs.com/images/carousel/planet.jpg',
                },
            ],
        }
    },
    computed: {
    ...mapState(['settings']) 
  
  }
}
</script>