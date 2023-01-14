<template>


  <div>
    <v-main>
      <v-container>
        <!-- <Carausel /> -->
        <HomeProfile />
        <!-- <HomeInformations /> -->
        <!-- <HomeAgenda /> -->
      </v-container>
    </v-main>
    <!-- <Parallax />
    <v-main class="mt-15">
      <v-container>
     <Poster />
        <HomeDocuments />
        <Galery />
        <Videos />    
      </v-container>
    </v-main> -->
  </div>


</template>

<script>
// @ is an alias to /src
import HomeProfile from '@/components/C_HomeProfile';
import { mapState } from "vuex";
export default {
  name: 'Home',
  components: {
    HomeProfile
  },
  data: () => ({
    loading: false
  }),
  created() {
    this.loading = true;
    setTimeout(() => {
      this.loading = false;
    }, 1000);


  },
  computed: {
    ...mapState(['settings'])
  },
  mounted() {
    this.toTop();
  },
  methods: {
    toTop() {
      this.$vuetify.goTo(0)
    }
  }
}
</script>
