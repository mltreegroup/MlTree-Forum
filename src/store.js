import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
	state: {
		isDark: false,
		userStatus: {
			name: null,
			status: null,
			group: null
		}
	},
	mutations: {
		changeDark(state) {
			state.isDark = !state.isDark;
		},
		userLogin(state){
			
		}
	},
	actions: {

	}
})
