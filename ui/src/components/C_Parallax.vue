<template >
  <v-container>
    <v-parallax height="500" src="../assets/lahai9.jpg">
      <v-row align="center" justify="center">
        <v-col class="text-center" cols="12">
          <h1 class="text-h3 mb-4">
            Shalom, Mari Bergabung bersama Kami
          </h1>
          <h4 class="subheading">
            <v-btn :color="settings.color" class="white--text" rounded>
              Hubungi Kami <v-icon>mdi-phone</v-icon>
            </v-btn>
          </h4>
        </v-col>
      </v-row>
    </v-parallax>
  </v-container>
</template>


<script>
import { mapState } from "vuex";
export default {
  computed: {
    ...mapState(['settings'])
  },
  data: () => ({
    items: [
      {
        text: 'Beranda',
        disabled: false,
        href: '/',
        color: "#42A5F5"
      },
      {
        text: 'Informasi',
        disabled: true,
        href: 'breadcrumbs_link_2',
        color: "#BDBDBD"
      },
    ],
  }),
}
</script>
