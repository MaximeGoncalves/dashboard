<template>
    <div>
        <div v-if="ticket && unauthorized === false ">
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="card shadow mb-2">
                        <div class="card-header p-0" style="border-bottom: none;">
                            <div class="nav-wrapper p-0">
                                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text"
                                    role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link mb-sm-3 mb-md-0 active rounded-left-one"
                                           id="tabs-icons-text-1-tab" data-toggle="tab"
                                           href="#ticket" role="tab" aria-controls="tabs-icons-text-1"
                                           aria-selected="true"
                                           style="box-shadow: none; border-top-left-radius: 0.375rem;">
                                            <i class="fas fa-file-alt mr-2"></i>Ticket</a>
                                    </li>
                                    <li class="nav-item" v-if="$gate.isAdmin()">
                                        <a class="nav-link mb-sm-3 mb-md-0 rounded-right-one" id="tabs-icons-text-3-tab"
                                           data-toggle="tab"
                                           href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3"
                                           aria-selected="false"
                                           style="box-shadow: none;border-top-right-radius: 0.375rem;"><i
                                            class="fas fa-cogs mr-2"></i>Détails</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body" v-if="ticket.state">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="ticket" role="tabpanel"
                                     aria-labelledby="tabs-icons-text-1-tab">
                                    <div class="card-body">
                                        <h2 class="d-inline mb-3">{{ this.ticket.topic }}</h2>
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
                                        <br>
                                        <small class="">{{ ticket.user.fullname}}</small>
                                        -
                                        <small class="">{{ ticket.society.name}}</small>
                                        <div class="mb-4"></div>
                                        <h4>Description :</h4>
                                        <p v-html="ticket.description"></p>
                                    </div>
                                    <div class="card-footer mt-3">
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <h4>Date de création</h4>
                                                <small>{{ticket.created_at | formatDate}}</small>
                                            </div>
                                            <div class="col-12 col-md-4" v-if="ticket.source">
                                                <h4>Source</h4>
                                                <small>{{ ticket.source.name }}</small>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <h4>Priorité</h4>
                                                <small>{{ this.ticket.importance }}</small>
                                            </div>
                                        </div>
                                        <h4 class="mt-5">Technicien :</h4>
                                        <img v-if="ticket.technician.user" :src="getProfilePhoto(ticket.technician)"
                                             class="avatar avatar-sm "
                                             data-toggle="tooltip"
                                             data-placement="bottom"
                                             :title="ticket.technician.user.name">
                                        <div v-else>N/A</div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel"
                                     aria-labelledby="tabs-icons-text-3-tab">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <h4>Détails utilisateur</h4>
                                                <br>
                                                <p><span class="font-weight-bold">Identifiant : </span><span
                                                    class="ml-2">{{ ticket.user.id }}</span>
                                                </p>

                                                <p><span class="font-weight-bold">Nom complet :</span> <span
                                                    class="ml-2"> {{ ticket.user.fullname }}</span>
                                                </p>
                                                <p><span class="font-weight-bold">E-mail :</span> <span class="ml-2">{{ ticket.user.email }}</span>
                                                </p>
                                                <p><span class="font-weight-bold">Rôle : </span><span class="ml-2">{{ ticket.user.role }}</span>
                                                </p>

                                            </div>
                                            <div class="col-12 col-md-6 ">
                                                <h3>Paramètres</h3>

                                                <h4 class="mt-3 mb--3">Technicien</h4>
                                                <form @submit.prevent="updateTicket">
                                                    <v-select label="fullname"
                                                              :options="technicians"
                                                              :clearable="false"
                                                              v-model="ticket.technician.user"
                                                              class="mt-3 form-control-alternative"
                                                              placeholder="Technician"></v-select>

                                                    <h4 class="mt-2 mb--3">Sources</h4>
                                                    <v-select label="name"
                                                              :options="sources"
                                                              v-model="ticket.source"
                                                              class=" mt-3 form-control-alternative"
                                                              :clearable="false"
                                                              placeholder="Sources"></v-select>
                                                    <h4 class="mt-2 mb--3">Priorité</h4>
                                                    <v-select class=" mt-3 form-control-alternative"
                                                              placeholder="Priorité"
                                                              :clearable="false"
                                                              v-model="ticket.importance"
                                                              :options="['Normal','Urgent']"></v-select>

                                                    <h4 class="mt-2 mb--3 ">État</h4>
                                                    <v-select class="mt-3 form-control-alternative"
                                                              label="name"
                                                              placeholder="État"
                                                              :clearable="false"
                                                              v-model="ticket.state"
                                                              :options="states"></v-select>
                                                    <button type="submit" class="btn btn-primary float-right mt-3">Save
                                                    </button>
                                                </form>
                                                <a class="btn btn-danger mt-3 text-white"
                                                   @click="deleteTicket">Supprimer</a>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-2">
                        <div class="card-body">
                            <h4 class="card-title">Nouveau message</h4>
                            <form @submit.prevent="addMessage">
                                <div class="form-group" :class="errors.content ? 'has-danger' : ''">
                            <textarea name="content" id="comment" cols="30" rows="3" class="form-control"
                                      v-model="message"></textarea>
                                    <small v-if="errors.content" :class="errors.content ? 'text-danger' : ''"
                                           v-for="error in errors.content">{{ error }}
                                    </small>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn btn-primary mt-2 mb-2">Envoyer</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                            <div class="row" v-for="message in messages">
                                <message-component :msg="message" :key="message.id"></message-component>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <attachments-component :ticket="ticket"></attachments-component>
                    <action-component :actions="actions" :ticket="ticket"></action-component>
                </div>
            </div>
        </div>
        <div v-if="unauthorized === true">
            <not-found></not-found>
        </div>
    </div>

