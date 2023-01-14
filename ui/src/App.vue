<template color="transparent">
  <v-app dark>
    <v-system-bar v-show="false" class="px-0 py-0 my-0 my-0">
      <img src="https://cdn.pixelfordinner.cloud/uploads/2014/11/voa_panel_sample-1024x0-c-f.jpg" width="100%" alt=""
        height="200%">
    </v-system-bar>
    <div v-if="isActiveWeb">
      <v-container>
        <C_Header />
      </v-container>

      <router-view />
      <v-btn v-scroll="onScroll" v-show="fab" fab fixed small bottom right :color="settings.color" @click="toTop"
        class="mb-15">
        <v-icon color="white">mdi-chevron-up</v-icon>
      </v-btn>

      <v-container> 
          <Footers class="mt-15" /> 
      </v-container>
    </div>
    <div v-else>
      <v-container class="text-center my-15">
        <h1 class="text-center">Selamat Datang di</h1>
        <img class="text-center" src="./assets/logoLight.png" />
      </v-container>
    </div>

  </v-app>
</template>

<script>
import C_Header from '@/components/C_Header.vue';
import Footers from '@/components/C_Footer.vue';
import { mapState } from "vuex";

export default {
  computed: {
    ...mapState(['settings'])
  },
  created() {
    this.setMobileDeviceSettings();
    this.loading = true;
    setTimeout(() => {
      this.loading = false;
    }, 1000);
  },
  components: {
    C_Header,
    Footers
  },
  data() {
    return {
      isActiveWeb: true,
      fab: false,
      loading: false
      // itemsMenu: []
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
  }
}
</script>

<style>
.on-hover {
  transition: all 0.5s ease;
}
</style>