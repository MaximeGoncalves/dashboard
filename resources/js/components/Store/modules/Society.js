import axios from 'axios'
import VueProgressBar from 'vue-progressbar'
import Toasted from 'vue-toasted';

const state = {
    societies: [],
    society: {},
}

const getters = {
    societies: state => state.societies,
    society: state => state.society,
}

const mutations = {
    GET_SOCIETIES: (state, societies) => {
        state.societies = societies
    },
    GET_SOCIETY: (state, society) => {
        state.society = society
    },
    ADD_SOCIETY: (state, society) => {
        state.societies.unshift(society)
        state.users.data.unshift(user)
    }
}

const actions = {
    ADD_SOCIETY: (store, society) => {
        Vue.prototype.$Progress.start()
        axios.post('/api/society', {
            name: society.name,
            address: society.address,
            city: society.city,
            cp: society.cp,
            email: society.email,
            phone: society.phone,
            fax: society.fax,
        }).then(response => {
            Vue.prototype.$Progress.finish()
            Vue.toasted.success("La société à été créée.", {duration: 3000});
            $('#addNewSociety').modal('hide')
            store.commit('ADD_SOCIETY', response.data)
            store.commit('REMOVE_ERRORS', null, {root: true})
        }).catch((errors) => {
            Vue.prototype.$Progress.fail()
            store.commit('ADD_ERRORS', errors.response.data.errors, {root: true})
        })
    }
    ,
    GET_SOCIETIES: (store) => {
        axios.get('/api/society').then(response => {
            store.commit('GET_SOCIETIES', response.data)
        })
    },
    GET_SOCIETY: (store, society) => {
        axios.get('/api/society/' + society).then(response => {
            store.commit('GET_SOCIETY', response.data)
            console.log(response)
        })
    },
    UPDATE_SOCIETY: (store, society) => {
        return new Promise((resolve, reject) => {
            axios.put('/api/society/' + society.id, {
                name: society.name,
                address: society.address,
                city: society.city,
                cp: society.cp,
                email: society.email,
                phone: society.phone,
                fax: society.fax,
            }).then(() => {
                store.commit('REMOVE_ERRORS', null, {root: true})
                resolve()
            }).catch((errors) => {
                store.commit('ADD_ERRORS', errors.response.data.errors, {root: true})
                // return Promise.reject(new Error('erreur'))
                reject()
            })
        })
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
