require('./bootstrap');

import router from './routes';
import Vuex from 'vuex';
import Index from './Index';
import VueRouter from 'vue-router';
import FatalError from './shared/components/FatalError';
import Success from './shared/components/Success';
import ValidationErrors from "./shared/components/ValidationErrors";
// import moment from "moment";
import storeDef from './store';
import { before } from 'lodash';


window.Vue = require('vue');
Vue.use(VueRouter);
Vue.use(Vuex);

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

// Vue.filter('fromNow', value => moment(value).fromNow());

Vue.component('fatal-error', FatalError);
Vue.component('success', Success);
Vue.component('v-errors', ValidationErrors);
Vue.component('pagination', require('laravel-vue-pagination'));

const store = new Vuex.Store(storeDef);

// hanle every axios request
window.axios.interceptors.response.use(
    response => {
        return response;
    },
    error => {
        // if session is expired - logout
        if (401 === error.response.status) {
            router.push({ name: "home" })
            store.dispatch("logout");
        }

        // user doesn't have access 
        if (403 === error.response.status) {
            router.push({ name: "home" })
        }

        return Promise.reject(error);
    }
);

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        index: Index
    },
    beforeCreate() {
        // this.$store.dispatch('loadLastCheck'); // no need

        this.$store.dispatch('loadUser');
    },
});

