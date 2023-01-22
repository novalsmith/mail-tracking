import Vue from 'vue'
import Vuex from 'vuex'
import employees from './modules/employee'
import settings from './modules/settings'
import breadcrumbs from './modules/breadcrumbs'
import inboxs from './modules/inbox'
import trackings from './modules/tracking'
import access from './modules/access'
import unit from './modules/unit'



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
    employees,
    settings,
    breadcrumbs,
    inboxs,
    trackings,
    access,
    unit
  }
})