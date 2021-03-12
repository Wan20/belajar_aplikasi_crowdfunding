import Vue from 'vue'
import Vuex from 'vuex'
import transaction from './store/transaction'
import alert from './store/alert'
import auth from './store/auth'
import dialog from './store/dialog'
import VuexPersist from 'vuex-persist'

const vuexPersist = new VuexPersist({
    key     : 'sanbercode',
    storage : localStorage
})
Vue.use(Vuex)

export default new Vuex.Store({
    plugins: [vuexPersist.plugin],
	modules: {
        transaction,
        alert,
        auth,
        dialog
    }	
})