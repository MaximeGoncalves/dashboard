import Vue from 'vue'
import Vuex from 'vuex'

import user from './modules/User'
import society from './modules/Society'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        user,
        society
    },
    state: {
        errors: [],
    },
    mutations:{
        ADD_ERRORS : (state, errors) => {
            state.errors = errors
        },
        REMOVE_ERRORS : (state) => {
            state.errors = []
        }
    },
    getters: {
        errors: state => state.errors
    },
    strict: true,
})
