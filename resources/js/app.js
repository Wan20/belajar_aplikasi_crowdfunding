import Vue from 'vue'
import router from './router'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import './bootstrap'

const app = new Vue({
    el: '#app',
    router,
    vuetify,
    components: {
        App
    },
});

