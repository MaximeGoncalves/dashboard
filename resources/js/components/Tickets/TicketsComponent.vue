<template>
    <div>
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card shadow" v-if="!card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Users</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="#"
                                   class="btn btn-sm btn-primary">Add
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
                                <th scope="col">ID</th>
                                <th scope="col">Objet</th>
                                <th scope="col">Demandeur</th>
                                <th scope="col">Priorité</th>
                                <th scope="col">Demande</th>
                                <th scope="col">Modify</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="ticket in tickets">
                                <td>{{ticket.id}}</td>
                                <td>{{ticket.topic}}</td>
                                <td>{{ticket.user.fullname}}</td>
                                <td>priorité</td>
                                <td>{{ticket.created_at}}</td>
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
                                                   href="#">Edit</a>
                                                <a class="dropdown-item" href="#"
                                                >
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
                <div class="card shadow mb-2 px-5 py-5" v-if="card" v-for="ticket in tickets.data">
                    <div class="row">
                        <div class="col-md-9 col-12">
                            <router-link :to="{name: 'ticket', params: {id: ticket.id }}">

                                <h2 class="d-inline">{{ ticket.topic }}</h2>
                            </router-link>

                            <p class="d-inline"> - #{{ ticket.id }} </p>
                            <div v-if='ticket.state' class="d-inline">
                                <i class="fas fa-bookmark text-info"
                                   v-if="ticket.state.id == '1'" data-toggle="tooltip" data-placement="top"
                                   title="Pending"></i>
                                <i class="fas fa-bookmark text-success"
                                   v-if="ticket.state.id == '2'" data-toggle="tooltip" data-placement="top"
                                   title="Open"></i>
                                <i class="fas fa-bookmark text-warning"
                                   v-if="ticket.state.id == '3'" data-toggle="tooltip" data-placement="top"
                                   title="Wainting customer"></i>
                                <i class="fas fa-bookmark text-dark"
                                   v-if="ticket.state.id == '4'" data-toggle="tooltip" data-placement="top"
                                   title="Closed"></i>
                            </div>
                            <p v-if="ticket['user']">{{ ticket['user'].fullname }} - {{ ticket.created_at | formatDate
                                }} </p>
                            <h4>Description :</h4>
                            <p v-html="ticket.description"></p>
                            <div class="mt-3" v-if="ticket.technician">
                                <img :src="getProfilePhoto(ticket.technician)"
                                     class="avatar avatar-sm mr-3"
                                     data-toggle="tooltip"
                                     data-placement="bottom"
                                     :title="ticket.technician.user.name">
                                <small class="pr-2 text-nowrap d-inline-block" v-if="ticket.actions.length == 1">
                                    <i class="fas fa-list-ul text-muted mr-1"></i>
                                    <b>0</b> Actions
                                </small>
                                <small class="pr-2 text-nowrap d-inline-block" v-else-if="ticket.actions.length == 0">
                                    <i class="fas fa-list-ul text-muted mr-1"></i>
                                    <b>0</b> Actions
                                </small>
                                <small class="pr-2 text-nowrap d-inline-block" v-else-if="ticket.actions.length > 1">
                                    <i class="fas fa-list-ul text-muted mr-1"></i>
                                    <b>{{ ticket.actions.length }}</b> Actions
                                </small>
                                <small class="pr-2 text-nowrap d-inline-block">
                                    <i class="fas fa-comments text-muted mr-1"></i>
                                    <b>{{ ticket.messages.length}}</b> Commentaires
                                </small>
                            </div>
                        </div>
                        <div class="col-md-3 col-12 d-flex flex-column justify-content-center" v-if="$gate.isAdmin()">
                            <div class="d-flex align-items-center">
                                <div v-if='ticket.state'>
                                    <i class="fas fa-bookmark text-info"
                                       v-if="ticket.state.id == '1'"></i>
                                    <i class="fas fa-bookmark text-success"
                                       v-if="ticket['state'].id == '2'"></i>
                                    <i class="fas fa-bookmark text-warning"
                                       v-if="ticket['state'].id == '3'"></i>
                                    <i class="fas fa-bookmark text-dark"
                                       v-if="ticket['state'].id == '4'"></i>
                                </div>
                                <v-select
                                    label="name"
                                    class="d-inline"
                                    placeholder="Etat"
                                    :clearable=false
                                    v-model="ticket.state"
                                    :options="states"
                                    @input="updateTicket(ticket)"
                                ></v-select>
                            </div>

                            <div class="d-flex align-items-center">
                                <div v-if="ticket.source">
                                    <i class="fas fa-tablet-alt text-primary" v-if="ticket['source'].id == '1'"></i>
                                    <i class="fas fa-envelope text-primary" v-if="ticket['source'].id == '2'"></i>
                                    <i class="fas fa-phone text-primary" v-if="ticket['source'].id == '3'"></i>
                                    <i class="fas fa-comments text-primary" v-if="ticket['source'].id == '4'"></i>
                                </div>
                                <v-select
                                    label="name"
                                    placeholder="Source"
                                    v-model="ticket.source"
                                    :clearable=false
                                    :options="sources"
                                    @input="updateTicket(ticket)"
                                ></v-select>
                            </div>

                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle text-success"
                                   v-if="ticket.importance == 'Normal'"></i>
                                <i class="fas fa-exclamation-triangle text-red"
                                   v-if="ticket.importance == 'Urgent'"></i>
                                <v-select
                                    placeholder="Importance"
                                    :clearable=false
                                    v-model="ticket.importance"
                                    :options="['Normal', 'Urgent']"
                                    @input="updateTicket(ticket)"
                                ></v-select>
                            </div>
                        </div>
                    </div>
                </div>
                <pagination :data="tickets" @pagination-change-page="getResults" :limit="9"></pagination>
            </div>
            <div class="col-12 flex-column-reverse col-md-4">
                <div class="card shadow mb-2 px-5 py-5">
                    <div class="row">
                        <div class="col-8">
                            <h3 class="mb-0">FILTERS</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="#"
                               class="btn btn-sm btn-primary" @click="NewModal">Add ticket</a>
                        </div>
                    </div>
                    <div class="row">
                        <form class="w-100" @submit.prevent="search">
                            <br>
                            <h4>Techniciens</h4>
                            <v-select class="form-control-alternative " multiple label="fullname"
                                      placeholder="Techniciens"
                                      v-model="filter.technician"
                                      :options="technicians"
                            ></v-select>

                            <h4 class="mt-4">Etat</h4>
                            <v-select class="form-control-alternative mt-2" multiple placeholder="Etat"
                                      label="name"
                                      v-model="filter.state"
                                      :options="states"></v-select>

                            <h4 class="mt-4">Priorité</h4>
                            <v-select class="form-control-alternative mt-2" placeholder="Priorité"
                                      v-model="filter.importance" :options="['Normal','Urgent']" clearable></v-select>

                            <h4 class="mt-4">Sources</h4>
                            <v-select class="form-control-alternative mt-2" label="name" placeholder="Sources"
                                      v-model="filter.source" :options="sources" clearable></v-select>
                            <div v-if="$gate.isAdmin()">
                                <h4 class="mt-4">Société</h4>
                                <v-select class="form-control-alternative mt-2" label="name" placeholder="Société"
                                          v-model="filter.society" :options="society" clearable></v-select>
                                <h4 class="mt-4">Demandeur</h4>
                                <v-select class="form-control-alternative mt-2" label="fullname" placeholder="Demandeur"
                                          v-model="filter.user" :options="availableUser" clearable></v-select>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3 float-right">
                                Search
                            </button>
                        </form>
                    </div>
                    <div class="row">

                    </div>
                    <div class="row">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addNewTicket" tabindex="-1" role="dialog" aria-labelledby="addNewTicket"
             aria-hidden="true">
            <div class="ticket modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Créer un nouveau ticket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="createTicket()" enctype="multipart/form-data" id="form">
                            <input type="hidden" name="_token"
                                   value="F3fqjyIFWnoHEzAeYoetOWiaHkr9ZdnoaXaxFsx7">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group " :class="errors.topic ? 'has-danger' : ''">
                                        <input type="text"
                                               v-model="ticket.topic"
                                               class="form-control form-control-alternative"
                                               :class="errors.topic ? 'is-invalid' : ''" name="topic"
                                               placeholder="Sujet de la demande">
                                        <small v-if="errors.topic" :class="errors.topic ? 'text-danger' : ''"
                                               v-for="error in errors.topic">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group " :class="errors.description ? 'has-danger' : ''">
                                    <textarea name="description" id="description" cols="30" rows="10"
                                              placeholder="Quel est votre problème ?"
                                              class="form-control form-control-alternative"
                                              :class="errors.description ? 'is-invalid' : ''"
                                              v-model="ticket.description"></textarea>
                                        <small v-if="errors.description"
                                               :class="errors.description ? 'text-danger' : ''"
                                               v-for="error in errors.description">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.importance ? 'has-danger' : ''">
                                        <v-select label="name"
                                                  :options="['Normal', 'Urgent']"
                                                  clearable
                                                  class="form-control-alternative"
                                                  v-model="ticket.importance"
                                                  placeholder="Priorité"></v-select>
                                        <small v-if="errors.importance" :class="errors.importance ? 'text-danger' : ''"
                                               v-for="error in errors.importance">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="file"
                                           id="files"
                                           class="form-control form-control-alternative"
                                           :class="errors.files ? 'is-invalid' : ''"
                                           ref="files"
                                           placeholder="Priorité"
                                           v-on:change="handleFileUploads"
                                           multiple>
                                    <small v-if="errors.files" :class="errors.files ? 'text-danger' : ''"
                                           v-for="error in errors.files">{{ error }}
                                    </small>
                                </div>
                                <div class="col-md-4" v-if="$gate.isAdmin()">
                                    <div class="form-group" :class="errors.user ? 'has-danger' : ''">
                                        <v-select label="fullname"
                                                  v-model="ticket.user"
                                                  :options="users"
                                                  clearable
                                                  class="form-control-alternative"
                                                  placeholder="Utilisateur"></v-select>
                                        <small v-if="errors.user" :class="errors.user ? 'text-danger' : ''"
                                               v-for="error in errors.user">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4" v-if="$gate.isAdmin()">
                                    <div class="form-group" :class="errors.technician ? 'has-danger' : ''">
                                        <v-select label="name"
                                                  :options="technicians"
                                                  v-model="ticket.technician"
                                                  clearable
                                                  class="form-control-alternative"
                                                  placeholder="Technicien"></v-select>
                                        <small v-if="errors.technician"
                                               :class="errors.technician ? 'text-danger' : ''"
                                               v-for="error in errors.technician">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4" v-if="$gate.isAdmin()">
                                    <div class="form-group" :class="errors.source ? 'has-danger' : ''">
                                        <v-select label="name"
                                                  :options="sources"
                                                  v-model="ticket.source"
                                                  clearable
                                                  class="form-control-alternative"
                                                  placeholder="Sources"></v-select>
                                        <small v-if="errors.source" :class="errors.source ? 'text-danger' : ''"
                                               v-for="error in errors.source">{{ error }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Créer
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
    props: {
        placeholder: 'placeholder',
    },
    data() {
        return {
            tickets: {},
            ticket: {},
            files: [],
            society: [],
            technicians: [],
            users: [],
            sources: [],
            states: [],
            errors: [],
            filter: {
                importance: null,
                technician: [],
                state: [
                    {"id": 1, "name": "Pending"},
                    {"id": 2, "name": "Open"}
                ],
                user: {},
                source: {},
                society: null,
            },
            card: true,
            photo: '',
        }
    },
    methods: {
        Filter() {
            axios.get('/api/tickets', {
                importance: this.filter.importance,
                technician: this.filter.technician,
                state: this.filter.state,
                type: this.filter.type,
                society: this.filter.society,
            }).then(result => {
                console.log(result)
            })
        },
        uploadFiles() {
            let formData = new FormData();
            for (var i = 0; i < this.files.length; i++) {
                let file = this.files[i];

                formData.append('files[' + i + ']', file);
            }
            axios.post('/api/uploadFiles', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(result => {
                console.log(result)
                console.log('SUCCESS!!');
            }).catch(function () {
                console.log('FAILURE!!');
            });
        },
        createTicket() {
            this.$Progress.start();

            if (window.user.role === 'admin') {
                let formData = new FormData();
                if (this.ticket.topic) {
                    formData.append('topic', this.ticket.topic)
                }
                if (this.ticket.description) {
                    formData.append('description', this.ticket.description)
                }
                if (this.ticket.importance) {
                    formData.append('importance', this.ticket.importance)
                }
                if (this.ticket.user) {
                    formData.append('user', this.ticket.user.id)
                }
                if (this.ticket.technician) {
                    formData.append('technician', this.ticket.technician.id)
                }
                if (this.ticket.source) {
                    formData.append('source', this.ticket.source.id)
                }
                for (var i = 0; i < this.files.length; i++) {
                    let file = this.files[i];
                    formData.append('files[' + i + ']', file);
                }
                axios.post('/api/tickets', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(result => {
                    $('.modal').modal('hide')
                    this.ticket = {}
                    Fire.$emit('createTicket')
                    this.$toasted.global.flash({message: "Le ticket à été créé."});
                    this.$Progress.finish();
                    console.log(result)
                }).catch(error => {
                    console.log(error)
                    this.errors = error.response.data.errors;
                    this.$Progress.fail()
                });
            } else {
                let formData = new FormData();
                formData.set('topic', this.ticket.topic)
                formData.set('description', this.ticket.description)
                formData.set('importance', this.ticket.importance)
                for (var i = 0; i < this.files.length; i++) {
                    let file = this.files[i];
                    formData.append('files[' + i + ']', file);
                }
                axios.post('/api/tickets', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(result => {
                    $('.modal').modal('hide')
                    this.ticket = {}
                    Fire.$emit('createTicket')
                    this.$toasted.global.flash({message: "Le ticket à été créé."});
                    this.$Progress.finish();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    this.$Progress.fail()
                })
            }
        },
        updateTicket(ticket) {
            console.log(ticket)
            let tech
            if (ticket.technician) {
                tech = ticket.technician.user.id
            }

            axios.put('/api/tickets/' + ticket.id, {
                source: ticket.source.id,
                importance: ticket.importance,
                state: ticket.state.id,
                technician: tech
            }).then(result => {
                this.$toasted.global.flash({message: "Le ticket à été mis à jour"});
                this.$Progress.finish()
                Fire.$emit('createTicket')
            })
        },
        handleFileUploads(e) {
            // for (var i = 0; i < event.target.files.length; i++) {
            //     this.ticket.files[i] = event.target.files[i]
            //     console.log('file Object:==>', this.ticket.files);
            // }
            // this.ticket.files = event.target.files
            // this.files = this.$refs.files.files
            let files = e.target.files || e.dataTransfer.files;
            for (let i = files.length - 1; i >= 0; i--) {
                this.files.push(files[i]);
            }
            // let uploadedFiles = this.$refs.files.files;
            //
            // for(var i = 0; i < uploadedFiles.length; i++) {
            //     this.files.push(uploadedFiles[i]);
            // }
        },
        NewModal() {
            this.errors = []
            this.ticket.topic = ''
            this.ticket.description = ''
            this.ticket.importance = ''
            this.ticket.user = ''
            this.files = []
            this.$refs.files.value = ""
            this.ticket.technician = ''
            this.ticket.source = ''
            $('#addNewTicket').modal('show')
        },
        getProfilePhoto(tech) {
            if (tech['user']) {
                if (tech['user'].photo !== 'profile.png') {
                    return '/img/profile/' + tech['user'].name + '/' + tech['user'].photo
                } else {
                    return '/img/profile/profile.png'
                }
            }
        },
        loadTickets() {

            // let filter = this.filter
            // let filterYrN
            // if (filter.importance || !filter.source || !filter.user || !filter.society || !filter.technician || !filter.state) {
            //     filterYrN = false
            // }else {
            //     filterYrN = true
            // }
            // axios.get('/api/tickets?filter=true&state[]=1&state[]=2').then(response => {
            //     console.log(response)
            //     this.tickets = response.data.ticket.data;
            //     this.technicians = response.data.technician;
            //     this.users = response.data.user;
            //     this.states = response.data.states;
            //     this.sources = response.data.sources;
            //     this.society = response.data.society;
            // })
            this.search()

        },
        getResults(page = 1) {
            let techTab = []
            let stateTab = []
            let source, user, society, filterYrN

            for (var i = 0; i < this.filter.technician.length; i++) {
                let tech = this.filter.technician[i];
                techTab.push(tech.id)
            }
            for (var i = 0; i < this.filter.state.length; i++) {
                let state = this.filter.state[i];
                stateTab.push(state.id)
            }

            if (this.filter.source != null) {
                source = this.filter.source.id
            }
            if (this.filter.user != null) {
                user = this.filter.user.id
            }
            if (this.filter.society != null) {
                society = this.filter.society.id
            }
            if (!this.filter.importance || !source || !user || !society || !techTab || !stateTab) {
                filterYrN = false
            } else {
                filterYrN = true
            }

            axios.get('/api/tickets?page=' + page, {
                params: {
                    filter: filterYrN,
                    technician: techTab,
                    state: stateTab,
                    importance: this.filter.importance,
                    source: source,
                    user: user,
                    society: society
                }
            })
                .then(response => {
                    this.tickets = response.data.ticket;
                });
        },
        search() {
            let techTab = []
            let stateTab = []
            let source, user, society, filterYrN

            for (var i = 0; i < this.filter.technician.length; i++) {
                let tech = this.filter.technician[i];
                techTab.push(tech.id)
            }
            for (var i = 0; i < this.filter.state.length; i++) {
                let state = this.filter.state[i];
                stateTab.push(state.id)
            }

            if (this.filter.source != null) {
                source = this.filter.source.id
            }
            if (this.filter.user != null) {
                user = this.filter.user.id
            }
            if (this.filter.society != null) {
                society = this.filter.society.id
            }
            if (!this.filter.importance || !source || !user || !society || !techTab || !stateTab) {
                filterYrN = false
            } else {
                filterYrN = true
            }
            axios.get('/api/tickets', {
                params: {
                    filter: filterYrN,
                    technician: techTab,
                    state: stateTab,
                    importance: this.filter.importance,
                    source: source,
                    user: user,
                    society: society
                }
            }).then(response => {
                this.tickets = response.data.ticket;
                this.technicians = response.data.technician;
                this.users = response.data.user;
                this.states = response.data.states;
                this.sources = response.data.sources;
                this.society = response.data.society;
                console.log(response)
            })
        }
    },
    computed: {
        availableUser() {
            return (this.filter.society != null) ? this.filter.society.users : this.users
        }
    },
    created() {

        this.search();

        Fire.$on('createTicket', () => {
            this.loadTickets()
        })

    }
}
</script>
<style>
    @media (min-width: 576px) {
        .ticket {
            max-width: 800px !important;
        }
    }

    .dropdown {
        display: block;
    }

    .v-select .dropdown-toggle {
        border: none;
    }

    .dropdown-toggle::after {
        display: none;
    }

    .dropdown-toggle {
        height: 46px;
    }

    .v-select input[type="search"],
    .v-select input[type="search"]:focus {
        margin: 0;
        font-size: 14px;
        flex-basis: 20px;
        flex-grow: 1;
        width: 100% !important;
    }

    .v-select .selected-tag {
        height: 30px;
        margin: 0;
        padding: 10px 5px;
        background-color: transparent;
        font-size: 14px;
        color: white;
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
    }

    .v-select .vs__selected-options {
        align-items: center;
        margin-left: 5px;
    }

    .v-select .selected-tag .close {
        color: white !important;
    }

    .close > span:not(.sr-only) {
        opacity: 1;
        margin-top: 2px;
    }

    .v-select .dropdown-menu > .highlight > a {
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
        font-size: 14px;
    }

    .v-select .dropdown-menu {
        font-size: 14px !important;
        width: 100%;
        border: none;
    }

    .v-select.single .selected-tag {
        background-color: transparent;
        border-color: transparent;
        background: none !important;
        color: black;
    }


</style>
