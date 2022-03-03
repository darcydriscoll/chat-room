import { createApp } from 'vue';
import { createWebHistory, createRouter } from 'vue-router';
import App from './App.vue';

import FetchFunc from './fetch_func.js';

// define route components
const Home = () => import('./components/pages/SignIn.vue');
const ChatRoom = () => import('./components/pages/ChatRoom.vue');

/**
 * Verify authentication of user.
 *
 * @return Promise<Object> wherein the object is the home route if the user is
 *  not authenticated.
 * @return bool True if the user is authenticated.
 * @throws Error if the server returns an unknown error or otherwise something
 *  bad happens.
 */
const verifyAccess = function () {
  const url = 'api/auto_sign_in.php';
  return FetchFunc.fetchJSON(url)
    .then(data => {
      if (!data.bool && data.msg === null) {
        return { name: 'home' };
      } else if (!data.bool) {
        console.error(`An unknown error was passed from the server: ${data.msg}`);
        throw new Error();
      }
      return true;
    })
    .catch(e => {
      console.error(e);
      throw new Error();
    });
};

// define routes
const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    beforeEnter: (to, from) => {
      if (from.name === 'chatroom') {
        to.meta.transitionName = 'dissolve-in-dissolve-down-out';
      } else {
        to.meta.transitionName = 'dissolve';
      }
    },
  },
  {
    path: '/chatroom',
    name: 'chatroom',
    component: ChatRoom,
    beforeEnter: async (to, from) => {
      // transition
      if (from.name === 'home') {
        to.meta.transitionName = 'dissolve-up-in-dissolve-out';
      } else {
        to.meta.transitionName = 'dissolve-up';
      }
      return await verifyAccess();
    },
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    redirect: { name: 'home' },
  },
];

// create router instance
const router = createRouter({
  history: createWebHistory(),
  routes,
});

// create and mount
const app = createApp(App);
app.use(router);
app.mount('#app');
