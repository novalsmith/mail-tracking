<template>
  <v-main>
    <div v-if="settings.screenSize.type.islg">
      <div class="mx-5 mt-5">
        <v-text-field v-model="searchValueData" solo dense rounded filled
          placeholder="Masukan kata yang ingin dicari disini" :keyup="fiterData" prepend-inner-icon="mdi-magnify">
        </v-text-field>
        <div v-if="this.searchValueData.length < 3">Minimal masukan 3 karakter untuk lakukan pencarian</div>
        <div v-else>
          <div v-if="fiterData.length > 0"> {{ fiterData.length }} Data ditemukan </div>
          <div v-else>Kata kunci tidak ditemukan</div>
        </div>
      </div>
      <v-list three-line v-if="this.searchValueData.length > 2">
        <v-list-item v-for="{ item, i } in fiterData" :key="i" @click="resultClicked">

          <v-img :src="item.image" class="mr-4 rounded" max-width="100" min-width="100"></v-img>

          <v-list-item-content>
            <span class="text-uppercase font-weight-regular text-caption" v-text="item.category"></span>

            <div v-text="item.title"></div>
          </v-list-item-content>
        </v-list-item>

      </v-list>
    </div>
    <div v-else>
      <v-toolbar elevation="0" color="primary">
        <v-text-field dense rounded filled width="100%" class="mt-7" v-model="searchValueData"
          placeholder="Masukan kata yang ingin dicari disini" prepend-inner-icon="mdi-magnify"
          @keyup="$emit('searchData', searchValueData)">
        </v-text-field>
      </v-toolbar>
    </div>
  </v-main>
</template>

<script>
import { mapState } from "vuex";

export default {
  data() {
    return {
      dialogm1: '',
      dialog: false,
      localData: this.$store.state.settings['dialogData'],
      searchValueData: "",
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
        },
        {
          image: 'https://cdn-images-1.medium.com/max/1024/1*FD2nkJewVeQnGf0ommQfrw.jpeg',
          title: 'The Pitfalls Of Outsourcing Self-Awareness To Artificial Intelligence',
          category: 'Technology',
          keyword: 'Military',
        },
        {
          image: 'https://cdn-images-1.medium.com/max/1024/1*eogFpsVgNzXQLCVgFzT_-A.jpeg',
          title: 'Degrees of Freedom and Sudoko',
          category: 'Travel',
          keyword: 'Social',
        },
        {
          image: 'https://cdn-images-1.medium.com/max/1024/1*FD2nkJewVeQnGf0ommQfrw.jpeg',
          title: 'The Pitfalls Of Outsourcing Self-Awareness To Artificial Intelligence',
          category: 'Technology',
          keyword: 'Military',
        },
        {
          image: 'https://cdn-images-1.medium.com/max/1024/1*eogFpsVgNzXQLCVgFzT_-A.jpeg',
          title: 'Degrees of Freedom and Sudoko',
          category: 'Travel',
          keyword: 'Social',
        },
        {
          image: 'https://cdn-images-1.medium.com/max/1024/1*FD2nkJewVeQnGf0ommQfrw.jpeg',
          title: 'Natal',
          category: 'Technology',
          keyword: 'Military',
        },
        {
          image: 'https://cdn-images-1.medium.com/max/1024/1*eogFpsVgNzXQLCVgFzT_-A.jpeg',
          title: 'Degrees of Freedom and Sudoko',
          category: 'Travel',
          keyword: 'Social',
        },
      ],
    }
  },
  methods: {
    resultClicked() {
      console.log("clicked");
      this.dialog = false; // close the dialog
      this.localData.isShowDialog = false;
      this.localData.modalType.isSearch = false;

      const modalData = {
        dialogData: this.localData,
      }
      console.log(modalData);
      this.$store.dispatch('settings', modalData);
    },
  },
  computed: {
    fiterData() {
      var listData = [];
      if (this.searchValueData.length > 3) {
        listData = this.items.filter(item => item.title.toLocaleLowerCase().includes(this.searchValueData.toLocaleLowerCase()));
      }

      return listData;
    },
    ...mapState(['settings'])
  },
}
</script>