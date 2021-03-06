import Vue from 'vue'
import router from './router'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import './bootstrap'

//support vuex
import Vuex from 'vuex'
Vue.use(Vuex)
import storeData from "./store/index"

const store = new Vuex.Store(
   storeData
)

const app = new Vue({
    el: '#app',
    router,
    vuetify,
    store, //vuex
    components: {
        App
    },
});

