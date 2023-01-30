<template>
  <v-footer padless color="transparent">
    <!-- menus -->
    <div v-if="!settings.screenSize.type.islg">
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
          <!-- <v-btn :small="styleData.small" :rounded="styleData.rounded" v-for="link in renderMenu"
                                :active-class="('white--text ' + settings.color)" :key="link.name" :to="link.path" text
                                class="my-4" :outlined="link.outlined" v-show="link.isShow">

                                <span>
                                    {{ link.name }}
                                </span>
                            </v-btn> -->

          <div v-for="(link, i) in renderMenu" :key="i">

            <v-list-item :to="link.path" :active-class="settings.color + ' white--text'">

              <v-list-item-title v-text="link.name" />
            </v-list-item>
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

      </v-bottom-navigation>
    </div>
    <v-col class="text-center" cols="12">
      {{ new Date().getFullYear() }} — <strong>Mail Track Minerba</strong>
      <div><small>v1.0</small></div>
    </v-col>
  </v-footer>
</template>

<script>
import { mapState } from "vuex";

export default {
  data: () => ({
    drawer: false,
    group: null,
    dialog: false,
    notifications: false,
    sound: true,
    widgets: false,
    headerTitleValue: "",
    users: [],
    menu: {}
  }),
  watch: {
    group() {
      this.drawer = false;

    },
  },
  created() {
    this.getMenuData();
    var listData = JSON.parse(localStorage.getItem('userData'));
    this.users = listData != undefined && listData.user ? listData.user : [];
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
  computed: {
    ...mapState(['settings']),
    renderMenu() {
      var level = parseInt(this.users.roleLevel);
      var menus = [
        {
          name: "Home",
          path: "/",
          icon: "mdi-home-roof",
          isShow: ((level == 99 || level == 0 || level == 1 || level == 2 || level == 3 || level == 4 || level == 5) ? true : false)
        },
        {
          name: "Inbox",
          path: "/inbox",
          icon: "mdi-phone",
          isShow: ((level == 99 || level == 1 || level == 2 || level == 3 || level == 4 || level == 5) ? true : false)
        },
        {
          name: "Outbox",
          path: "/outbox",
          icon: "mdi-phone",
          isShow: ((level == 99 || level == 1 || level == 2 || level == 3 || level == 4 || level == 5) ? true : false)
        },
        {
          name: "Nadine",
          path: "/nadine",
          icon: "mdi-phone",
          isShow: ((level == 99 || level == 0) ? true : false)
        },
        {
          name: "Employee",
          path: "/employee",
          icon: "mdi-phone",
          isShow: ((level == 99) ? true : false)
        },
        {
          name: "Access",
          path: "/access",
          icon: "mdi-phone",
          isShow: ((level == 99) ? true : false)
        },
        {
          name: "Unit",
          path: "/unit",
          icon: "mdi-phone",
          isShow: ((level == 99) ? true : false)
        },
        {
          name: "Report",
          path: "/report",
          icon: "mdi-phone",
          isShow: ((level == 99) ? true : false)
        },
        {
          name: "Profile",
          path: "/profile",
          icon: "mdi-cog-outline",
          isShow: ((level == 99) ? true : false)
        },
        {
          name: "Logout",
          path: "/logout",
          icon: "mdi-logout-variant",
          isShow: ((level == 99) ? true : false)
        },
      ];
      return menus;
    }
  },
}
</script>
<style>
.bg-active {
  color: white !important;
}
</style>