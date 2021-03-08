import Vue from 'vue'
import Vuex from 'vuex'
import transaction from './store/transaction'
import alert from './store/alert'

Vue.use(Vuex)

export default new Vuex.Store({
	modules: {
        transaction,
        alert
    }	
})