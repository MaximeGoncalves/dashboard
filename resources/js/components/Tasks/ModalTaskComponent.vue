<template>
    <div class="modal fade " id="taskModal" tabindex="-1" role="dialog" aria-labelledby="modal"
         aria-hidden="true">
        <div class=" modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content bg-secondary">
                <div class="modal-header bg-yellow" v-if="task.deleted_at">
                    <div class="row">
                        <div class="col-1"><i class="fas fa-heading"></i></div>
                        <div class="col-11">La tâche est archivé !</div>
                    </div>
                </div>
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-default" style="font-size: 20px;">&times;</span>
                    </button>
                    <div class="row mb-3">
                        <div class="col-1 pr-0 text-center">
                            <i class="fas fa-heading"></i>
                        </div>
                        <div class="col-11">
                            <h2 class="modal-title"><b>{{ task.name }}</b></h2>
                            <small>dans la liste <span class="text-primary">{{task.list.name}}</span></small>
                        </div>
                    </div>
                    <!-- Membres etc -->
                    <div class="row mb-3">
                        <div class="col-1 pr-0 text-center">
                        </div>
                        <div class="col-11 d-flex">
                            <div v-if="task.members && task.members.length > 0" class="mr-3">
                                <h4 class="h4">Membres</h4>
                                <div class="d-flex mr-3">
                                    <img v-for="member in task.members" :src="getProfilePhoto(member)"
                                         class="avatar avatar-task mr-2"
                                         data-toggle="tooltip"
                                         data-placement="bottom"
                                         :title="member.name">
                                </div>
                            </div>
                            <div v-if="task.dueDate" class="mr-3">
                                <h4 class="h4">Echéance</h4>
                                <small class="bg-yellow p-1 rounded">{{ task.dueDate | dateFromNow }}</small>
                            </div>
                        </div>
                    </div>
                    <!--end membres -->

                    <div class="row">
                        <div class="col-9">
                            <div class="row mb-5">
                                <div class="col-1 pr-0 text-center">
                                    <i class="fas fa-align-left "></i>
                                </div>
                                <div class="col-11">
                                    <h3><b>Description</b></h3>

                                    <p class="task-description" v-show="task.description && !editDescription"
                                       @click="editDescription = true">{{ task.description }} </p>

                                    <p v-show="!task.description"><a href="#" class="fake-description"
                                                                     v-if="!editDescription"
                                                                     @click="editDescription = true">{{ task.description
                                        ? task.description :
                                        'Ajouter une description détaillée...'}}</a>
                                    </p>
                                    <div v-if="editDescription">
                                        <textarea class="textarea-description" height="108px"
                                                  placeholder="Add a more detailed description…"
                                                  v-model="task.description"
                                                  v-click-outside="onClickOutsideDescription">
                                            {{ task.description }}
                                        </textarea>
                                        <button class="btn btn-sm btn-success" @click.prevent="updateDescription()">
                                            Enregistrer
                                        </button>
                                        <a href="#" @click.prevent="editDescription = false" style="color: #525F7F"><i
                                            class="fas fa-times ml-2"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--checklist-->
                            <div class="row">
                                <div class="col-1 pr-0 text-center">
                                    <i class="far fa-check-square"></i>
                                </div>
                                <div class="col-11">
                                    <h3><b>Checklist</b></h3>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-1 pr-0 text-center">
                                    <small>{{Math.ceil(task.percent)}} %</small>
                                </div>
                                <div class="col-11 ">
                                    <div class="progress align-middle mt-2">
                                        <div class="progress-bar"
                                             :class="task.percent === 100 ? 'bg-success' : 'bg-default' "
                                             role="progressbar"
                                             :style="'width:' + task.percent + '%;' "></div>
                                    </div>
                                </div>
                            </div>
                            <div v-for="(item,key) in task.subtasks">
                                <check-list-item :item="item"
                                                 :datakey="key"
                                                 @checkItem="$emit('checkItem')"
                                                 @subtask_deleted="updateItemList()">
                                </check-list-item>
                                <!--@checkItem="updatePercent"-->
                            </div>
                            <div class="row mb-5">
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <div v-if="addCheckListElement">
                                        <textarea class="textarea-description" name="" id="" height="108px"
                                                  placeholder="Add a more detailed description…"
                                                  v-model="item"
                                                  autofocus
                                                  v-click-outside="onClickOutsideItem"
                                                  style="outline-color:#5E71E3">
                                        </textarea>
                                        <button class="btn btn-sm btn-success" @click.prevent="addItem()">
                                            Ajouter
                                        </button>
                                        <a href="#" @click.prevent="addCheckListElement = false" style="color: #525F7F"><i
                                            class="fas fa-times ml-2"></i></a>
                                    </div>
                                    <button class="btn btn-sm btn-default mt-2" v-if="!addCheckListElement"
                                            @click="addCheckListElement = true">Ajouter un élement
                                    </button>
                                </div>
                            </div>
                            <!--endChecklist-->

                            <!--Attachments-->
                            <div class="row " v-if="task.attachments && task.attachments.length > 0">
                                <div class="col-1 pr-0 text-center">
                                    <i class="fas fa-paperclip"></i>
                                </div>
                                <div class="col-11">
                                    <h3><b>Fichiers attachés</b></h3>
                                </div>
                            </div>
                            <div class="row mb-5" v-if="task.attachments">
                                <div class="col-1 pr-0 text-center">

                                </div>
                                <div class="col-11">
                                    <div class="row">
                                        <div class="card mb-1 shadow-none border w-100"
                                             v-for="(attachment, key) in task.attachments">
                                            <div class="p-2 ">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="attachment-avatar-sm">
                                                                    <span class="avatar-title bg-default rounded">
                                                                        .{{ attachment.type }}
                                                                    </span>
                                                        </div>
                                                    </div>
                                                    <div class="col pl-0">
                                                        <a class="text-muted font-weight-bold"
                                                           :href="attachment.url"
                                                           download>
                                                            {{ attachment.name }}
                                                        </a>
                                                        <p class="mb-0" v-if="attachment.size > 10000 ">{{
                                                            (attachment.size / 1000000).toFixed(2) }} MB</p>
                                                        <p class="mb-0" v-else>{{ (attachment.size /
                                                            1000).toFixed(2) }} KB</p>

                                                    </div>
                                                    <div class="col-auto">
                                                        <!-- Button -->
                                                        <a href="#"
                                                           class="btn btn-link btn-lg text-muted"
                                                           @click.prevent="deleteFile(attachment.id, key)">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--endAttachments-->

                            <!--commentaires-->
                            <div class="row row mb-3">
                                <div class="col-1 pr-0 text-center">
                                    <i class="far fa-comment"></i>
                                </div>
                                <div class="col-11">
                                    <h3><b>Ajouter un commentaire</b></h3>
                                </div>
                            </div>
                            <div class="row row mb-5">
                                <div class="col-1 pr-0 d-flex justify-content-center align-item-center">
                                    <img :src="getProfilePhoto(user)"
                                         class="avatar avatar-task"
                                         data-toggle="tooltip"
                                         data-placement="bottom"
                                         :title="user.name">
                                </div>
                                <div class="col-11">
                                        <textarea v-model="comment" class="textarea-comment" name="" id="task-comment"
                                                  placeholder="Ajouter un commentaire"></textarea>

                                    <button class="btn btn-sm btn-success" @click.prevent="addComment()"
                                            :disabled="!comment">
                                        Enregistrer
                                    </button>
                                </div>
                            </div>
                            <!--activités-->
                            <div class="row row mb-3">
                                <div class="col-1 pr-0 text-center">
                                    <i class="far fa-comment"></i>
                                </div>
                                <div class="col-11">
                                    <h3><b>Activités</b></h3>
                                </div>
                            </div>
                            <div class="row row mb-3" v-for="(comment,key) in task.comments" :data-comment-id="key">
                                <div class="col-1 pr-0 text-center">
                                    <img :src="getProfilePhoto(comment.from)"
                                         class="avatar avatar-task"
                                         data-toggle="tooltip"
                                         data-placement="bottom"
                                         :title="comment.from.name">
                                </div>
                                <div class="col-11">
                                    <div><b style="font-size: 14px;">{{comment.from.name}}</b>
                                        <small>{{comment.created_at}}</small>
                                    </div>

                                    <div class="action-comment" v-if="!EditComment">
                                        <p class="bg-white current-comment m-0">{{ comment.content }}</p>
                                    </div>
                                    <div v-else>
                                        <textarea class="textarea-comment"
                                                  v-model="comment.content"
                                                  v-click-outside="onClickOutsideEditComment"
                                                  placeholder="Ajouter un commentaire"></textarea>

                                        <button class="btn btn-sm btn-success" @click.prevent="editComment(comment)"
                                                :disabled="!comment.content">
                                            Enregistrer
                                        </button>
                                    </div>
                                    <br>
                                    <a href="#" v-if="!EditComment" class="comment-actions-link" @click.prevent="EditComment= true">Editer </a><span v-if="!EditComment">-</span>
                                    <a href="#" v-if="!EditComment" class="comment-actions-link" @click.prevent="deleteComment(comment, key)"> Supprimer</a>
                                    <hr class="m-0">
                                </div>
                            </div>

                        </div>
                        <div class="col-3 d-flex flex-column p-0">
                            <h4 class="h4 text-uppercase">Ajouter</h4>
                            <button class="btn btn-sm btn-default mb-1" data-toggle="modal" data-target="#modalUser">
                                <i class="fas fa-users"></i>
                                <span>Utilisateurs</span>
                            </button>
                            <label for="files" class="btn btn-sm btn-default mb-1"><i
                                class="fas fa-paperclip"></i><span>Fichiers</span></label>
                            <input type="file"
                                   id="files"
                                   class="form-control form-control-alternative input-file"
                                   ref="files"
                                   placeholder="Ajouter des Fichiers"
                                   v-on:change="handleFileUploads"
                                   multiple>
                            <button class="btn btn-sm btn-default mb-1" data-toggle="modal" data-target="#modalDueDate">
                                <i class="fas fa-hourglass-half"></i>
                                <span>Echéance</span>
                            </button>
                            <h4 class="h4 mt-3">ACTIONS</h4>
                            <button class="btn btn-sm btn-default mb-1" @click="archive" v-if="!task.deleted_at">
                                <i class="fas fa-archive"></i>
                                <span>Archiver</span>
                            </button>
                            <button class="btn btn-sm btn-default mb-1" v-if="task.deleted_at" @click="resendToBoard">
                                <i class="fas fa-undo"></i>
                                <span>Réintégrer</span>
                            </button>
                            <button class="btn btn-sm btn-danger mb-1" v-if="task.deleted_at">
                                <i class="far fa-trash-alt"></i>
                                <span>Supprimer</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade modalUser" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajouter des membres</h5>
                        <button type="button" class="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <v-select label="fullname" :options="users" selectOnTab v-model="task.members" multiple
                                  class="form-control-alternative "></v-select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="addUserToTask">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalDueDate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter une de fin</h5>
                        <button type="button" class="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <datePicker name="date" v-model="task.dueDate" :config="options"></datePicker>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="addDueDateTask">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import vClickOutside from 'v-click-outside'
