import Vue from 'vue'
import AxiosPlugin from 'vue-axios-cors';
import axios from "axios";
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import httpClientAxios from './auth/http-common'
import Modal from './components/C_GeneralDialog.vue'
// import './registerServiceWorker'

Vue.config.productionTip = false

// let refresh = false;
// axios.defaults.baseURL = process.env.VUE_APP_PROXY_SERVICE_URL;
// axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
// axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

// axios.interceptors.response.use(resp => resp, async error => {
//   if (error.response.status === 401 && !refresh) {

//     if (error.response.data && error.response.data.error === "Expired token") {
//       refresh = true;
//       const param = {
//         "isWebAdmin": true
//       }
//       const {
//         status,
//         data
//       } = await axios.post('otentikasi', param, {
//         withCredentials: true
//       });
//       console.log(data.access_token);
//       if (status === 200) {
//         localStorage.setItem('token', data.access_token);
//         axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
//         console.log(error);
//         return axios(error.config);
//       } else {
//         return Promise.reject(error);
//       }
//     }
//   } else {
//     refresh = false;

//     return Promise.reject(error);
//   }
// });

new Vue({
  axios,
  router,
  store,
  vuetify,
  httpClientAxios,
  Modal,
  AxiosPlugin,
  render: h => h(App)
}).$mount('#app')