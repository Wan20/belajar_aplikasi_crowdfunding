export default {
    namespaced: true,
	state: {
        transactions: 0
	},
	mutations: {
        donate: (state, payload) => {
            state.transactions++
        }
	},
	actions: {
	},
    getters: {
        transactions : state => state.transactions
	}
}