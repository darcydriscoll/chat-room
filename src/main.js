import { createApp } from 'vue'
import { createWebHistory, createRouter } from 'vue-router'
import App from './App.vue'

// define route components
const Home = () => import('./components/pages/SignIn')
const ChatRoom = () => import('./components/pages/ChatRoom')

// define routes
const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: {
      transition: 'signin',
    },
  },
  {
    path: '/chatroom',
    name: 'chatroom',
    component: ChatRoom,
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    redirect: { name: 'home' }
  }
]

// create router instance
const router = createRouter({
  history: createWebHistory(),
  routes,
})

// create and mount
const app = createApp(App)
app.use(router)
app.mount('#app')
