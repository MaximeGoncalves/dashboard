<template>
    <div class="card shadow mb-2 px-5 py-5">
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
                <viewer :value="ticket.description"></viewer>
                <div class="mt-3" v-if="ticket.technician">
                    <img :src="getProfilePhoto(ticket.technician)"
                         class="avatar avatar-sm mr-3"
                         data-toggle="tooltip"
                         data-placement="bottom"
                         :title="ticket.technician.user.name">
                    <small class="pr-2 text-nowrap d-inline-block">
                        <i class="fas fa-file-archive text-muted mr-1"></i>
                        <b>{{ticket.attachments.length}}</b> Fichiers
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
                <div class="d-flex align-items-center">
                    <i class="fas fa-laptop text-default"></i>
                    <v-select
                        label="name"
                        placeholder="Types"
                        :clearable=false
                        v-model="ticket.type"
                        :options="types"
                        @input="updateTicket(ticket)"
                    ></v-select>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Editor, Viewer} from '@toast-ui/vue-editor';
export default {
    components: {Editor, Viewer},
    props: ['ticket', 'sources', 'states', 'types'],
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
        updateTicket(ticket) {
            let tech
            if (ticket.technician) {
                tech = ticket.technician.user.id
            }

            axios.put('/api/tickets/' + ticket.id, {
                source: ticket.source.id,
                importance: ticket.importance,
                state: ticket.state.id,
                type: ticket.type ? ticket.type.id : null,
                technician: tech
            }).then(result => {
                this.$toasted.global.flash({message: "Le ticket à été mis à jour"});
                this.$Progress.finish()
                Fire.$emit('createTicket')
            })
        },
    }
}
</script>
