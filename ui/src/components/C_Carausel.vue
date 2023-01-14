 
<template class="my-1">
  <!-- <v-carousel next-icon="mdi-chevron-right" prev-icon="mdi-chevron-left" cycle delimiter-icon="mdi-minus" -->
  <div>
    <v-carousel cycle :show-arrows="false" delimiter-icon="mdi-checkbox-blank-circle-outline" class="mb-15 rounded-lg"
      :height="imageSize">
      <v-carousel-item v-for="(item, i) in items" :key="i" :src="require(`../assets/${item.img}`)">
        <v-row class="fill-height" align="center" justify="center">
          <v-col class="ml-15">
            <v-chip class="ma-2" close :color="settings.color" small text-color="white"
              :to="'/informasi/kategori/' + item.slug">
              {{ item.category }}
            </v-chip>
            <br>
            <router-link :to="'/informasi/detail/' + item.slugTitle">
              <div class="text-h4 white--text float-left">
                {{ item.title }}
              </div>
            </router-link>
          </v-col>
        </v-row>
      </v-carousel-item>
    </v-carousel>
  </div>


</template>
<script>
import { mapState } from "vuex";

export default {
  name: "Carausel",
  computed: {
    ...mapState(['settings'])
  },
  data() {
    return {
      cycle: true,
      imageSize: 500,
      model: null,
      items: [
        {
          category: "NATAL",
          slug: "natal",
          title: "Ibadah Natal GKI Lahai-Roi Mefkajim Ayamaru Papua Barat 25 Desember 2022",
          slugTitle: 'ibadah-natal',
          img: 'lahai1.jpeg',
        },
        {
          category: "KUNJUNGAN",
          slug: "jemaat",
          title: "Kunjungan Bupati Maybrat",
          slugTitle: 'kunjungan-bupati-maybrat',
          img: 'lahai2.jpeg',
        },
        {
          category: "SIDANG KLASIS",
          slug: "sidang-klasis",
          title: "Sidang Klasis di Maybrat",
          slugTitle: 'sidang-klasis-di-maybrat',
          img: 'lahai3.jpeg',
        },
        {
          category: "PESPARAWI",
          slug: "pesparawi",
          title: "Pesparawi se Indonesia di Kabupaten Maybrat 2024",
          slugTitle: 'pesparawi-se-indonesia-di-kabupaten-maybrat-2024',
          img: 'lahai5.jpeg',
        },
      ],
    }
  },
  mounted() {
    if (this.$store.state.settings.screenSize.type.isxs) {
      this.imageSize = 400;
    }
  }
}
</script>