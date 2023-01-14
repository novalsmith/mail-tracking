<template >
  <v-row>
    <v-col md="10" offset-md="1">
      <!-- <Breadcrumbs v-if="settings.screenSize.type.isxs || settings.screenSize.type.ismd" v-show="false" /> -->
      <!-- <Breadcrumbs /> -->
      <!-- <v-card> -->
      <v-img src="../assets/lahai2.jpeg" class="white--text align-end rounded-lg"
        gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)" height="400px" lazy-src="../assets/lahai2.jpeg">
        <v-card-title>
          Ibadah Perayaan Natal 25 Desember diadakan serentak se-papua barat di GKI
          Lahai-Roi Mefkajim Ayamaru Kabupaten Maybrat
        </v-card-title>
      </v-img>


      <v-card-actions>
        <v-chip outlined small :color="settings.color" :to="`/informasi/kategori/natal`">
          Sidang Klasis
        </v-chip>
        <span class="ml-5">12 Okt 2022</span>
        <v-spacer></v-spacer>

        <v-btn icon>
          <v-icon>mdi-cards-heart-outline</v-icon>
        </v-btn>

        <v-btn icon>
          <v-icon>mdi-bookmark-outline</v-icon>
        </v-btn>

        <v-btn icon>
          <v-icon>mdi-share-variant</v-icon>
        </v-btn>
      </v-card-actions>
      <!-- </v-card>  -->
    </v-col>
    <v-col md="10" offset-md="1">
      <!-- <v-img src="../assets/lahai2.jpeg" class="grey darken-4 rounded-lg"></v-img> -->
      <div class="my-1">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum repellat libero fugit vero est tempora
          amet, fuga quos ad dignissimos! Quas, necessitatibus maiores eos natus officiis iste repellendus dolorum
          porro.
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Impedit, eum inventore repellendus dolore
          distinctio pariatur soluta! Excepturi, tenetur quibusdam distinctio illo iusto eius alias, labore sunt
          impedit doloremque tempore earum? Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil modi nobis
          quis aliquid tempora ea,
          eum aut hic dicta reprehenderit voluptates officiis earum ex,
          atque optio at quas ratione! Temporibus!</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum repellat libero fugit vero est tempora
          amet, fuga quos ad dignissimos! Quas, necessitatibus maiores eos natus officiis iste repellendus dolorum
          porro.
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Impedit, eum inventore repellendus dolore
          distinctio pariatur soluta! Excepturi, tenetur quibusdam distinctio illo iusto eius alias, labore sunt
          impedit doloremque tempore earum? Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil modi nobis
          quis aliquid tempora ea,
          eum aut hic dicta reprehenderit voluptates officiis earum ex,
          atque optio at quas ratione! Temporibus!</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum repellat libero fugit vero est tempora
          amet, fuga quos ad dignissimos! Quas, necessitatibus maiores eos natus officiis iste repellendus dolorum
          porro.
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Impedit, eum inventore repellendus dolore
          distinctio pariatur soluta! Excepturi, tenetur quibusdam distinctio illo iusto eius alias, labore sunt
          impedit doloremque tempore earum? Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil modi nobis
          quis aliquid tempora ea,
          eum aut hic dicta reprehenderit voluptates officiis earum ex,
          atque optio at quas ratione! Temporibus!</p>

          <v-img src="../assets/lahai4.jpeg" height="400" class="grey darken-4 rounded-lg my-10">
        </v-img>

        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum repellat libero fugit vero est tempora
          amet, fuga quos ad dignissimos! Quas, necessitatibus maiores eos natus officiis iste repellendus dolorum
          porro.
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Impedit, eum inventore repellendus dolore
          distinctio pariatur soluta! Excepturi, tenetur quibusdam distinctio illo iusto eius alias, labore sunt
          impedit doloremque tempore earum? Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil modi nobis
          quis aliquid tempora ea,
          eum aut hic dicta reprehenderit voluptates officiis earum ex,
          atque optio at quas ratione! Temporibus!</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum repellat libero fugit vero est tempora
          amet, fuga quos ad dignissimos! Quas, necessitatibus maiores eos natus officiis iste repellendus dolorum
          porro.
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Impedit, eum inventore repellendus dolore
          distinctio pariatur soluta! Excepturi, tenetur quibusdam distinctio illo iusto eius alias, labore sunt
          impedit doloremque tempore earum? Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil modi nobis
          quis aliquid tempora ea,
          eum aut hic dicta reprehenderit voluptates officiis earum ex,
          atque optio at quas ratione! Temporibus!</p>
      </div>

      <!-- <OtherNews /> -->
    </v-col>
  </v-row>
</template>

<script>
import { mapState } from "vuex";
// import Breadcrumbs from '@/components/C_Breadcrumbs.vue';
// import OtherNews from '@/components/C_OtherNews.vue';
export default {
  name: "Video",
  computed: {
    ...mapState(['settings'])
  },
  data() {
    return {
      data: []
    }
  },
  components: {
    // Breadcrumbs,
    // OtherNews
  },
  methods: {
    setBreadcrumsData() {
      const datas = {
        type: "informasi",
        items: [
          {
            text: 'Beranda',
            disabled: false,
            href: '/',
            color: "#42A5F5"
          },
          {
            text: 'Informasi',
            disabled: false,
            href: '/informasi',
            color: "#42A5F5"
          },
          {
            text: 'Detail Berita',
            disabled: true,
            href: 'breadcrumbs_link_2',
            color: "#BDBDBD"
          },
        ],

      }
      this.$store.dispatch('breadcrumData', datas);
      console.log(this.$route.params) // outputs 'yay'

    }
  },
  created() {
    this.setBreadcrumsData();
  }
}
</script> 