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
    path: '/about',
    name: 'About',
    component: () => import( /* webpackChunkName: "about" */ '../views/Agenda.vue')
  },
  {
    path: '/users',
    name: 'Users',
    component: () => import( /* webpackChunkName: "about" */ '../views/Users.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import( /* webpackChunkName: "about" */ '../views/Login.vue')
  },
  {
    path: '/informasi/detail/:id',
    name: 'News',
    component: () => import( /* webpackChunkName: "about" */ '../views/NewsDetail.vue')
  },
  {
    path: '/informasi',
    name: 'Category',
    component: () => import( /* webpackChunkName: "about" */ '../views/Category.vue')
  },
  {
    path: '/informasi/kategori',
    name: 'Category',
    component: () => import( /* webpackChunkName: "about" */ '../views/Category.vue')
  },
  {
    path: '/informasi/:id',
    name: 'kategori',
    component: () => import( /* webpackChunkName: "about" */ '../views/Category.vue')
  },
  {
    path: '/profil',
    name: 'Profil',
    component: () => import( /* webpackChunkName: "about" */ '../views/Profile.vue')
  },
  {
    path: '/agenda',
    name: 'Agenda',
    component: () => import( /* webpackChunkName: "about" */ '../views/Agenda.vue')
  },
  {
    path: '/agenda/detail/:id',
    name: 'Agenda',
    component: () => import( /* webpackChunkName: "about" */ '../views/AgendaDetail.vue')
  },
  {
    path: '/kontak',
    name: 'Kontak',
    component: () => import( /* webpackChunkName: "about" */ '../views/Contact.vue')
  },
  {
    path: '/dokumen',
    name: 'Document',
    component: () => import( /* webpackChunkName: "about" */ '../views/Document.vue')
  },
  {
    path: '/media/video',
    name: 'Video',
    component: () => import( /* webpackChunkName: "about" */ '../views/Video.vue')
  },
  {
    path: '/media/foto',
    name: 'Galeri',
    component: () => import( /* webpackChunkName: "about" */ '../views/Galery.vue')
  },
  {
    path: '/foto/album/:id',
    name: 'DetailGaleri',
    component: () => import( /* webpackChunkName: "about" */ '../views/Galery.vue')
  },
  {
    path: '/pencarian',
    name: 'Pencarian',
    component: () => import( /* webpackChunkName: "about" */ '../views/SearchPage.vue')
  },
  {
    path: '/jadwal',
    name: 'Pencarian',
    component: () => import( /* webpackChunkName: "about" */ '../views/JadwalIbadah.vue')
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