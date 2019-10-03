import Vuex from 'vuex'
import axios from 'axios'
import Toasted from 'vue-toasted';
import VueProgressBar from 'vue-progressbar'
import swal from 'sweetalert';

const options = {
    color: '#2dce89',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}

const state = {
    users: {},
}

const getters = {
    users: state => state.users,
    user: state => state.user,
}

const mutations = {
    GET_USERS: (state, users) => {
        state.users = users
    },
    ADD_USER: (state, user) => {
        state.users.data.unshift(user)
    },
    DELETE_USER: (state, key) => {
        state.users.data.splice(key, 1)
        // s.items.splice(index, 1);
    },
    PAGINATE_USERS: (state, users) => {
        state.users = users
    },
    SEARCH: (state, users) => {
        state.users = users
    },
}

const actions = {
    ADD_USER: (store, payload) => {
        console.log(payload)
        Vue.prototype.$Progress.start()
        axios.post('/api/user', {
            name: payload.user.name,
            fullname: payload.user.fullname,
            email: payload.user.email,
            password: payload.user.password,
            role: payload.user.role,
            society: payload.society,
            profession: payload.user.profession,
            phone: payload.user.phone,
        }).then(response => {
            console.log(response)
            Vue.prototype.$Progress.finish()
            Vue.toasted.success("L'utilisateur à été créé.", {duration: 3000});
            store.commit('ADD_USER', response.data)
            store.commit('REMOVE_ERRORS', null, {root: true})
            $('#addNewUser').modal('hide')
        }).catch(errors => {
            console.log(errors)
            Vue.prototype.$Progress.fail()
            Vue.toasted.error(errors.response.data.errors, {duration: 3000});
            store.commit('ADD_ERRORS', errors.response.data.errors, {root: true})
        })
    },

    UPDATE_USER: (store, user) => {
        Vue.prototype.$Progress.start()
        axios.put('/api/user/' + user.id, {
            name: user.name,
            fullname: user.fullname,
            email: user.email,
            password: user.password,
            role: user.role,
            society: user.society_id,
            profession: user.profession,
            phone: user.phone,
        }).then((response) => {
            Vue.prototype.$Progress.finish()
            Vue.toasted.success("L'utilisateur à été mis à jour.", {duration: 3000});
            store.dispatch('GET_USERS', response.data.society_id)
            store.commit('REMOVE_ERRORS', null, {root: true})
            $('#addNewUser').modal('hide')
        }).catch(errors => {
            console.log(errors.response.data.errors)
            Vue.prototype.$Progress.fail()
            store.commit('ADD_ERRORS', errors.response.data.errors, {root: true})
        })
    },

    DELETE_USER: (store, user, key) => {
        swal({
            title: "Etes vous sur ? ",
            text: "Cette manipulation est irréversible !",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    axios.delete('/api/user/' + user).then(() => {
                        swal("L'utilisateur à été supprimé.", {
                            icon: "success",
                        });
                        store.commit('DELETE_USER', key)
                    }).catch(error => {
                        this.errors = error.response.data.message;
                        store.commit('ADD_ERRORS', error.response.data.message, {root: true})
                        swal(store.state.errors);
                    })
                } else {
                    swal("Annulation...");
                }
            });
    },

    PAGINATE_USERS: (store, page = 1) => {
        axios.get('api/user?page=' + page)
            .then(response => {
                store.commit('PAGINATE_USERS', response.data)
            });
    },

    GET_USERS: (context, id) => {
        axios.get('/api/user', {
            params: {
                society: id
            }
        }).then((response) => {
            context.commit('GET_USERS', response.data)
            // this.users = data.data.data
        });
    },

    SEARCH: (store, string) => {

        axios.get('/api/user/search', {
            params: {
                search: string
            }
        }).then(response => {
            console.log(response)
            store.commit('SEARCH', response.data)
        })
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
