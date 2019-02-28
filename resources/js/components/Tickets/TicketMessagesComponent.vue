<template>
    <div class="col-12">
        <div class="row mt-3">
            <div class="col-auto">
                <img :src="getProfilePhotoFrom(msg.from)" alt=""
                     class="avatar avatar-sm">
            </div>
            <div class="col">
                <h4 class="d-inline">{{ msg.from.fullname }} </h4>

                <br>
                <small class="mb-3 text-muted d-inline-block">{{ msg.created_at | formatDate}}</small>
                <small class="text-muted" v-if="!edit">
                    <a href="#" class="ml-1" @click.prevent="edit = true" v-if="canEdit(msg.from.id)"><i
                        class="fas fa-pen mr-1"></i>Edit</a></small>
                <small><a href="#" class="ml-1" @click.prevent="respondTo = msg"><i class="fas fa-reply mr-1"></i>Repondre</a></small>
                <div v-if="edit">
                    <textarea rows="3" class="form-control" v-model="message"></textarea>
                    <div class="d-flex align-items-center mb-2 justify-content-end">
                        <small><a href="#" @click.prevent="cancel"><i class="fas fa-ban mr-1"></i>Annuler</a></small>
                        <small class="ml-2"><a href="#" @click.prevent="updateMessage"><i class="fas fa-save mr-1"></i>Sauvegarder</a>
                        </small>
                    </div>
                </div>
                <p v-else v-html="msg.content"></p>

                <div v-if="respondTo">
                    <textarea rows="3" class="form-control" v-model="respondToMessage"></textarea>
                    <div class="d-flex align-items-center mb-2 justify-content-end">
                        <small><a href="#" @click.prevent="cancelResponse"><i class="fas fa-ban mr-1"></i>Annuler</a>
                        </small>
                        <small class="ml-2"><a href="#" @click.prevent="storeResponse"><i class="fas fa-save mr-1"></i>Envoyer</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3 pl-4 border-secondary ml-3" style="border-left: 2px solid">
            <message-component :msg="child" :key="child.id" v-for="child in msg.children"></message-component>
        </div>
    </div>

</template>

<script>
export default {
    props: ['msg'],

    data() {
        return {
            message: this.msg.content,
            errors: [],
            edit: false,
            respondTo: null,
            respondToMessage: '',
        }
    },
    computed: {},
    methods: {
        canEdit(idUser) {
            return idUser === window.user.id
        },
        storeResponse() {
            axios.post('/api/message/' + this.msg.ticket_id, {
                content: this.respondToMessage,
                to_id: this.respondTo.id,
            }).then(response => {
                this.respondTo = null
                Fire.$emit('addMessage', this.msg.ticket_id)
            })
        },
        updateMessage() {
            axios.put('/api/message/' + this.msg.id, {
                content: this.message,
            }).then(result => {
                this.edit = false
                this.msg.content = this.message
            })
        },
        cancel() {
            this.edit = false;
            this.message = this.msg.content
        },
        cancelResponse() {
            this.respondTo = null
            this.respondToMessage = ''
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

    }
}
</script>
