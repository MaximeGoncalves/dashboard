import axios from 'axios'
import VueProgressBar from 'vue-progressbar'
import Toasted from 'vue-toasted';
import {router} from '../../../app'

const state = {
    tickets: {},
    ticket: {
        technician : {}
    },
    technicians: [],
    users: [],
    states: [],
    sources: [],
    society: [],
    types: [],
    actions: {},
    messages: {},
    unauthorized: false
}

const getters = {
    // tickets: state => state.tickets,
    // technicians: state => state.technicians,
    // users: state => state.users,
    // states: state => state.states,
    // sources: state => state.sources,
    // society: state => state.society,
    // types: state => state.types,
}

const mutations = {
    GET_TICKETS: (state, response) => {
        state.tickets = response.data.ticket;
        state.technicians = response.data.technician;
        state.users = response.data.user;
        state.states = response.data.states;
        state.sources = response.data.sources;
        state.society = response.data.society;
        state.types = response.data.types
    },
    GET_TICKET: (state, response) => {
            state.ticket = response.data.ticket,
            state.sources = response.data.sources,
            state.technicians = response.data.technicians,
            state.states = response.data.states,
            state.messages = response.data.messages,
            state.types = response.data.types,
            state.actions = response.data.actions
    },
    SET_UNAUTHORIZED: (state, value) => {
        state.unauthorized = value
    }
}

const actions = {
    GET_TICKETS: (context, payload) => {
        console.log(payload)
        axios.get('/api/tickets?page=' + payload.page, {
            params: {
                technician: payload.technician,
                state: payload.state,
                importance: payload.importance,
                source: payload.source,
                user: payload.user,
                society: payload.society,
                type: payload.type,
            }
        }).then(response => {
            context.commit('GET_TICKETS', response)
        });
    },
    GET_TICKET: (context, ticket) => {
        axios.get('/api/tickets/' + ticket).then(response => {

            console.log(response)
            if (response.data.unauthorized) {
                context.commit('SET_UNAUTHORIZED', true)
            }
            context.commit('GET_TICKET', response)

        })
    },
    UPDATE_TICKET: (context, ticket) => {
        console.log(ticket)
        axios.put('/api/tickets/' + ticket.id, {
            source: ticket.source.id,
            importance: ticket.importance,
            state: ticket.state.id,
            type: ticket.type ? ticket.type.id : null,
            technician: ticket.technician.user.id
        }).then(() => {
            Vue.toasted.success("Le ticket à été modifié !", {duration: 3000});
        })
    },
    DELETE_TICKET: (context, ticket) => {
        swal({
            title: "Etes vous sur ? ",
            text: "Cette manipulation est irréversible !",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then(willDelete => {
                if (willDelete) {
                    axios.delete('/api/tickets/' + ticket.id).then(() => {
                        swal("Le ticket à été supprimé", {
                            icon: "success",
                        });
                        router.push({path: "/tickets"})
                    }).catch(errors => {
                        swal(errors.response.data.message);
                    })
                } else {
                    swal("Ok on annule !");
                }
            });
    }
}


export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