import AtTa from 'vue-at/dist/vue-at-textarea'
import At from 'vue-at'
import CheckListItem from './CheckListItemComponent'
import datePicker from 'vue-bootstrap-datetimepicker'

export default {
    components: {AtTa, At, CheckListItem, datePicker},
    directives: {
        clickOutside: vClickOutside.directive
    },
    props: ['task'],
    data() {
        return {
            editDescription: false,
            addCheckListElement: false,
            comment: '',
            EditComment: false,
            user: {},
            members: [],
            item: '',
            users: [],
            options: {
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-calendar-check',
                    clear: 'far fa-trash-alt',
                    close: 'far fa-times-circle'
                }
            }
        }
    },
    methods: {
        handleFileUploads(e) {
            this.$Progress.start();
            this.$Progress.set(0)
            let files = e.target.files || e.dataTransfer.files;
            for (let i = files.length - 1; i >= 0; i--) {
                this.files.push(files[i]);
            }
            let formData = new FormData();
            for (var i = 0; i < this.files.length; i++) {
                let file = this.files[i];
                formData.append('files[' + i + ']', file);
            }
            formData.append('type', 'Task')

            let self = this
            const config = {
                onUploadProgress: function (progressEvent) {
                    let percent = (Math.round((progressEvent.loaded * 100) / progressEvent.total)).toString()
                    if (percent === '100') {
                        self.$emit('uploadFinish')
                    }
                    swal({
                        title: percent + '%',
                        text: self.uploadText,
                        icon: "info",
                        dangerMode: false,
                    })
                    console.log(Math.round((progressEvent.loaded * 100) / progressEvent.total));
                }
            }
            axios.post('/api/attachment/' + this.task.id, formData, config)
                .then(response => {
                    for (let i = 0; i < response.data.length; i++) {
                        this.task.attachments.push(response.data[i])
                    }
                    this.files = []
                    this.$Progress.finish();
                    this.uploadText = 'Attendez la fin du téléchargement'
                }).catch(error => {
                this.$Progress.fail()
                this.errors = error.response.data.message;
                swal({
                    title: "Une erreur est survenue",
                    text: this.errors,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
            })
        },
        deleteFile(id, key) {
            this.$Progress.start();

            swal({
                title: "Etes vous sur ? ",
                text: "Cette manipulation est irréversible !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(willDelete => {
                    if (willDelete) {
                        axios.delete('/api/attachment/' + id, {
                            params: {
                                elementID: this.task.id,
                                type: this.type
                            }
                        }).then(response => {
                            this.task.attachments.splice(key, 1);
                            swal("Le fichier à été supprimé", {
                                icon: "success",
                            });
                            this.$Progress.finish();
                        }).catch(error => {
                            this.$Progress.fail()
                            this.errors = error.response.data.message;
                            swal({
                                title: "Une erreur est survenue",
                                text: this.errors,
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                        })
                    } else {
                        swal("Ok on annule !");
                    }
                });
        },
        onClickOutsideItem(event) {
            this.addCheckListElement = false
        },
        onClickOutsideEditComment(){
            this.EditComment = false
        },
        onClickOutsideDescription(event) {

            this.editDescription = false
            this.updateDescription()
        },
        getProfilePhoto(user) {
            if (user) {
                if (user.photo !== 'profile.png') {
                    return '/img/profile/' + user.name + '/' + user.photo
                } else {
                    return '/img/profile/profile.png'
                }
            }
        },
        updateDescription() {
            axios.put('/api/tasks/' + this.task.id + '/editDescription', {
                description: this.task.description
            }).then(() => {
                this.editDescription = false;
                this.$emit('addComment')

            })
        },
        addComment() {
            this.$Progress.start();
            let commentWithTag
            axios.post('/api/message/' + this.task.id, {
                content: this.comment,
                type: 'Task'
            }).then(response => {
                this.$Progress.finish();
                this.comment = ''
                this.$toasted.global.flash({message: "Votre commentaire à bien été ajouté"});
                this.task.comments.push(response.data.messages)
                this.$emit('addComment')
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail();
            })
        },
        getUserTag() {
            return str.split('@')[1];
        },
        addItem() {
            if (this.item !== "") {
                axios.post('/api/subtask', {
                    name: this.item,
                    task: this.task.id,
                }).then(response => {
                    this.$emit('checkItem')
                    this.$emit('addComment')

                    this.task.subtasks.push(response.data)
                    this.newSubTask = ""
                })

            }
        },
        updatePercent() {
            axios.get('/api/tasks/getPercent/' + this.task.id).then(response => {
                this.task.percent = response.data
            })
        },
        updateItemList(key) {
            this.task.subtasks.splice(key, 1)
        },
        addUserToTask() {
            axios.post('/api/tasks/addUser/' + this.task.id, {
                members: this.task.members
            }).then(response => {
                $('#modalUser').modal('hide')
            })
        },
        addDueDateTask() {
            axios.post('/api/tasks/addDueDate/' + this.task.id, {
                date: this.task.dueDate
            }).then(response => {
                $('#modalDueDate').modal('hide')
            })
        },
        archive() {
            axios.delete('/api/tasks/archive/' + this.task.id).then(response => {
                this.$emit('archivingTask', this.task)
                this.task.deleted_at = response.data.deleted_at
            })
        },
        resendToBoard() {
            axios.post('/api/tasks/resendToBoard/' + this.task.id).then(response => {
                this.task.deleted_at = response.data.deleted_at
            })
        },
        editComment(comment){
            axios.put('/api/message/' + comment.id, {
                content: comment.content,
            }).then(result => {
                this.EditComment = false
            })
        },
        deleteComment(comment, key){
            axios.delete('/api/message/delete/' + comment.id).then(result => {
                this.task.comments.splice(key, 1);
            })
        }
    },
    created() {
        axios.get('/api/profile').then(result => {
            this.user = result.data
        })
        axios.get('/api/user/getAllUsers').then(response => {
            console.log(response)
            this.users = response.data
        })
        this.$on('checkItem', () => {
            this.updatePercent()
        })
    }
}
</script>
<style>
    .modal .btn:not(:last-child) {
        margin: 0;
    }

    .fake-description {
        background-color: rgba(9, 30, 66, .08);
        border-radius: 3px;
        display: block;
        min-height: 40px;
        padding: 8px 12px;
        text-decoration: none;
        color: var(--light);
        font-size: 14px;
    }

    .fake-description:hover {
        color: var(--light);

    }

    .task-description {
        cursor: pointer;
    }

    .textarea-description {
        min-height: 108px;
        width: 100%;
        background: #fff;
        box-shadow: none;
        border: none;
        color: #172b4d;
        font-size: 14px;
        box-sizing: border-box;
        -webkit-appearance: none;
        border-radius: 3px;
        display: block;
        line-height: 20px;
        margin-bottom: 12px;
        padding: 8px 12px;
        transition-property: background-color, border-color, box-shadow;
        transition-duration: 85ms;
        transition-timing-function: ease;
    }

    .textarea-comment {
        border: 0;
        box-shadow: none;
        border-bottom: 30px solid transparent;
        background: #fff !important;
        height: 75px;
        margin: 0;
        min-height: 75px;
        padding: 9px 11px;
        padding-bottom: 0;
        width: 100%;
        resize: none;
        font-size: 14px;
        border-radius: 3px;
        border: 1px solid #eee;
    }

    .current-comment {
        padding: 8px 12px !important;
    }

    .action-comment {
        background-color: #fff;
        border-radius: 3px;
        box-shadow: 0 1px 2px -1px rgba(9, 30, 66, .25), 0 0 0 1px rgba(9, 30, 66, .08);
        box-sizing: border-box;
        clear: both;
        display: inline-block;
        margin: 4px 2px 4px 0;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .comment-actions-link{
        font-size: 11px;
        color: var(--default);
        text-decoration: underline;
    }
</style>
