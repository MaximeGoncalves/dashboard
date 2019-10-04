import Vue from 'vue'
import Vuex from 'vuex'

import user from './modules/User'
import society from './modules/Society'
import ticket from './modules/Ticket'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        user,
        society,
        ticket
    },
    state: {
        errors: [],
    },
    mutations: {
        ADD_ERRORS: (state, errors) => {
            state.errors = errors
        },
        REMOVE_ERRORS: (state) => {
            state.errors = []
        }
    },
    getters: {
        errors: state => state.errors
    },

    /**
     * J'ai commenter le mode strict pour les tickets, actullement aucune solution pour editer un ticket depuis
     * la collection de cardTicket.
     */
    // strict: true,

})
