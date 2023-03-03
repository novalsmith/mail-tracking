import Vue from 'vue'
import AxiosPlugin from 'vue-axios-cors';
import axios from "axios";
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import httpClientAxios from './auth/http-common'
import Modal from './components/C_GeneralDialog.vue'
import VuePapaParse from 'vue-papa-parse'
import './registerServiceWorker'
import mixins from 'vuetify-multiple-draggable-dialogs';

Vue.config.productionTip = false

// let refresh = false;
// axios.defaults.baseURL = process.env.VUE_APP_PROXY_SERVICE_URL;
// axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
// axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

// axios.interceptors.response.use(resp => resp, async error => {
//   console.log("masuk sini");
//   console.log(error.response.status);
//   if (error.response.status === 401) {

//     if (error.response.data && error.response.data.error === "Expired token") {
//       router.push('/logout')
//     }
//   }
// });
Vue.mixin(mixins);

new Vue({
  axios,
  router,
  store,
  vuetify,
  httpClientAxios,
  Modal,
  AxiosPlugin,
  VuePapaParse,
  render: h => h(App)
}).$mount('#app')