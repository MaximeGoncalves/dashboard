<template>
    <div>
        <div class="row mb-3 mt-2">
            <div class="col-lg-12 d-flex justify-content-between">
                <button class="btn btn-default " @click="NewModal">
                    Nouveau ticket
                </button>
                <div class="dropdown d-inline-block" v-if="$gate.isAdmin()">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Paramètres <i class="fas fa-cog"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a href="" class="dropdown-item"
                           @click.prevent="ViewPreferences = true">Préférences</a>
                        <a href="" class="dropdown-item"
                           @click.prevent="ViewTypes = true">Gestion des types</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row flex-xl-row-reverse">
            <div class="col-12  col-xl-4">
                <div class="card shadow mb-2 px-5 py-5">
                    <div class="row">
                        <div class="col-8">
                            <h3 class="mb-0">FILTERS</h3>
                        </div>
                    </div>
                    <div class="row">
                        <form class="w-100" @submit.prevent="getResults">
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
                                <h4 class="mt-4">Type</h4>
                                <v-select class="form-control-alternative mt-2" label="name" placeholder="Types"
                                          v-model="filter.type" :options="types" clearable></v-select>
                                <h4 class="mt-4">Société</h4>
                                <v-select class="form-control-alternative mt-2" label="name" placeholder="Société"
                                          v-model="filter.society" :options="society" clearable></v-select>

                                <h4 class="mt-4">Demandeur</h4>
                                <v-select class="form-control-alternative mt-2" label="fullname" placeholder="Demandeur"
                                          v-model="filter.user" :options="availableUser" clearable></v-select>
                            </div>

                            <button type="submit" class="btn btn-default mt-3 float-right">
                                Rechercher
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-8">
                <div class="card shadow" v-if="!card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Users</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="#"
                                   class="btn btn-sm btn-default">Add
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
                <card-ticket v-if="card" v-for="(ticket, key) in tickets.data"
                             :key="key"
                             :ticket="ticket"
                             :sources="sources"
                             :states="states"
                             :types="types"
                ></card-ticket>
                <pagination :data="tickets" @pagination-change-page="getResults" :limit="3"></pagination>
            </div>
        </div>
        <div class="tickets-settings shadow p-2 border" :class="ViewTypes ? 'show' : ''">
            <div class="row">
                <div class="col">
                    <h2 class="h2">
                        Types
                    </h2>
                </div>
                <div class="col">
                    <span class="float-right cursor-pointer">
                            <span @click="ViewTypes = false"><i class="fas fa-times"></i></span>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="types">Ajouter un type</label>
                    <input type="text" id="types" name="types" class="form-control" v-on:keyup.enter="saveType()"
                           v-model="type">
                    <ul class="list-group mt-2">
                        <types v-for="(type, index) in types" :key="index" :type="type" :index="index"
                               v-on:deleteType="types.splice(index, 1)"></types>
                    </ul>
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
                                        <editor ref="tuiEditor"
                                                placeholder="Quel est votre problème ?"
                                                v-model="ticket.description"
                                                :options="editorOptions"
                                                :html="editorHtml"
                                                :mode="editorMode"
                                                :previewStyle="editorPreviewStyle"
                                                :class="errors.description ? 'is-invalid' : ''"
                                                height="150px"></editor>
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-default">Créer
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
import {Editor} from '@toast-ui/vue-editor';
import Types from './TypesComponent.vue';
import CardTicket from './CardTicketComponent';
export default {
    components: {Editor, Types, CardTicket},
    props: {
        placeholder: 'placeholder',
    },
    data() {
        return {
            editorOptions: {
                minHeight: '0',
                hideModeSwitch: false,
                toolbarItems: [
                    'bold',
                    'italic',
                    'strike',
                    'ul',
                    'ol',
                    'indent',
                    'outdent',
                    'divider',
                    'table',
                ]
            },
            editorHtml: '',
            editorMode: 'wysiwyg',
            editorVisible: true,
            editorPreviewStyle: 'vertical',
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
                    {"id": 1, "name": "En attente"},
                    {"id": 2, "name": "Ouvert"},
                    {"id": 3, "name": "En attente client"},
                ],
                user: {},
                source: {},
                type: {},
                society: null,
            },
            card: true,
            photo: '',
            uploadText: 'Attendez la fin du téléchargement',
            types: [],
            type: '',
            ViewTypes: false,
            ViewPreferences: false,
        }
    },
    methods: {
        createTicket() {
            this.$Progress.start();
            let formData = new FormData();
            if (window.user.role === 'admin') {
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
            } else {
                formData.append('topic', this.ticket.topic)
                formData.append('description', this.ticket.description)
                formData.append('importance', this.ticket.importance)
            }
            for (var i = 0; i < this.files.length; i++) {
                let file = this.files[i];
                formData.append('files[' + i + ']', file);
            }
            formData.append('type', 'Ticket')
            let self = this
            const config = {
                onUploadProgress: function (progressEvent) {
                    if (self.files.length > 0) {
                        let percent = (Math.round((progressEvent.loaded * 100) / progressEvent.total)).toString()
                        if (percent === '100') {
                            self.$emit('uploadFinish')
                        }
                        swal({
                            title: percent === '100' ? 'Creation du ticket' : percent + '%',
                            text: self.uploadText,
                            icon: "info",
                            dangerMode: false,
                            button: {
                                visible: false
                            },
                        })
                    }
                }
            }
            axios.post('/api/tickets', formData, config, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(response => {
                $('.modal').modal('hide')
                this.ticket = {}
                this.$toasted.global.flash({message: "Le ticket à été créé."});
                this.$Progress.finish();
                Fire.$emit('createTicket')
                Fire.$emit('uploadFinish')
                swal.close()
                axios.post('/api/tickets/sendmail', {
                    ticket: response.data.ticket.id,
                })

            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail()
            });
        },
        handleFileUploads(e) {
            let files = e.target.files || e.dataTransfer.files;
            for (let i = files.length - 1; i >= 0; i--) {
                this.files.push(files[i]);
            }
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
        getResults(page = 1) {
            let techTab = []
            let stateTab = []
            let source, user, society, filterYrN, type
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
            if (!this.filter.importance || !source || !user || !society  || !type || !techTab || !stateTab) {
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
                    society: society,
                    type: type
                }
            })
                .then(response => {
                    this.tickets = response.data.ticket;
                    this.technicians = response.data.technician;
                    this.users = response.data.user;
                    this.states = response.data.states;
                    this.sources = response.data.sources;
                    this.society = response.data.society;
                    this.types = response.data.types
                });
        },
        saveType() {
            axios.post('/api/type', {
                name: this.type,
            }).then(response => {
                this.type = ""
                this.types.push(response.data)
            })
        },
    },
    computed: {
        availableUser() {
            return (this.filter.society != null) ? this.filter.society.users : this.users
        }
    },
    created() {
        this.$on('uploadFinish', function () {
            this.uploadText = 'Création du ticket, veuillez patienter.'
            swal({
                title: 'creation du ticket',
                text: this.uploadText,
                showConfirmButton: false
            })
        })
        this.getResults();
        Fire.$on('createTicket', () => {
            this.getResults(this.tickets.current_page)
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
        background: linear-gradient(87deg, #32325d 0, #172b4d 100%) !important;
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
        font-size: 14px;
        background: linear-gradient(87deg, #32325d 0, #172b4d 100%) !important;
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
    .tickets-settings {
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 100%;
        background: white;
        transform: translateX(100%);
        transition: transform 0.3s linear
    }
    .tickets-settings.show {
        transform: translateX(0%) !important;
    }
</style>
