<template>
    <div>
        <div class="row mb-2">
            <div class="col-12">
                <h2 class="d-inline">Gestionnaire de licences</h2>
                <button class="btn btn-primary float-right" @click="addLicence">Ajouter</button>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div id="accordion">
                    <div class="card" v-for="(society,key) in data">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse"
                                        :data-target=" ('#collapse' +  key).replace(/ /g, '')" aria-expanded="true"
                                        aria-controls="collapseOne">
                                    {{ key }}
                                </button>
                            </h5>
                        </div>
                        <div :id=" ('collapse' + key).replace(/ /g, '')" class="collapse"
                             aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="scope-col">Utilisateur</th>
                                        <th class="scope-col">Category</th>
                                        <th class="scope-col">E-mail</th>
                                        <th class="scope-col">Password</th>
                                        <th class="scope-col">Licence</th>
                                        <th class="scope-col">Date d'ajout</th>
                                        <th class="scope-col text-center">Modify</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="licence in society">
                                        <td>{{ licence.user.name }}</td>
                                        <td>{{ licence.category.name }}</td>
                                        <td>{{ licence.email }}</td>
                                        <td>{{ licence.password }}</td>
                                        <td>{{ licence.licence }}</td>
                                        <td>{{ licence.created_at | formatDate }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form
                                                        action="https://argon-dashboard-laravel.creative-tim.com/user/8642"
                                                        method="post">
                                                        <input type="hidden" name="_token"
                                                               value="F3fqjyIFWnoHEzAeYoetOWiaHkr9ZdnoaXaxFsx7">
                                                        <input
                                                            type="hidden" name="_method" value="delete">
                                                        <a class="dropdown-item"
                                                           href="#" @click.prevent="editLicence(licence)">Edit</a>
                                                        <a class="dropdown-item" href="#"
                                                           @click.prevent="deleteLicence(licence)">
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
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addLicence" tabindex="-1" role="dialog" aria-labelledby="addLicence"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ editMode ? "Modification de : " +
                            licence.category.name + " de " + licence.user.fullname:
                            'Nouvelle licence'}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="editMode ? updateLicence(licence)  : createLicence()">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group " :class="errors.user ? 'has-danger' : ''">
                                        <v-select label="fullname"
                                                  :options="users"
                                                  v-model="licence.user"
                                                  class="form-control-alternative"
                                                  placeholder="Utilisateur"></v-select>
                                        <small v-if="errors.user" :class="errors.user ? 'text-danger' : ''"
                                               v-for="error in errors.user">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.category ? 'has-danger' : ''">
                                        <v-select label="name"
                                                  :options="categories"
                                                  v-model="licence.category"
                                                  class="form-control-alternative"
                                                  placeholder="Categorie"
                                                  taggable
                                                  push-tags
                                                  :clearable="true"
                                                  selectOnTab></v-select>
                                        <small v-if="errors.category" :class="errors.category ? 'text-danger' : ''"
                                               v-for="error in errors.category">{{ error }}
                                        </small>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.email ? 'has-danger' : ''">
                                        <input type="text" v-model="licence.email"
                                               class="form-control form-control-alternative" name="email"
                                               :class="errors.email ? 'is-invalid' : ''"
                                               placeholder="E-mail">
                                        <small v-if="errors.email" :class="errors.email ? 'text-danger' : ''"
                                               v-for="error in errors.email">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.password ? 'has-danger' : ''">
                                        <input type="text" v-model="licence.password"
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
                                <div class="col-12">
                                    <div class="form-group" :class="errors.licence ? 'has-danger' : ''">
                                        <input type="text" v-model="licence.licence"
                                               class="form-control form-control-alternative" name="licence"
                                               :class="errors.category ? 'is-invalid' : ''"
                                               placeholder="Licence">
                                        <small v-if="errors.licence" :class="errors.licence ? 'text-danger' : ''"
                                               v-for="error in errors.licence">{{ error }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">{{ editMode ? 'Enregistrer' :
                                    'Créer'}}
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
            data: [],
            visible: false,
            editMode: false,
            licence: {
                society: '',
                user: '',
                licence: '',
                email: '',
                password: '',
                category: '',

            },
            errors: [],
            users: [],
            categories: [],

        }
    },
    methods: {
        loadLicences() {
            axios.get('/api/licences').then(response => {
                console.log(response)
                // this.data = Object.keys(response.data.licences).map(item => response.data.licences[item]);
                this.data = response.data.licences
                this.users = response.data.users
                this.categories = response.data.categories
            })
        },
        addLicence() {
            this.editMode = false
            this.licence.society = ''
            this.licence.user = ''
            this.licence.licence = ''
            this.licence.email = ''
            this.licence.password = ''
            this.licence.category = ''
            $('#addLicence').modal('show')
        },
        createLicence() {
            this.$Progress.start();

            axios.post('/api/licences', {
                user: this.licence.user.id,
                email: this.licence.email,
                password: this.licence.password,
                society: this.licence.user.society_id,
                category: this.licence.category,
                licence: this.licence.licence,
            }).then(response => {
                console.log(response);
                this.$emit('addLicence')
                this.$Progress.finish();
                $('#addLicence').modal('hide')
                this.$toasted.global.flash({message: "La licence à été ajoutée."});
                this.errors = []

                this.licence.society = ''
                this.licence.user = ''
                this.licence.licence = ''
                this.licence.email = ''
                this.licence.password = ''
            }).catch(error => {

                this.errors = error.response.data.errors;
                this.$Progress.fail()

            })
        },
        updateLicence(licence) {
            this.$Progress.start();

            axios.put('/api/licences/' + licence.id, {
                user: this.licence.user.id,
                society: this.licence.user.society_id,
                category: this.licence.category.id,
                licence: this.licence.licence,
                email: this.licence.email,
                password: this.licence.password,
            }).then(response => {
                console.log(response);
                this.$emit('addLicence')
                this.$Progress.finish();
                $('#addLicence').modal('hide')
                this.$toasted.global.flash({message: "La licence à été modifiée."});
                this.errors = []
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail()
            })
        },
        deleteLicence(licence) {
            swal({
                title: "Etes vous sur ? ",
                text: "Cette manipulation est irréversible !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        this.$Progress.start();

                        axios.delete('/api/licences/' + licence.id).then(() => {
                            swal("La licence à été supprimé.", {
                                icon: "success",
                            });
                            this.$emit('addLicence')
                            this.$Progress.finish();
                        }).catch(error => {
                            this.$Progress.fail();
                            this.errors = error.response.data.message;
                            swal(this.errors);
                        })
                    } else {
                        swal("Ok on annule !");
                    }
                });
        },
        editLicence(licence) {
            this.editMode = true
            this.licence = licence
            $('#addLicence').modal('show')
        }
    },
    created() {
        if (window.user.role === "user") {
            this.$router.push({path: '/'})
        }
        this.loadLicences()
        this.$on('addLicence', () => {
            this.loadLicences()
        })

    },
}
</script>
