import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import routerObject from "@/router";

Vue.use(VueRouter)

const routes = [{
    path: '/',
    name: 'Home',
    component: Home,
    beforeEnter: (to, from, next) => {
      console.log("masuk1 " + localStorage.getItem('token'));
      if (localStorage.getItem('token') == null) {
        // routerObject.push("/logout");
        next({
          path: '/logout',
          replace: true
        })
      } else {
        next();
      }
    }
  },
  {
    path: '/users',
    name: 'Users',
    component: () => import( /* webpackChunkName: "about" */ '../views/Users.vue'),
    beforeEnter: (to, from, next) => {
      console.log("masuk2 " + localStorage.getItem('token'));
      if (localStorage.getItem('token') == null) {
        // routerObject.push("/logout");
        next({
          path: '/logout',
          replace: true
        })
      } else {
        next();
      }
    }
  },
  {
    path: '/logout',
    name: 'Logout',
    component: () => import( /* webpackChunkName: "about" */ '../views/Logout.vue'),
    beforeEnter: (to, from, next) => {
      next();
      // console.log("masuk3 " + localStorage.getItem('token'));
      // if (localStorage.getItem('token') != null) {
      //   // routerObject.push("/logout");
      //   next({
      //     path: '/',
      //     replace: true
      //   })
      // }else{
      //   next();
      // } 
    }
  },
  {
    path: "/:catchAll(.*)",
    component: () => import( /* webpackChunkName: "about" */ '../views/Notfound.vue'),
    beforeEnter: (to, from, next) => {
      console.log("masuk4 " + localStorage.getItem('token'));
      if (localStorage.getItem('token') == null) {
        // routerObject.push("/logout");
        next({
          path: '/logout',
          replace: true
        })
      } else {
        next();
      }
    }
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router