<template>
    <div v-if="showNote()">
            <div class="row mt-2">
                <div class="col-05 d-flex flex-column align-items-center">
                    <img :src="getProfilePhotoFrom(msg.from)" alt=""
                         class="avatar avatar-sm">
                    <i class="fa fa-pen-alt cursor-pointer text-muted mt-3 " style="font-size:12px"
                       @click.prevent="edit = true" v-if="canEdit(msg.from.id)"></i>
                    <i class="fas fa-reply cursor-pointer text-muted mt-3" @click.prevent="respondTo = msg" v-if="!msg.hasOwnProperty('private')"></i>
                </div>
                <div class="col-11 card py-3" :class="msg.hasOwnProperty('private') ? 'bg-note' : ''" >
                    <h4 class="d-inline">{{ msg.from.fullname }} <i class="fas fa-lock" v-if="msg.private" data-toggle="tooltip" data-placement="top" title="Seul l'équipe softease peut voir cette note"></i> </h4>
                    <small class="mb-2 text-muted d-inline-block">{{ msg.created_at | FullDate}}</small>
                    <div v-if="msg.parent">
                        <blockquote class="blockquote">{{msg.parent.content}}</blockquote>
                    </div>
                    <div v-if="edit">
                        <editor ref="tuiEditor"
                                v-model="message"
                                :options="editorOptions"
                                :html="editorHtml"
                                :mode="editorMode"
                                :previewStyle="editorPreviewStyle"
                                height="150px"></editor>
                        <div class="d-flex align-items-center mb-2 justify-content-end">
                            <small class=""><a href="#" class="text-default" @click.prevent="cancel"><i
                                class="fas fa-ban mr-1"></i>Annuler</a></small>
                            <small class="ml-2"><a href="#" class="text-default" @click.prevent="updateMessage"><i
                                class="fas fa-save mr-1"></i>Sauvegarder</a>
                            </small>
                        </div>
                    </div>
                    <viewer v-else :value="msg.content"></viewer>
                    <!--<p v-else v-html="msg.content"></p>-->

                    <div v-if="respondTo">
                        <editor ref="tuiEditor"
                                v-model="respondToMessage"
                                :options="editorOptions"
                                :html="editorHtml"
                                :mode="editorMode"
                                :previewStyle="editorPreviewStyle"
                                height="150px"></editor>
                        <div class="d-flex align-items-center mb-2 justify-content-end">
                            <small><a href="#" @click.prevent="cancelResponse" class="text-default"><i
                                class="fas fa-ban mr-1"></i>Annuler</a>
                            </small>
                            <small class="ml-2 "><a class="text-default" href="#" @click.prevent="storeResponse"><i
                                class="fas fa-save mr-1"></i>Envoyer</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!--        <div class="row mt-2 pl-4 border-secondary ml-3" style="border-left: 2px solid"> -->
    <!--            <message-component :msg="child" :key="child.id" v-for="child in msg.children"></message-component> -->
    <!--        </div> -->

</template>

<script>
import {Editor, Viewer} from '@toast-ui/vue-editor';

export default {
    props: ['msg'],
    components: {Editor, Viewer},

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
            message: this.msg.content,
            errors: [],
            edit: false,
            respondTo: null,
            respondToMessage: '',
            type: '',
            displayAction: false
        }
    },
    computed: {},
    methods: {
        showNote(){
            if (this.msg.private == true){
                if(window.user.society_id == 1){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true
            }


        },
        canEdit(idUser) {
            return idUser === window.user.id
        },
        storeResponse() {
            if (this.msg.commentable_type === "App\\Task") {
                this.type = "Task"
            } else {
                this.type = "Ticket"
            }
            axios.post('/api/message/' + this.msg.commentable_id, {
                content: this.respondToMessage,
                to_id: this.respondTo.id,
                type: this.type,
            }).then(response => {
                this.type = ''
                this.respondTo = null
                axios.post('/api/message/'+ response.data.messages.id + '/sendEmailMessage', {
                    message: response.data.messages.id
                })
                this.$emit('commentAdded', response.data.messages)
            })
        },
        updateMessage() {
            if(this.msg.hasOwnProperty('private')){
                axios.put('/api/note/' + this.msg.id, {
                    content: this.message,
                }).then(result => {
                    this.edit = false
                    this.msg.content = this.message
                })
            }else{
                axios.put('/api/message/' + this.msg.id, {
                    content: this.message,
                }).then(result => {
                    this.edit = false
                    this.msg.content = this.message
                })
            }

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

<style lang="scss">
    .action-card {
        background-color: rgba(17, 205, 239, 0.3);

        h4 {
            color: white;
        }
    }

    blockquote {
        padding: 5px 15px;
        color: #a0a0a0;
        font-style: italic;
        font-size: 14px !important;
        font-weight: 300;
        margin: 10px 0;
        border-left: 4px solid #f5f5f5;
    }
</style>
