<template>
  <v-footer padless color="primary">
    <div v-if="!settings.screenSize.type.islg">
      <!-- <DrawerNavigations @drawerData=drawerData :headerTitle="headerTitleValue" :drawer="drawerVal"/> -->
      <v-navigation-drawer v-model="drawer" fixed left class="rounded-r-lg" style="position: fixed; z-index: 888;">
        <v-row class="my-1 mx-1">
          <v-col md="10">
            <span>Menu</span>
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

        <v-list nav dense class="text-uppercase">
          <div v-for="(link, i) in menu.right" :key="i">

            <v-list-item v-if="!link.submenu" :to="link.path" :active-class="settings.color + ' white--text'">

              <v-list-item-title v-text="link.name" />
            </v-list-item>

            <v-list-group no-action v-else :key="link.name" :prepend-icon="link.icon"
              :event="(link.name === 'Media' ? '' : 'click')" :value="false"
              :active-class="(link.name === 'Media' ? 'blue-grey lighten-2' : settings.color)" class="white--text">
              <template v-slot:activator>
                <v-list-item-title>{{ link.name }}</v-list-item-title>
              </template>

              <v-list-item v-for="sublink in link.submenu" :to="sublink.path" :key="sublink.name"
                :active-class="settings.color">
                <v-list-item-title>{{ sublink.name }}</v-list-item-title>
              </v-list-item>
            </v-list-group>
          </div>
        </v-list>
      </v-navigation-drawer>  
       <v-bottom-navigation fixed :color="settings.color" height="60" style="position: fixed; z-index: 999;">
        <v-btn to="/">
          <span>Home</span>
          <v-icon>mdi-home</v-icon>
        </v-btn>
        <v-btn @click.stop="drawer = !drawer">
          <span>Menu</span>
          <v-icon>mdi-menu</v-icon>
        </v-btn>

        <v-btn to="/informasi">
          <span>Info</span>
          <v-icon>mdi-newspaper-variant-multiple</v-icon>
        </v-btn>
        <v-btn to="/jadwal">
          <span>Ibadah</span>
          <v-icon>mdi-book-open-variant</v-icon>
        </v-btn>

      </v-bottom-navigation>
    </div>
    <v-col class="text-center" cols="12">
      {{ new Date().getFullYear() }} — <strong>GKI Lahai-Roi Mefkajim Ayamaru</strong>
    </v-col>
  </v-footer>
</template>

<script>
import { mapState } from "vuex";
import DrawerNavigations from '@/components/C_DrawerNavigations.vue';

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
    headerTitleValue: "",
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
    menu: {}
  }),
  watch: {
    group() {
      this.drawer = false;

    },
  },
  created() {
    this.getMenuData();
  },
  methods: {
    searchingButton() {
      this.dialog = true;
    },
    getMenuData() {
      this.menu = this.$store.state.settings['menu'];
    },
    drawerData(val) {
      this.headerTitleValue = val.headerTitle;
      this.drawerVal = true;
    }
  },
  components: {
    DrawerNavigations
  }
}
</script>
<style>
.bg-active {
  color: white !important;
}
</style>