<template>
    <div>
        <div class="row">
            <div class="col">
                <div class="card shadow" v-if="$gate.isAdmin()">
                    <div class="card-header border-0">
                        <div class="row align-items-center mb-2">
                            <div class="col-6">
                                <h3 class="mb-0 col-12 col-md-7 mb-2 mb-md-0">Utilisateurs</h3>
                            </div>
                            <div class="col-6 text-right">
                                <div class="row align-items-center">
                                    <div class="col-12 col-xl-8 mb-2 mb-md-0">
                                        <div class="form-group mb-0">
                                            <div class="input-group input-group-alternative">
                                                <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-search"></i></span></div>
                                                <input placeholder="Rechercher" type="text" class="form-control"
                                                       @input="search(searchData)" v-model="searchData"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-xl-4 mt-2 mt-xl-0">
                                        <a href="#"
                                           class="btn btn-default" @click="NewModal">Ajouter un utilisateur</a>
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
                                <tr v-for="(user, key) in users.data">
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


            <!-- Modal -->
            <div class="modal fade" id="addNewUser" tabindex="-1" role="dialog" aria-labelledby="addNewUser"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ editMode ? "Modification de : " +
                                user.fullname:
                                'Nouvelle utilisateur'}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="editMode ? UpdateUser(user.id)  : createUser()">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group " :class="errors.name ? 'has-danger' : ''">
                                            <input type="text" v-model="user.name"
                                                   class="form-control form-control-alternative"
                                                   :class="errors.name ? 'is-invalid' : ''" name="name"
                                                   placeholder="Identifiant de connexion">
                                            <small v-if="errors.name" :class="errors.name ? 'text-danger' : ''"
                                                   v-for="error in errors.name">{{ error }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" :class="errors.fullname ? 'has-danger' : ''">
                                            <input type="text" v-model="user.fullname"
                                                   class="form-control form-control-alternative" name="fullname"
                                                   :class="errors.fullname ? 'is-invalid' : ''"
                                                   placeholder="Nom Complet">
                                            <small v-if="errors.fullname" :class="errors.fullname ? 'text-danger' : ''"
                                                   v-for="error in errors.fullname">{{ error }}
                                            </small>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" :class="errors.email ? 'has-danger' : ''">
                                            <input type="email" v-model="user.email"
                                                   class="form-control form-control-alternative" name="email"
                                                   :class="errors.email ? 'is-invalid' : ''"
                                                   placeholder="Email">
                                            <small v-if="errors.email" :class="errors.email ? 'text-danger' : ''"
                                                   v-for="error in errors.email">{{ error }}
                                            </small>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" :class="errors.password ? 'has-danger' : ''">
                                            <input type="password" v-model="user.password"
                                                   class="form-control form-control-alternative" name="password"
                                                   :class="errors.password ? 'is-invalid' : ''"
                                                   placeholder="Mot de passe" v-if="!editMode">
                                            <input type="password" v-model="user.password"
                                                   class="form-control form-control-alternative" name="password"
                                                   :class="errors.password ? 'is-invalid' : ''"
                                                   placeholder="Mot de passe" v-if="editMode" disabled>
                                            <small v-if="errors.password" :class="errors.password ? 'text-danger' : ''"
                                                   v-for="error in errors.password">{{ error }}
                                            </small>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" :class="errors.profession ? 'has-danger' : ''">
                                            <input name="profession" id="profession"
                                                   v-model="user.profession"
                                                   class="form-control form-control-alternative"
                                                   :class="errors.profession ? 'is-invalid' : ''"
                                                   placeholder="Profession">
                                            <small v-if="errors.profession"
                                                   :class="errors.profession ? 'text-danger' : ''"
                                                   v-for="error in errors.profession"
                                            >{{ error }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" :class="errors.phone ? 'has-danger' : ''">
                                            <input name="phone" id="phone"
                                                   v-model="user.phone"
                                                   class="form-control form-control-alternative"
                                                   :class="errors.phone ? 'is-invalid' : ''"
                                                   placeholder="Téléphone">
                                            <small v-if="errors.phone" :class="errors.phone ? 'text-danger' : ''"
                                                   v-for="error in errors.phone">{{ error }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" :class="errors.society ? 'has-danger' : ''">
                                            <v-select label="name"
                                                      :options="societies"
                                                      v-model="user.society"
                                                      class="form-control-alternative"
                                                      placeholder="Société"></v-select>
                                            <small v-if="errors.society" :class="errors.society ? 'text-danger' : ''"
                                                   v-for="error in errors.society">{{ error }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" :class="errors.role ? 'has-danger' : ''">
                                            <v-select
                                                :options="['user',
                                                  'leader','admin']"
                                                v-model="user.role"
                                                class="form-control-alternative"
                                                placeholder="Rôle"></v-select>
                                            <small v-if="errors.role" :class="errors.role ? 'text-danger' : ''"
                                                   v-for="error in errors.role">{{
                                                error }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">{{ editMode ? 'Sauvegarder' :
                                        'Créer'}}
                                    </button>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import Store from '../Store/Store.js'
import {mapActions, mapState} from 'vuex'

export default {
    store: Store,
    data() {
        return {
            user: {
                society: {}
            },
            editMode: true,
            selected: "",
            searchData: ''
        }
    },

    methods: {
        ...mapActions({
            addUserStore: 'user/ADD_USER',
            deleteUser: 'user/DELETE_USER',
            paginateUser: 'user/PAGINATE_USERS',
            search: 'user/SEARCH'
        }),
        createUser() {
            this.addUserStore(this.user)
        },
        UpdateUser(user) {
            this.$store.dispatch('user/UPDATE_USER', this.user)
        },
        async EditUser(user) {
            this.editMode = true
            let getUser = await axios.get('/api/user/' + user).then((user) => {
                this.user = user.data
            })
            this.selected = this.user.society_id
            $('#addNewUser').modal('show')
        },
        NewModal() {
            this.editMode = false
            this.errors = []
            this.user.name = ''
            this.user.fullname = ''
            this.user.email = ''
            this.user.password = ''
            this.user.role = ''
            this.user.society = ''
            $('#addNewUser').modal('show')
        },
    },
    computed: {
        ...mapState({
            users: state => state.user.users,
            societies: state => state.society.societies,
            errors: state => state.errors,
        }),
    },
    created() {
        if (window.user.role === "user") {
            this.$router.push({path: '/'})
        }
        this.$store.dispatch('user/GET_USERS');
        this.$store.dispatch('society/GET_SOCIETIES');
    },
}
</script>

