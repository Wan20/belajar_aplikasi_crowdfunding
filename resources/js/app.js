import Vue from 'vue'
import router from './router'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import './bootstrap'
import store from './store'

const app = new Vue({
    el: '#app',
    router,
    vuetify,
    store, //vuex
    components: {
        App
    },
});