</template>

<script>
import actionComponent from './TicketActionsComponent'

import AttachmentsComponent from './TicketAttachmentsComponent'

export default {
    components: {
        actionComponent,
        AttachmentsComponent
    },
    data() {
        return {
            unauthorized: false,
            ticket: {},
            errors: [],
            message: '',
            messages: {},
            actions: {}
        }
    }
    ,
    methods: {

        getProfilePhoto(tech) {
            if (tech['user']) {
                if (tech['user'].photo !== 'profile.png') {
                    return '/img/profile/' + tech['user'].name + '/' + tech['user'].photo
                } else {
                    return '/img/profile/profile.png'
                }
            }
        },
        updateTicket() {
            this.$Progress.start()
            let tech
            if (this.ticket.technician.user) {
                tech = this.ticket.technician.user.id
                console.log(tech)
            }
            axios.put('/api/tickets/' + this.ticket.id, {
                technician: tech,
                source: this.ticket.source.id,
                importance: this.ticket.importance,
                state: this.ticket.state.id
            }).then(result => {
                this.$toasted.global.flash({message: "Le ticket à été mis à jour"});
                this.$Progress.finish()
                Fire.$emit('updateTicket', this.ticket.id)
            })
        },
        deleteTicket() {
            swal({
                title: "Etes vous sur ? ",
                text: "Cette manipulation est irréversible !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(willDelete => {
                    if (willDelete) {
                        axios.delete('/api/tickets/' + this.ticket.id).then(() => {
                            swal("Le ticket à été supprimé", {
                                icon: "success",
                            });
                            this.$router.push({path: "/tickets"})
                        }).catch(error => {
                            this.errors = error.response.data.message;
                            swal(this.errors);
                        })
                    } else {
                        swal("Ok on annule !");
                    }
                });
        },
        loadTicket() {
            let ticket = this.$route.params.id;

            axios.get('/api/tickets/' + ticket).then(result => {
                if (result.data.unauthorized) {
                    this.unauthorized = true
                }
                this.ticket = result.data.ticket
                this.sources = result.data.sources
                this.technicians = result.data.technicians
                this.states = result.data.states
                this.messages = result.data.messages
                this.actions = result.data.actions.reverse()
                if (!this.ticket.technician) {
                    this.ticket.technician = {}
                }
            })
        },
        addMessage() {
            this.$Progress.start();
            axios.post('/api/message/' + this.ticket.id, {
                content: this.message
            }).then(result => {
                this.$Progress.finish();
                this.message = ""
                this.$toasted.global.flash({message: "Votre commentaire à bien été ajouté"});
                Fire.$emit('addMessage', this.ticket.id)
                console.log(result)
            }).catch(error => {
                console.log(error)
                this.errors = error.response.data.errors;
                this.$Progress.fail();
            })
        },
        loadAction(id) {
            axios.get('/api/actions', {
                params: {
                    ticket: id,
                }
            }).then(response => {
                console.log(response)
                this.actions = response.data.actions.reverse()
            })
        },
    }
    ,
    mounted() {

        this.loadTicket()
        Fire.$on('addAction', (id) => {
            this.loadAction(id)
        })
        Fire.$on('addMessage', (id) => {
            axios.get('/api/message', {
                params: {
                    ticket: id,
                }
            }).then(response => {
                this.messages = response.data.messages
            })
            this.loadAction(id)
        })
        Fire.$on('updateTicket', (id) => {
            this.loadTicket()
            this.loadAction(id)
        })
    }
}
</script>

<style>


    .nav-pills .nav-item:not(:last-child) {
        padding-right: 0;
    }

    .nav-pills .nav-link {
        border-radius: 0;
    }

    .timeline-alt {
        padding: 20px 0;
        position: relative;
    }

    .timeline-avatar {
        width: 22px !important;
        height: 22px !important;
    }

    .timeline-alt .timeline-item {
        position: relative;
    }

    .timeline-alt .timeline-item .timeline-item-info {
        margin-left: 30px;
    }

    .timeline-alt .timeline-item .timeline-icon {
        float: left;
        height: 20px;
        width: 20px;
        border-radius: 50%;
        border: 2px solid transparent;
        font-size: 12px;
        text-align: center;
        line-height: 16px;
        background-color: #fff;
    }

    .timeline-alt .timeline-item:before {
        background-color: #f1f3fa;
        bottom: 0;
        content: "";
        left: 9px;
        position: absolute;
        top: 20px;
        width: 2px;
        z-index: 0;
    }

    small p {
        margin-bottom: 0 !important;
    }
</style>
