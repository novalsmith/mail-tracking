<template color="transparent">
  <v-app dark>
    <v-system-bar v-show="false" class="px-0 py-0 my-0 my-0">
      <img src="https://cdn.pixelfordinner.cloud/uploads/2014/11/voa_panel_sample-1024x0-c-f.jpg" width="100%" alt=""
        height="200%">
    </v-system-bar>
    <div :class="!initDataAfterLogin ? 'hero' : ''">
      <div v-if="!initDataAfterLogin">
        <C_Login />
      </div>
      <div v-else>
        <C_Header class="heroHome" />
        <router-view />
      </div>


      <v-btn v-scroll="onScroll" v-show="fab" fab fixed small bottom right :color="settings.color" @click="toTop"
        class="mb-15">
        <v-icon color="white">mdi-chevron-up</v-icon>
      </v-btn>

      <v-container>
        <Footers class="mt-15" />
      </v-container>
    </div>

  </v-app>
</template>

<script>
import C_Header from '@/components/C_Header.vue';
import Footers from '@/components/C_Footer.vue';
import C_Login from '@/components/C_Login.vue';
import { mapState } from "vuex";

export default {
  name: "app",
  data() {
    return {
      isLogin: false,
      isActiveWeb: true,
      fab: false,
      loading: false
    };
  },
  methods: {
    onScroll(e) {
      if (typeof window === 'undefined') return;
      const top = window.pageYOffset || e.target.scrollTop || 0;
      this.fab = top > 20;
    },
    toTop() {
      this.$vuetify.goTo(0)
    },
    isMobile() {
      if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        return true
      } else {
        return false
      }
    },
    setMobileDeviceSettings() {

      const datas = {
        isMobileData: this.isMobile()
      }
      this.$store.dispatch('settings', datas);
    }
  },

  computed: {
    ...mapState(['settings', 'lookups']),
    initDataAfterLogin() {
      var status = false;
      if (localStorage.getItem('isLogin') == 'true') {
        var lookupData = JSON.parse(localStorage.getItem('lookups'));
        // mapping header
        var headerLookup = lookupData.filter(val => val.type == "HEADER").map(result => { return result; });
        // mapping status
        var statusLookup = lookupData.filter(val => val.type == "STATUS").map(result => { return result; });
        // mapping type
        var typeLookup = lookupData.filter(val => val.type == "TYPE").map(result => { return result; });
        // mapping type
        var accessLookup = lookupData.filter(val => val.type == "ACCESS").map(result => { return result; });
        // mapping level
        var levelLookup = lookupData.filter(val => val.type == "LEVEL").map(result => { return result; });
        // mapping desc
        var descLookup = lookupData.filter(val => val.type == "DESC").map(result => { return result; });
        // mapping actionFollowup
        var actionLookup = lookupData.filter(val => val.type == "ACTION").map(result => { return result; });
        // mapping filePrefixLookup
        var filePrefixLookup = lookupData.filter(val => val.type == "FILEPREFIX").map(result => { return result; });

        var mapping = {
          header: headerLookup,
          status: statusLookup,
          type: typeLookup,
          access: accessLookup,
          level: levelLookup,
          desc: descLookup,
          action: actionLookup,
          filePrefix:filePrefixLookup
        };
        this.$store.dispatch('lookups', mapping);
        status = true;
      }
      return status;
    }
  },
  watch: {
    $route: {
      immediate: true,
      handler(to, from) {
        document.title = to.meta.title || 'Mail Track Minerba';
      }
    },
  },
  created() {
    this.setMobileDeviceSettings();
  },
  components: {
    C_Header,
    Footers,
    C_Login
  },
}
</script>

<style lang="css" scoped>
.on-hover {
  transition: all 0.5s ease;
}

.hero {
  background: url('./assets/minerbalogin.jpg');
  background-size: cover;
  background-position-y: bottom;
  height: 45vh;
}

.heroHome {
  background: url('./assets/DashboardMailtracking.jpg');
  background-size: cover;
  background-position-y: bottom;
}
</style>