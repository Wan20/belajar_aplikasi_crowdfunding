export default {
	state: {
        count: 0
	},

	getters: {
       getDonationTotalFormGetters(state){ 
           //take parameter state
          return state.count
       }
	},

	actions: {
	},

	mutations: {
        donate(state) {
            state.count++
        }
	}
}