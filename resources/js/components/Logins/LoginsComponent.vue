<template>
    <div>
        <div class="row">
            <div class="col">
                <div class="card shadow" v-if="$gate.isAdmin()">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-7 mb-2 mb-md-0">
                                <h3 class="mb-0">Logins</h3>
                            </div>
                            <div class="col-12 col-md-5 text-right">
                                <div class="row align-items-center">
                                    <div class="col-12 col-md-9 mb-2 mb-md-0">
                                        <div class="form-group mb-0">
                                            <div class="input-group input-group-alternative">
                                                <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-search"></i></span></div>
                                                <input placeholder="Search" type="text" class="form-control" @input="search" v-model="searchData"></div>
                                        </div>
                                    </div>
                                    <!--<input type="text" @input="search" v-model="searchData">-->
                                   <div class="col-auto">
                                       <a href="#"
                                          class="btn btn-sm btn-primary" @click="NewModal">Add
                                           Login</a>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Service</th>
                                <th scope="col">Url</th>
                                <th scope="col">Identifiant</th>
                                <th scope="col">Password</th>
                                <th scope="col">Société</th>
                                <th scope="col">Modify</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="logins" v-for="login in logins">
                                <td>{{ login.name }}</td>
                                <td>{{ login.url }}</td>
                                <td>{{ login.username }}</td>
                                <td>{{ login.password }}</td>
                                <td>{{ login.society.name }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <form method="post">
                                                <input type="hidden" name="_token"
                                                       value="F3fqjyIFWnoHEzAeYoetOWiaHkr9ZdnoaXaxFsx7">
                                                <input
                                                    type="hidden" name="_method" value="delete">
                                                <a class="dropdown-item"
                                                   href="#" @click.prevent="EditLogin(login.id)">Edit</a>
                                                <a class="dropdown-item" href="#"
                                                   @click.prevent="deleteLogin(login.id)">
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
                <div v-else>
                    <not-found></not-found>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNewLogin" tabindex="-1" role="dialog" aria-labelledby="addNewLogin"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ editMode ? "Modification de : " +
                            login.name : 'Nouvelle utilisateur'}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="editMode ? Update(login.id)  : create()">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group " :class="errors.name ? 'has-danger' : ''">
                                        <input type="text" v-model="login.name"
                                               class="form-control form-control-alternative"
                                               :class="errors.name ? 'is-invalid' : ''" name="name"
                                               placeholder="Service">
                                        <small v-if="errors.name" :class="errors.name ? 'text-danger' : ''"
                                               v-for="error in errors.name">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.url ? 'has-danger' : ''">
                                        <input type="text" v-model="login.url"
                                               class="form-control form-control-alternative" name="url"
                                               :class="errors.url ? 'is-invalid' : ''"
                                               placeholder="Url">
                                        <small v-if="errors.url" :class="errors.url ? 'text-danger' : ''"
                                               v-for="error in errors.url">{{ error }}
                                        </small>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.username ? 'has-danger' : ''">
                                        <input type="text" v-model="login.username"
                                               class="form-control form-control-alternative" name="username"
                                               :class="errors.username ? 'is-invalid' : ''"
                                               placeholder="username">
                                        <small v-if="errors.username" :class="errors.username ? 'text-danger' : ''"
                                               v-for="error in errors.username">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.password ? 'has-danger' : ''">
                                        <input type="text" v-model="login.password"
                                               class="form-control form-control-alternative" name="password"
                                               :class="errors.password ? 'is-invalid' : ''"
                                               placeholder="password">
                                        <small v-if="errors.password" :class="errors.password ? 'text-danger' : ''"
                                               v-for="error in errors.password">{{ error }}
                                        </small>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.society ? 'has-danger' : ''">
                                        <v-select label="name"
                                                  :options="societies"
                                                  v-model="login.society"
                                                  class="form-control-alternative"
                                                  placeholder="Société"></v-select>
                                        <small v-if="errors.society" :class="errors.society ? 'text-danger' : ''"
                                               v-for="error in errors.society">{{ error }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">{{ editMode ? 'Save changes' :
                                    'Create login'}}
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
            login: {},
            societies: [],
            errors: [],
            logins: {},
            editMode: true,
            searchData: ''
        }
    },
    methods: {
        loadLogins() {
            axios.get('/api/logins').then((response) => {
                this.logins = response.data.logins
                this.societies = response.data.societies
            });
        },
        search() {
            axios.get('/api/logins/search', {
                params: {
                    search: this.searchData
                }
            }).then(response => {
                console.log(response.data)
                this.logins = response.data
            })
        },
        create() {
            this.$Progress.start();
            axios.post('/api/logins', {
                ...this.login
            }).then(response => {
                Fire.$emit('CreateLogin')
                this.$Progress.finish();
                $('#addNewLogin').modal('hide')
                this.$toasted.global.flash({message: "Le login à été créé."});
                this.errors = []
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail()
            })
        },
        async EditLogin(login) {
            this.errors = []
            this.editMode = true
            let getLogin = await axios.get('/api/logins/' + login).then((response) => {
                this.login = response.data.login
            })
            $('#addNewLogin').modal('show')
        },
        Update(login) {
            this.$Progress.start();
            axios.put('/api/logins/' + login, {
                ...this.login
            }).then((response) => {
                $('#addNewLogin').modal('hide')
                Fire.$emit('UpdateLogin')

                this.$toasted.global.flash({message: "L'utilisateur à été mis à jour"});
                this.$Progress.finish();
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail()
            })
        },
        NewModal() {
            this.editMode = false
            this.errors = []
            this.login.name = ''
            this.login.username = ''
            this.login.url = ''
            this.login.society = {}
            this.login.password = ''
            $('#addNewLogin').modal('show')
        },
        deleteLogin(id) {
            swal({
                title: "Etes vous sur ? ",
                text: "Cette manipulation est irréversible !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('/api/logins/' + id).then(() => {
                            swal("Le login à été supprimé.", {
                                icon: "success",
                            });
                            Fire.$emit('UpdateLogin')
                        }).catch(error => {
                            this.errors = error.response.data.message;
                            swal(this.errors);
                        })
                    } else {
                        swal("Ok on annule !");
                    }
                });
        },
    },
    created() {
        if (this.$gate.isAdmin()) {
            this.loadLogins()
            Fire.$on('CreateLogin', () => {
                this.loadLogins()
            })
            Fire.$on('UpdateLogin', () => {
                this.loadLogins()
            })
        }
    },
}
</script>

