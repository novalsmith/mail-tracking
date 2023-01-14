<template>
  <v-dialog v-model="this.$store.state.settings['dialogData'].isShowDialog" scrollable persistent width="600px">
    <v-card>
      <v-card-title>{{this.$store.state.settings['dialogData'].data?.title}}
        <v-spacer></v-spacer>
        <v-btn :color="settings.color" icon class="white--text" @click="closeDialog()">
          <v-icon>
            mdi-close
          </v-icon>
        </v-btn>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <slot></slot>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn :color="settings.color" @click="closeDialog()" class="white--text" text>
          OK
        </v-btn>
      </v-card-actions>
    </v-card>

  </v-dialog>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      dialogm1: '',
      localData: this.$store.state.settings['dialogData'],
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
          keyword: 'Phones',
        },
        {
          image: 'https://cdn-images-1.medium.com/max/1024/1*rTEtei1UEmNqbq6evRsExw.jpeg',
          title: 'How to Get Media Mentions for Your Business',
          category: 'Media',
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
      ],
    }
  },
  methods: {
    initData() {
      this.dialog = this.$store.state.settings['dialogData'].isShowDialog;
      this.isSearchingModal = this.$store.state.settings['dialogData'].modalType.isSearch;
    },
    closeDialog() {
      console.log("clicked");
      this.dialog = false; // close the dialog
      this.localData.isShowDialog = false;
      this.localData.modalType.isSearch = false;
      this.localData.modalType.isGalery = false;
      const modalData = {
        dialogData: this.localData
      }
      this.$store.dispatch('settings', modalData);

    }
  },
  mounted() {
    // this.initData();
  },
  computed: {
    ...mapState(['settings'])

  },
  components: {
  }
}
</script>