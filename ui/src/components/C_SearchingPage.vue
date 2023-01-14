<template>
  <div>
    <div v-if="!settings.screenSize.type.islg">
      <v-navigation-drawer v-model="drawer" fixed left class="rounded-r-lg">
        <v-row class="my-1 mx-1">
          <v-col md="10">
            <span>Kategori</span>
          </v-col>
          <v-col md="2" class="text-right">
            <v-btn icon @click.stop="drawer = false" :color="settings.color">
              <v-icon>
                mdi-close
              </v-icon>
            </v-btn>
          </v-col>
        </v-row>
        <v-divider></v-divider>
        <C_CategorySection />
      </v-navigation-drawer> 
    </div>
    <v-container>
      <v-btn text @click.stop="drawer = !drawer">
        <v-icon>mdi-menu</v-icon>
        <span class="text-h7 mx-2">Kategori</span>
      </v-btn>
      <!-- <v-spacer></v-spacer> -->
      <span class="text-h7 mx-2 text-right grey--text">Natal</span>
    </v-container>
    <v-toolbar elevation="0" color="primary"> 
      <v-text-field dense rounded filled width="100%" class="mt-7" placeholder="Masukan kata yang ingin dicari disini"
        prepend-inner-icon="mdi-magnify">
      </v-text-field>
    </v-toolbar>
    <v-list three-line color="primary">
      <v-list-item v-for="(item, i) in items" :key="i">

        <v-img :src="item.image" class="mr-4 rounded" max-width="100" min-width="100"></v-img>

        <v-list-item-content>
          <span class="text-uppercase font-weight-regular text-caption" v-text="item.category"></span>

          <div v-text="item.title"></div>
        </v-list-item-content>
      </v-list-item>

    </v-list>
  </div>
</template>
<script>
import { mapState } from "vuex";
import C_CategorySection from '@/components/C_CategorySection.vue';
// import { width, type } from 'screenSize';
// const screenSize = new useBreakpoints();
export default {
  computed: {
    ...mapState(['settings'])
  },
  data: () => ({
    drawer: false,
    group: null,
    dialog: false,
    notifications: false,
    sound: true,
    widgets: false,
    categories: [
      { text: 'Natal', icon: 'mdi-clock', total: 5, slug: "natal" },
      { text: 'Jemaat', icon: 'mdi-account', total: 10, slug: "jemaat" },
      { text: 'PESPARAWI', icon: 'mdi-flag', total: 10, slug: "pesparawi" },
      { text: 'PW', icon: 'mdi-flag', total: 12, slug: "pw" },
      { text: 'Sidang Klasis', icon: 'mdi-flag', total: 3, slug: "sidang-klasis" },
      { text: 'Oukumene', icon: 'mdi-flag', total: 7, slug: "oukumene" },
    ],
    items: [
      {
        image: 'https://cdn-images-1.medium.com/max/1024/1*9C9hLji68wV373tk8okLYA.jpeg',
        title: 'TBI’s 5 Best: SF Mocktails to Finish Dry January Strong',
        category: 'Travel',
        keyword: 'Drinks',

      },
      {
        image: 'https://cdn-images-1.medium.com/max/1024/1*BBNtYUieAqHoXKjiJ2mMjQ.png',
        title: 'PWAs on iOS 12.2 beta: the good, the bad, and the “not sure yet if good”',
        category: 'Technology',
        keyword: 'Phones'
      },
      {
        image: 'https://cdn-images-1.medium.com/max/1024/1*rTEtei1UEmNqbq6evRsExw.jpeg',
        title: 'How to Get Media Mentions for Your Business',
        category: 'Media',
        keyword: 'Social'
      },
      {
        image: 'https://cdn-images-1.medium.com/max/1024/1*FD2nkJewVeQnGf0ommQfrw.jpeg',
        title: 'The Pitfalls Of Outsourcing Self-Awareness To Artificial Intelligence',
        category: 'Technology',
        keyword: 'Military'
      },
      {
        image: 'https://cdn-images-1.medium.com/max/1024/1*eogFpsVgNzXQLCVgFzT_-A.jpeg',
        title: 'Degrees of Freedom and Sudoko',
        category: 'Travel',
        keyword: 'Social',
      }
    ],
  }),
  watch: {
    group() {
      this.drawer = false;

    },
  },
  methods: {
    // console.log(screenSize);
    searchingButton() {
      this.dialog = true;
    }
  },
  components: {
    C_CategorySection
  }
}
</script>