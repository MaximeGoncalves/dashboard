<template>
    <div class="w-100">
        <div class="col-12">
            <div class="card shadow" v-if="$gate.isAdmin()">
                <div class="card-header border-0">
                    <div class="row align-items-center mb-2">
                        <div class="col-6">
                            <h3 class="mb-0 col-12 col-md-7 mb-2 mb-md-0">Utilisateurs</h3>
                        </div>
                        <div class="col-6 text-right">
                            <div class="row align-items-center">
                                <div class="col-12 col-xl-12 mb-2 mb-md-0">
                                    <div class="form-group mb-0">
                                        <div class="input-group input-group-alternative">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fas fa-search"></i></span></div>
                                            <input placeholder="Rechercher" type="text" class="form-control"
                                                   @input="search(searchData)" v-model="searchData"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-4 mt-2 mt-xl-0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Fullname</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Society</th>
                                <th scope="col">Créé le</th>
                                <th scope="col">Modify</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(user, key) in users">
                                <td>{{ user.name }}</td>
                                <td>{{ user.fullname }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.role }}</td>
                                <td>{{ user.society.name }}</td>
                                <td>{{ user.created_at | formatDate }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <form>
                                                <a class="dropdown-item"
                                                   href="#" @click.prevent="EditUser(user.id)">Edit</a>
                                                <a class="dropdown-item" href="#"
                                                   @click.prevent="deleteUser(user.id, key)">
                                                    Delete
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <pagination :data="Object.assign({}, users)" @pagination-change-page="paginateUser" :limit="5"></pagination>
        </div>

        <footer class="footer"></footer>
        <!-- Modal -->
        <modal-user :user="user" :editMode="editMode" :society="society"></modal-user>

    </div>
</template>


<script>
import Store from '../Store/Store.js'
import {mapActions, mapState} from 'vuex'
import ModalUser from '../Users/UserModalComponent'

export default {
    store: Store,
    props: ['users', 'society'],
    data() {
        return {
            selected: "",
            searchData: '',
            editMode: false,
            user : {}
        }
    },
    components: {
        ModalUser
    },
    methods: {
        ...mapActions({
            deleteUser: 'user/DELETE_USER',
            paginateUser: 'user/PAGINATE_USERS',
            search: 'user/SEARCH',
        }),
        EditUser(user) {
            this.editMode = true
            axios.get('/api/user/' + user).then((user) => {
                this.user = user.data
            })
            $('#addNewUser').modal('show')
        },
    },
    computed: {
        ...mapState({
            // user: state=>state.user.user,
            // errors: state => state.errors,
        }),
    },
    created() {
        if (window.user.role === "user") {
            this.$router.push({path: '/'})
        }
        console.log(this.society)
        // this.$store.dispatch('user/GET_USERS');
        // this.$store.dispatch('society/GET_SOCIETIES');
    },
}
</script>

