import Vue from 'vue'
import Vuex from 'vuex'
import users from './modules/users'
import settings from './modules/settings'
import breadcrumbs from './modules/breadcrumbs'

Vue.use(Vuex)


// export default createStore({
//   modules: {
//     users,
//     settings
//   },
// })
// const state = {
//   user: null
// }
export default new Vuex.Store({
  // state,
  modules: {
    users,
    settings,
    breadcrumbs
  }
})