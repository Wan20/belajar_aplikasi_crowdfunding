import Vue from 'vue'
import router from './router'
import App from './App.vue'
import vuetify from './plugins/vuetify'

const app = new Vue({
    el: '#app',
    router,
    vuetify,
    components: {
        App
    },
});

