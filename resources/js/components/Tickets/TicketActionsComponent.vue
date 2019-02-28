<template>
    <div>


        <div class="card" v-if="ticket.actions && ticket.actions[0]">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="header-title mb-2">Activités récentes</h4>
                    <button class="btn btn-icon btn-primary" type="button" @click="addAction" v-if="$gate.isAdmin()">
                        <i class="fas fa-plus-square"></i>
                    </button>
                </div>
                <div class="timeline-alt pb-0">
                    <div class="timeline-item" v-for="action in actions">
                        <img :src="getProfilePhotoFrom(action.from)"
                             class="timeline-avatar timeline-icon">
                        <div class="timeline-item-info">
                            <a href="#"
                               class="text-primary font-weight-bold mb-1 d-block">{{action.from.name}}</a>
                            <small v-html="action.content">
                            </small>
                            <p class="mb-0 pb-2">
                                <small class="text-muted">{{ action.created_at | formatDate}}</small>
                            </p>
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
    props: ['ticket'],
    data() {
        return {
            action: '',
            actions: [],
        }
    },
    watch : {
      ticket : 'loadAction'
    },
    methods: {
        loadAction() {
            let ticket = this.$route.params.id
            axios.get('/api/tickets/' + ticket).then(result => {
                this.actions = result.data.ticket.actions.reverse()
            })
        },
        getProfilePhotoFrom(from) {
            if (from) {
                if (from.photo !== 'profile.png') {
                    return '/img/profile/' + from.name + '/' + from.photo
                } else {
                    return '/img/profile/profile.png'
                }
            }

        },
        addAction() {
            $('#addAction').modal('show')
        },
        createAction() {
            this.$Progress.start()
            axios.post('/api/addAction/' + this.ticket.id, {
                content: this.action
            }).then(result => {
                $('#addAction').modal('hide')
                this.action = ""
                this.$toasted.global.flash({message: "L'action à bien été ajoutée."});
                Fire.$emit('addAction')
                this.$Progress.finish()

            }).catch(rerror => {

            })
        },
    },
    mounted() {
        this.loadAction()

    }
}
</script>
