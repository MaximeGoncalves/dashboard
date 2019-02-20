<template>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow" v-if="$gate.isAdmin()">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Users</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="#"
                                   class="btn btn-sm btn-primary" @click="NewModal">Add
                                    user</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Fullname</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Society</th>
                                <th scope="col">Créé le</th>
                                <th scope="col">Modify</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in users">
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
                                            <form action="https://argon-dashboard-laravel.creative-tim.com/user/8642"
                                                  method="post">
                                                <input type="hidden" name="_token"
                                                       value="F3fqjyIFWnoHEzAeYoetOWiaHkr9ZdnoaXaxFsx7"> <input
                                                type="hidden" name="_method" value="delete">
                                                <a class="dropdown-item"
                                                   href="#" @click.prevent="EditUser(user.id)">Edit</a>
                                                <a class="dropdown-item" href="#"
                                                   @click.prevent="deleteUser(user.id)">
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
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">
                            <ul class="pagination" role="navigation">

                                <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                                    <span class="page-link" aria-hidden="true">‹</span>
                                </li>


                                <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                                <li class="page-item"><a class="page-link"
                                                         href="https://argon-dashboard-laravel.creative-tim.com/user?page=2">2</a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                                         href="https://argon-dashboard-laravel.creative-tim.com/user?page=3">3</a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                                         href="https://argon-dashboard-laravel.creative-tim.com/user?page=4">4</a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                                         href="https://argon-dashboard-laravel.creative-tim.com/user?page=5">5</a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                                         href="https://argon-dashboard-laravel.creative-tim.com/user?page=6">6</a>
                                </li>


                                <li class="page-item">
                                    <a class="page-link"
                                       href="https://argon-dashboard-laravel.creative-tim.com/user?page=2" rel="next"
                                       aria-label="Next »">›</a>
                                </li>
                            </ul>

                        </nav>
                    </div>
                </div>
                <div v-else>
                    <not-found></not-found>
                </div>
            </div>
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
                                               placeholder="Username">
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
                                               placeholder="Fullname">
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
                                        <input type="password" v-model="user.password" v-bind:disabled="editMode"
                                               class="form-control form-control-alternative" name="password"
                                               :class="errors.password ? 'is-invalid' : ''"
                                               placeholder="Password">
                                        <small v-if="errors.password" :class="errors.password ? 'text-danger' : ''"
                                               v-for="error in errors.password">{{ error }}
                                        </small>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.society ? 'has-danger' : ''">
                                        <select name="society" id="society"
                                                class="form-control form-control-alternative" v-model="user.society_id"
                                                :class="errors.society ? 'is-invalid' : ''">
                                            <option value="" selected>Selectionner une société</option>
                                            <option v-for="soc in society" :value="soc.id"
                                                    :selected="soc.id === user.society">{{ soc.name}}
                                            </option>
                                        </select>
                                        <small v-if="errors.society" :class="errors.society ? 'text-danger' : ''"
                                               v-for="error in errors.society">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.role ? 'has-danger' : ''">
                                        <select name="role" id="role" v-model="user.role"
                                                class="form-control form-control-alternative"
                                                :class="errors.role ? 'is-invalid' : ''">
                                            <option value="" selected>Select role</option>
                                            <option value="user">User</option>
                                            <option value="leader">Leader</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                        <small v-if="errors.role" :class="errors.role ? 'text-danger' : ''">{{
                                            errors.role }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">{{ editMode ? 'Save changes' :
                                    'CreateUser'}}
                                </button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: {},
            society: {},
            errors: [],
            users: {},
            editMode: true,
            selected: "",
        }
    },
    methods: {
        UpdateUser(user) {
            this.$Progress.start();
            axios.put('/api/user/' + user, {
                name: this.user.name,
                fullname: this.user.fullname,
                email: this.user.email,
                password: this.user.password,
                role: this.user.role,
                society: this.user.society_id,
            }).then((response) => {
                $('#addNewUser').modal('hide')
                Fire.$emit('UpdateUser')
                this.$toasted.global.flash({message : "L'utilisateur à été mis à jour"});

                this.$Progress.finish();
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail()
            })
        },
        async EditUser(user) {
            this.errors = []
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
            this.user.society_id = ''
            $('#addNewUser').modal('show')
        },
        createUser() {
            this.$Progress.start();
            axios.post('/api/user', {
                name: this.user.name,
                fullname: this.user.fullname,
                email: this.user.email,
                password: this.user.password,
                role: this.user.role,
                society: this.user.society_id,
            }).then(response => {
                Fire.$emit('CreateUser')
                this.$Progress.finish();
                $('#addNewUser').modal('hide')
                // let toast = this.$toasted.show("Utilisateur créé !", {
                //     theme: "toasted-primary",
                //     position: "bottom-right",
                //     duration: 3000,
                //     className: 'bg-primary'
                // });
                // this.flash('Utilisateur crée !')
                this.$toasted.global.flash({message : "L'utilisateur à été créé."});
                this.errors = []
            }).catch(error => {

                this.errors = error.response.data.errors;
                this.$Progress.fail()

            })
        },
        loadUsers() {

            axios.get('/api/user').then((data) => {
                this.users = data.data.data
            });

        },
        deleteUser(id) {
            swal({
                title: "Etes vous sur ? ",
                text: "Cette manipulation est irréversible !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('/api/user/' + id).then(() => {
                            swal("L'utilisateur à été supprimé.", {
                                icon: "success",
                            });
                            Fire.$emit('UpdateUser')
                        }).catch(error => {
                            this.errors = error.response.data.message;
                            swal(this.errors);
                        })
                    } else {
                        swal("Ok on annule !");
                    }
                });
        },
        getSociety() {
            axios.get('/api/society').then((society) => {
                this.society = society.data
            })

        },

    },
    created() {
        if (this.$gate.isAdmin()) {
            this.loadUsers()
            this.getSociety()
            Fire.$on('CreateUser', () => {
                this.loadUsers()
            })
            Fire.$on('UpdateUser', () => {
                this.loadUsers()
            })
        }
    },
}
</script>

