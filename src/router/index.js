import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes = [{
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/users',
    name: 'Users',
    component: () => import( /* webpackChunkName: "about" */ '../views/Users.vue')
  },
  {
    path: "/:catchAll(.*)",
    component: () => import( /* webpackChunkName: "about" */ '../views/Notfound.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router