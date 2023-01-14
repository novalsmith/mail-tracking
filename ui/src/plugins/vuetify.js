import Vue from 'vue';
import Vuetify from 'vuetify/lib/framework';
import colors from 'vuetify/lib/util/colors'


Vue.use(Vuetify);

export default new Vuetify({
    icons:{
        iconfont: "fa"
    },
    theme: {
        themes: {
          light: {
            primary: colors.teal.lighten-5,
            secondary: colors.deepOrange.lighten1
          },
          dark: {
            primary: '#121212',
            secondary: '#FFFFFF'
          },
        },
      },
});
