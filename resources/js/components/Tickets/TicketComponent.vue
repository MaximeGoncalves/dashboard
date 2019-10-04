<template>
    <div>
        <div v-if="ticket && unauthorized === false ">
            <header-ticket-component
                :element="ticket"
                type="Ticket"
                v-on:addNote="addcomment = false, addNote = true"
                v-on:addComment="addNote = false, addcomment = true"
                v-on:deleteTicket="deleteTicket()"
            ></header-ticket-component>
            <div class="row m-0" style="height: calc(100vh - 82px - 85.375px);">
                <div class="pl-4 pt-2 border-left border-right ticket-detail"
                     :class="$gate.isAdmin() ? 'col-12 col-md-8' : 'col-10'  "
                     style="max-height:calc(100vh - 82px - 85.375px); overflow-y:scroll; overflow-x:hidden">
                    <ticket-description-component :ticket="ticket"
                                                  v-on:addComment="addcomment = true"></ticket-description-component>

                    <!-- Activités -->
                    <div class="row mt-3" v-if="messages != 0">
                        <div class="col-05"></div>
                        <div class="col-11">
                            <h2>Activitées</h2>
                        </div>
                    </div>
                    <div class="mb-2" v-for="message in messages">
                        <message-component :msg="message" v-on:commentAdded="addComment($event)"></message-component>
                    </div>
                    <div class="card shadow mb-2" id="note" name="note" v-show="addNote">
                        <div class="card-body">
                            <h4 class="card-title">Nouvelle note</h4>
                            <form @submit.prevent="addNotes">
                                <div class="form-group" :class="errors.content ? 'has-danger' : ''">
                                    <editor ref="tuiEditor"
                                            v-model="note"
                                            :options="editorOptions"
                                            :html="editorHtml"
                                            :mode="editorMode"
                                            :previewStyle="editorPreviewStyle"
                                            height="200px"></editor>
                                    <small v-if="errors.content" :class="errors.content ? 'text-danger' : ''"
                                           v-for="error in errors.content">{{ error }}
                                    </small>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end align-items-center">
                                            <div v-if="$gate.isAdmin() && addNote == true"
                                                 class="d-flex justify-content-end align-items-center">
                                                <span class="mb-1 mr-2">Privé</span>
                                                <label class="custom-toggle">
                                                    <input type="checkbox" v-model="privateNote">
                                                    <span class="custom-toggle-slider rounded-circle"></span>
                                                </label>
                                            </div>
                                            <button class="btn btn-success my-2 ml-3">Envoyer</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card shadow mb-2" id="textarea-comment" v-if="addcomment">
                        <div class="card-body">
                            <h4 class="card-title">Nouveau message</h4>
                            <form @submit.prevent="addMessage">
                                <div class="form-group" :class="errors.content ? 'has-danger' : ''">
                                    <editor ref="tuiEditor"
                                            v-model="message"
                                            :options="editorOptions"
                                            :html="editorHtml"
                                            :mode="editorMode"
                                            :previewStyle="editorPreviewStyle"
                                            height="200px"></editor>
                                    <small v-if="errors.content" :class="errors.content ? 'text-danger' : ''"
                                           v-for="error in errors.content">{{ error }}
                                    </small>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end align-items-center">

                                            <button class="btn btn-success my-2 ml-3">Envoyer</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class=" bg-white p-0 " :class="$gate.isAdmin()? 'col-12 col-md-4' : 'col-12 col-md-2'">
                    <div class="row m-0 h-100">
                        <div class="col-6 border-right py-3" v-if="$gate.isAdmin()">
                            <h3>Propriétés</h3>

                            <form @submit.prevent="updateTicket">
                                <h4 class="mt-3 mb--2">Technicien</h4>
                                <v-select label="fullname"
                                          :options="technicians"
                                          :clearable="false"
                                          v-model="ticket.technician.user"
                                          class="mt-3 form-control-alternative"
                                          placeholder="Technician"></v-select>
                                <div class="mb-3"></div>

                                <h4 class="mt-2 mb--2">Sources</h4>
                                <v-select label="name"
                                          :options="sources"
                                          v-model="ticket.source"
                                          class=" mt-3 form-control-alternative"
                                          :clearable="false"
                                          placeholder="Sources"></v-select>
                                <div class="mb-3"></div>
                                <h4 class="mt-2 mb--2">Type</h4>
                                <v-select label="name"
                                          :options="types"
                                          v-model="ticket.type"
                                          class=" mt-3 form-control-alternative"
                                          :clearable="false"
                                          placeholder="Types"></v-select>
                                <div class="mb-3"></div>

                                <h4 class="mt-2 mb--2">Priorité</h4>
                                <v-select class=" mt-3 form-control-alternative"
                                          placeholder="Priorité"
                                          :clearable="false"
                                          v-model="ticket.importance"
                                          :options="['Normal','Urgent']"></v-select>
                                <div class="mb-3"></div>

                                <h4 class="mt-2 mb--2 ">État</h4>
                                <v-select class="mt-3 form-control-alternative"
                                          label="name"
                                          placeholder="État"
                                          :clearable="false"
                                          v-model="ticket.state"
                                          :options="states"></v-select>

                                <button type="submit" class="btn btn-success float-right mt-3">
                                    Enregistrer
                                </button>
                            </form>

                        </div>
                        <div class="col-6 p-0 bg-white" :class="$gate.isAdmin() ? 'col-6' : 'col-12' ">
                            <div class="accordion" id="accordionExample">
                                <div class="card border-none border-bottom">
                                    <div class="card-header p-2" id="headingOne" data-toggle="collapse"
                                         data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <h3>Details du ticket</h3>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                         data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p class="font-weight-bold">Date de création : <span
                                                class="font-weight-normal">{{ticket.created_at | formatDate}}</span>
                                            </p>
                                            <p class="font-weight-bold">Source : <span
                                                class="font-weight-normal">{{ ticket.source.name }}</span></p>
                                            <p class="font-weight-bold">Priorité : <span
                                                class="font-weight-normal">{{ this.ticket.importance }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header p-2" id="headingTwo" data-toggle="collapse"
                                         data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <h3>Détails de l'utilisateur</h3>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                         data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p><span class="font-weight-bold">Identifiant : </span><span
                                                class="ml-2">{{ ticket.user.id }}</span>
                                            </p>
                                            <p>
                                                <span class="font-weight-bold">Nom complet :</span> <span
                                                class="ml-2"> {{ ticket.user.fullname }}</span>
                                            </p>
                                            <p>
                                                <span class="font-weight-bold">E-mail :</span>
                                                <span class="ml-2">{{ ticket.user.email }}</span>
                                            </p>
                                            <p>
                                                <span class="font-weight-bold">Rôle : </span>
                                                <span class="ml-2">{{ ticket.user.role }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header p-2" id="headingThree" data-toggle="collapse"
                                         data-target="#collapseThree" aria-expanded="false" aria-controls="collapseTwo">
                                        <h3>Historique</h3>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                         data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div v-for="action in ticket.actions">
                                                <p><span class="font-weight-bold">{{action.from.fullname}}</span><br>
                                                    {{action.content}}<br>
                                                    <small>{{action.created_at | fullDate}}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
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
import {Editor, Viewer} from '@toast-ui/vue-editor';
import vClickOutside from 'v-click-outside'
import HeaderTicketComponent from './HeaderTicketComponent'
import TicketDescriptionComponent from './TicketDescriptionComponent'
import TicketRepository from './TicketRepository'
export default {
    directives: {
        clickOutside: vClickOutside.directive
    },
    components: {
        HeaderTicketComponent,
        TicketDescriptionComponent,
        actionComponent,
        Editor,
        Viewer,
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
            edit: false,
            unauthorized: false,
            ticket: {},
            errors: [],
            message: '',
            note: '',
            privateNote: true,
            messages: {},
            editDescription: false,
            technicians: {},
            types: {},
            addcomment: false,
            addNote: false,
        }
    }
    ,
    methods: {
        addComment($event) {
            this.messages.push($event);
        },
        updateTicket() {
            TicketRepository.update(this.ticket)
            // this.$Progress.start()
            // let tech, type
            // if (this.ticket.technician.user) {
            //     tech = this.ticket.technician.user.id
            // }
            // axios.put('/api/tickets/' + this.ticket.id, {
            //     technician: tech,
            //     source: this.ticket.source.id,
            //     importance: this.ticket.importance,
            //     state: this.ticket.state.id,
            //     type: this.ticket.type ? this.ticket.type.id : null
            // }).then(response => {
            //     this.$toasted.global.flash({message: "Le ticket à été mis à jour"});
            //     this.$Progress.finish()
            //     Fire.$emit('updateTicket', this.ticket.id)
            //    if(response.data.close){
            //        axios.post('/api/tickets/sendmail', {
            //            ticket: response.data.ticket.id,
            //            close: response.data.close
            //        })
            //    }
            // })
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
                this.types = result.data.types
                this.actions = result.data.actions
                if (!this.ticket.technician) {
                    this.ticket.technician = {}
                }
            })
        },
        addMessage() {
            this.$Progress.start();
            axios.post('/api/message/' + this.ticket.id, {
                content: this.message,
                type: 'Ticket'
            }).then(response => {
                this.$Progress.finish();
                this.message = ""
                this.addcomment = false;
                this.$toasted.global.flash({message: "Votre commentaire à bien été ajouté"});
                this.messages.push(response.data.messages)
                axios.post('/api/message/'+response.data.messages.id + '/sendEmailMessage')
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail();
            })
        },
        addNotes() {
            this.$Progress.start();
            axios.post('/api/note/' + this.ticket.id, {
                content: this.note,
                private: this.privateNote
            }).then(response => {
                this.$Progress.finish();
                this.note = ""
                this.addNote = false;
                this.$toasted.global.flash({message: "Votre note à bien été ajoutée"});
                this.messages.push(response.data)
                axios.post('/api/note/' + this.ticket.id + '/sendemail', {
                    note : response.data.id
                })
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail();
            })
        },
    },
    mounted() {
        this.loadTicket()
    }
}
</script>

<style>
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
        color: #fff;
        background-color: #172b4d;
    }
    .nav-pills .nav-link:hover {
        color: #172b4d;
    }
    .nav-pills .nav-link.active:hover {
        color: #fff;
    }
    .nav-pills .nav-link {
        color: #172b4d;
    }
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
    .accordion .card-header {
        position: relative;
    }
    .accordion .card-header:after {
        font: normal normal normal 14px/1 NucleoIcons;
        line-height: 0;
        position: absolute;
        top: 50%;
        right: 1.5rem;
        content: '\ea0f';
        transition: all .15s cubic-bezier(.68, -.55, .265, 1.55);
        transform: translateY(-50%);
    }
    .accordion .card-header[aria-expanded=false]:after {
        content: '\ea0f';
    }
    .accordion .card-header[aria-expanded=true]:after {
        transform: rotate(180deg);
    }
</style>
