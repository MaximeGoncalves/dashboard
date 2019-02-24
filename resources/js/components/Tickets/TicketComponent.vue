<template>

    <div v-if="ticket">
        <div class="row mb-2">
            <div class="col-12">
                <button class="btn btn-icon btn-danger" type="button" @click="addAction" style="border-radius:30px">
                    <i class="fas fa-plus-square"></i>
                    <span class="btn-inner--text">Activité</span>
                </button>
                <button class="btn btn-icon btn-danger" type="button" style="border-radius:30px">
                    <i class="fas fa-plus-square"></i>
                    <span class="btn-inner--text">Message</span>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card shadow mb-2 px-5 py-5">
                    <div class="card-body" v-if="ticket.state">
                        <h2 class="d-inline mb-3">{{ this.ticket.topic }}</h2>
                        <i class="fas fa-bookmark text-info"
                           v-if="ticket.state.id == '1'" data-toggle="tooltip" data-placement="top" title="Pending"></i>
                        <i class="fas fa-bookmark text-success"
                           v-if="ticket.state.id == '2'" data-toggle="tooltip" data-placement="top" title="Open"></i>
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
                        <div class="mb-3"></div>
                        <h4>Description :</h4>
                        <p v-html="ticket.description"></p>
                    </div>
                    <div class="card-footer mt-3">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <h4>Date de création</h4>
                                {{ticket.created_at | formatDate}}
                            </div>
                            <div class="col-12 col-md-4" v-if="ticket.source">
                                <h4>Source</h4>
                                {{ ticket.source.name }}
                            </div>
                            <div class="col-12 col-md-4">
                                <h4>Priorité</h4>
                                {{ this.ticket.importance }}
                            </div>
                        </div>
                        <h4 class="mt-5">Technicien :</h4>
                        <img v-if="ticket.technician" :src="getProfilePhoto(ticket.technician)"
                             class="avatar avatar-sm "
                             data-toggle="tooltip"
                             data-placement="bottom"
                             :title="ticket.technician.user.name">
                        <div v-else>N/A</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card shadow mb-2" v-if="ticket.attachments && ticket.attachments[0] ">
                    <div class="card-body">
                        <h4 class="card-title">Fichiers attachés</h4>
                        <div class="card mb-1 shadow-none border" v-for="attachment in ticket.attachments">
                            <div class="p-2">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="fas fa-paperclip"></i>
                                    </div>
                                    <div class="col pl-0">
                                        <a class="text-muted font-weight-bold"
                                           :href="attachment.link"
                                           download>
                                            {{ attachment.name }}
                                        </a>
                                        <p class="mb-0">2.3 MB</p>
                                    </div>
                                    <div class="col-auto">
                                        <!-- Button -->
                                        <a href="javascript:void(0);" class="btn btn-link btn-lg text-muted">
                                            <i class="dripicons-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" v-if="ticket.actions && ticket.actions[0]">
                    <div class="card-body">
                        <div class="dropdown float-right">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown"
                               aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Settings</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                            </div>
                        </div>
                        <h4 class="header-title mb-2">Recent Activity</h4>
                        <div class="row" v-for="action in ticket.actions">
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto">
                                        <img :src="getProfilePhotoFrom(action.from)"
                                             class="avatar avatar-sm">
                                    </div>
                                    <div class="col">
                                        <a href="#"
                                           class="text-primary font-weight-bold mb-1 d-block">{{action.from.name}}</a>
                                        <small class="d-block" v-html="action.content"></small>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted">{{ action.created_at | formatDate}}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-12">
                <div class="card shadow mb-2">
                    <div class="card-body">
                        <h4 class="card-title">Nouveau message</h4>
                        <form @submit.prevent="addMessage">
                            <textarea name="content" id="comment" cols="30" rows="3" class="form-control" v-model="message"></textarea>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button class="btn btn-primary mt-2">Envoyer</button>
                                </div>
                            </div>
                        </form>
                        <div class="row" v-for="message in ticket.messages">
                            <div class="col-12">
                                <div class="message d-flex">
                                    <img :src="getProfilePhotoFrom(message.from)" alt="" class="avatar avatar-sm mr-2">
                                    <div>
                                        <h4 class="d-inline">{{ message.from.fullname }}</h4>
                                        <p>{{ message.content }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addAction" tabindex="-1" role="dialog" aria-labelledby="addAction"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form @submit.prevent="createAction()">
                            <h4>Ajouter une action</h4>
                            <textarea name="content" id="action" cols="30" rows="2"
                                      class="form-control form-control-alternative"
                                      v-model="action"
                                      placeholder="Votre action"></textarea>
                            <button type="submit" class="btn-primary btn float-right mt-2 btn-sm">Ajouter</button>
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
            ticket: {},
            action: '',
            message: '',
        }
    }
    ,
    methods: {
        addAction() {
            $('#addAction').modal('show')
        }
        ,
        addMessage() {
            this.$Progress.start();
            axios.post('/api/message/' + this.ticket.id , {
                content : this.message
            }).then(result => {
                this.$Progress.finish();

                this.message = ""
                this.$toasted.global.flash({message: "Votre commentaire à bien été ajouté"});
                Fire.$emit('addMessage')
            }).catch(error => {
                this.$Progress.fail();
            })
        },
        getProfilePhoto(tech) {
            if (tech['user']) {
                if (tech['user'].photo !== 'profile.png') {
                    return '/img/profile/' + tech['user'].name + '/' + tech['user'].photo
                } else {
                    return '/img/profile/profile.png'
                }
            }
        }
        ,
        getProfilePhotoFrom(from) {
            if (from) {
                if (from.photo !== 'profile.png') {
                    return '/img/profile/' + from.name + '/' + from.photo
                } else {
                    return '/img/profile/profile.png'
                }
            }

        }
        ,
        createAction() {
            axios.post('/api/addAction/' + this.ticket.id, {
                content: this.action
            }).then(result => {
                $('#addAction').modal('hide')
                this.action = ""
                this.$toasted.global.flash({message: "L'action à bien été ajoutée."});
                Fire.$emit('addAction')
            }).catch(rerror => {

            })
        }
        ,
        loadTicket() {
            let ticket = this.$route.params.id
            axios.get('/api/tickets/' + ticket).then(result => {
                this.ticket = result.data
            })
        }
        ,
    }
    ,
    mounted() {
        this.loadTicket()
        Fire.$on('addAction', () => {
            this.loadTicket()
        })
        Fire.$on('addMessage', () =>  {
            this.loadTicket()
        })

    }
}
</script>

<style>
    small p {
        margin-bottom: 0 !important;
    }
</style>
