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
    component: () => import('../views/Users.vue'),
    beforeEnter: (to, from, next) => {
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
    path: '/access',
    name: 'Access',
    component: () => import('../views/Access.vue'),
    beforeEnter: (to, from, next) => {
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
    path: '/unit',
    name: 'Unit',
    component: () => import('../views/Unit.vue'),
    beforeEnter: (to, from, next) => {
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
    path: '/employee',
    name: 'Employee',
    component: () => import('../views/Employee.vue'),
    beforeEnter: (to, from, next) => {
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
    path: '/nadine',
    name: 'Nadine',
    component: () => import('../views/Tracking.vue'),
    beforeEnter: (to, from, next) => {
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
    path: '/inbox',
    name: 'Inbox',
    component: () => import('../views/Inbox.vue'),
    beforeEnter: (to, from, next) => {
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
    component: () => import('../views/Logout.vue'),
    beforeEnter: (to, from, next) => {
      next();
    }
  },
  {
    path: "/:catchAll(.*)",
    component: () => import('../views/Notfound.vue'),
    beforeEnter: (to, from, next) => {
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