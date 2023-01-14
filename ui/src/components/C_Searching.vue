<template>
  <div>

    <v-btn icon small @click="searchingData" large class="d-flex d-sm-none d-md-none d-lg-none">
      <v-icon>mdi-magnify</v-icon>
    </v-btn>


    <v-btn icon small @click="searchingData" class="d-none d-md-block d-sm-block d-lg-block">
      <v-icon>mdi-magnify</v-icon>
    </v-btn>


    <GeneralDialog v-if="this.$store.state.settings['dialogData']['modalType'].isSearch">
      <component :is="child_component"></component>
    </GeneralDialog>
  </div>

</template>

<script>
import { mapState } from "vuex";
import GeneralDialog from "@/components/C_GeneralDialog.vue";
import SearchingModal from "@/components/C_SearchingModal.vue";
export default {
  data() {
    return {
      dialogm1: '',
      dialog: false,
      child_component: 'SearchingModal',
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
      localData: this.$store.state.settings['dialogData']
    }
  },
  methods: {
    searchingData() {

      this.dialog = true;
      console.log(this.$store.state.settings['dialogData']['modalType'].isSearch);
      // this.$store.state.settings['dialogData'].modalType.isSearch = true;
      this.localData.isShowDialog = true;
      this.localData.modalType.isGalery = false;
      this.localData.modalType.isSearch = true;
      this.localData.data = {
        title: "Pencarian"
      }
      const modalData = {
        dialogData: this.localData
      }
      this.$store.dispatch('settings', modalData);
    },
  },
  computed: {
    ...mapState(['settings'])
  },
  components: {
    GeneralDialog,
    SearchingModal
  }
}
</script>