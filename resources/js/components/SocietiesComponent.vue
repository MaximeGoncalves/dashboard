<template>
<div>
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Sociétés</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="#"
                               class="btn btn-sm btn-primary" @click="NewModal">Add
                                society</a>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Adresse</th>
                            <th scope="col">Ville</th>
                            <th scope="col">Code postale</th>
                            <th scope="col">Email</th>
                            <th scope="col">Téléphone</th>
                            <th scope="col">Fax</th>
                            <th scope="col">Modify</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="society in societies">
                            <td>{{ society.name }}</td>
                            <td>{{ society.address }}</td>
                            <td>{{ society.city }}</td>
                            <td>{{ society.cp }}</td>
                            <td>{{ society.email }}</td>
                            <td>{{ society.phone }}</td>
                            <td>{{ society.fax }}</td>
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
                                               href="#" @click.prevent="EditSociety(society.id)">Edit</a>
                                            <a class="dropdown-item" href="#"
                                               @click.prevent="deleteSociety(society.id)">
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
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addNewSociety" tabindex="-1" role="dialog" aria-labelledby="addNewSociety"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ editMode ? "Modification de : " +
                        society.name:
                        'Nouvelle Société'}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="editMode ? UpdateSociety(society.id)  : CreateSociety()">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group " :class="errors.name ? 'has-danger' : ''">
                                    <input type="text" v-model="society.name"
                                           class="form-control form-control-alternative"
                                           :class="errors.name ? 'is-invalid' : ''" name="name"
                                           placeholder="Nom de la société">
                                    <small v-if="errors.name" :class="errors.name ? 'text-danger' : ''"
                                           v-for="error in errors.name">{{ error }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group " :class="errors.address ? 'has-danger' : ''">
                                    <input type="text" v-model="society.address"
                                           class="form-control form-control-alternative"
                                           :class="errors.address ? 'is-invalid' : ''" name="address"
                                           placeholder="Adresse de la société">
                                    <small v-if="errors.address" :class="errors.address ? 'text-danger' : ''"
                                           v-for="error in errors.address">{{ error }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.city ? 'has-danger' : ''">
                                    <input type="text" v-model="society.city"
                                           class="form-control form-control-alternative" name="city"
                                           :class="errors.city ? 'is-invalid' : ''"
                                           placeholder="Ville">
                                    <small v-if="errors.city" :class="errors.city ? 'text-danger' : ''"
                                           v-for="error in errors.city">{{ error }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.cp ? 'has-danger' : ''">
                                    <input type="text" v-model="society.cp"
                                           class="form-control form-control-alternative" name="cp"
                                           :class="errors.email ? 'is-invalid' : ''"
                                           placeholder="Code postale">
                                    <small v-if="errors.cp" :class="errors.cp ? 'text-danger' : ''"
                                           v-for="error in errors.cp">{{ error }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.email ? 'has-danger' : ''">
                                    <input type="email" v-model="society.email"
                                           class="form-control form-control-alternative" name="email"
                                           :class="errors.email ? 'is-invalid' : ''"
                                           placeholder="E-mail">
                                    <small v-if="errors.email" :class="errors.email ? 'text-danger' : ''"
                                           v-for="error in errors.email">{{ error }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.phone ? 'has-danger' : ''">
                                    <input type="text" v-model="society.phone"
                                           class="form-control form-control-alternative" name="phone"
                                           :class="errors.phone ? 'is-invalid' : ''"
                                           placeholder="Télphone">
                                    <small v-if="errors.phone" :class="errors.phone ? 'text-danger' : ''"
                                           v-for="error in errors.phone">{{ error }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.fax ? 'has-danger' : ''">
                                    <input type="text" v-model="society.fax"
                                           class="form-control form-control-alternative" name="fax"
                                           :class="errors.fax ? 'is-invalid' : ''"
                                           placeholder="Fax">
                                    <small v-if="errors.fax" :class="errors.fax ? 'text-danger' : ''"
                                           v-for="error in errors.fax">{{ error }}
                                    </small>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">{{ editMode ? 'Save changes' : 'CreateUser'}}
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
            society: {},
            errors: [],
            societies: {},
            editMode: true,
            selected: "",
        }
    },
    methods: {
        UpdateSociety(society) {
            this.$Progress.start();
            axios.put('/api/society/' + society, {
                name: this.society.name,
                address: this.society.address,
                city: this.society.city,
                cp: this.society.cp,
                email: this.society.email,
                phone: this.society.phone,
                fax: this.society.fax,
            }).then((response) => {
                $('#addNewSociety').modal('hide')
                Fire.$emit('UpdateSociety')
                this.flash('Modifications effecutées !')
                this.$Progress.finish();
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail()
            })
        },
        EditSociety(society) {
            this.errors = []
            this.editMode = true
            axios.get('/api/society/' + society).then((society) => {
                console.log(society)
                this.society = society.data
            })
            $('#addNewSociety').modal('show')
        },
        NewModal() {
            this.editMode = false
            this.errors = []
            this.society.name = ''
            this.society.address = ''
            this.society.city = ''
            this.society.cp = ''
            this.society.email = ''
            this.society.phone = ''
            this.society.fax = ''
            $('#addNewSociety').modal('show')
        },
        CreateSociety() {
            this.$Progress.start();
            axios.post('/api/society', {
                name: this.society.name,
                address: this.society.address,
                city: this.society.city,
                cp: this.society.cp,
                email: this.society.email,
                phone: this.society.phone,
                fax: this.society.fax,
            }).then(response => {
                Fire.$emit('createSociety')
                this.$Progress.finish();
                $('#addNewSociety').modal('hide')

                this.flash('Société créée !')
                this.errors = []
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail()
            })
        },
        loadSocieties() {
            axios.get('/api/society').then((data) => {
                this.societies = data.data
            });
        },

        deleteSociety(id) {
            swal({
                title: "Etes vous sur ? ",
                text: "Cette manipulation est irréversible !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('/api/society/' + id).then(() => {
                            swal("La société à été supprimée.", {
                                icon: "success",
                            });
                            Fire.$emit('UpdateSociety')
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
        flash(message) {
            let toast = this.$toasted.show(message, {
                theme: "toasted-primary",
                position: "bottom-right",
                duration: 3000,
                className: 'bg-primary'
            });
        }
    },
    created() {
        if(window.user.role === "user"){
            this.$router.push({path: '/'})
        }
        this.loadSocieties()
        Fire.$on('createSociety', () => {
            this.loadSocieties()
        })
        Fire.$on('UpdateSociety', () => {
            this.loadSocieties()
        })
    },
}
</script>

