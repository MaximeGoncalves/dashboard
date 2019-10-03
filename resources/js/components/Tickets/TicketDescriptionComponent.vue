<template>
    <div class="py-5 px-5 card">
        <div class="row ">
            <div class="col-05"></div>
            <div class="col-11">
                <div class="row align-items-center justify-content-between m-0 p-0">
                    <div>
                        <h1 class="d-inline" style="font-weight: 900">#{{ticket.id}} {{ this.ticket.topic
                            }}</h1>
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
                    <small class="text-right mr-3">{{ticket.created_at | FullDate }}</small>
                </div>
                <small>{{ ticket.user.fullname}}</small>
                -
                <small class="">{{ ticket.society.name}}</small>
            </div>
        </div>

        <div class="mb-4"></div>

        <!-- Technicien -->

        <div class="row mb-2" v-if="ticket.technician.user">
            <div class="col-05 d-flex justify-content-center">
                <i class="fas fa-user-tie text-default"></i>
            </div>
            <div class="col d-flex align-item-center">
                <small>{{ ticket.technician.user.fullname }}</small>
            </div>
        </div>
        <!--Description-->
        <div class="row">
            <div class="col-05 d-flex flex-column align-items-center">
                <i v-if="canEdit()" class="fa fa-pen-alt cursor-pointer text-muted mt-3" style="font-size:12px"
                   @click="editDescription = true"></i>
            </div>
            <div class="col">
                <div v-if="!editDescription">
                    <viewer :value="ticket.description"></viewer>
                </div>
                <div v-else>
                    <editor ref="tuiEditor"
                            v-model="ticket.description"
                            :options="editorOptions"
                            :html="editorHtml"
                            :mode="editorMode"
                            :previewStyle="editorPreviewStyle"
                            height="200px"
                            v-click-outside="onClickOutsideEditComment"></editor>
                    <div class="row mt-2 justify-content-end align-item-center">
                        <button class="btn btn-sm btn-success"
                                @click.prevent="updateDescription()">
                            Enregistrer
                        </button>
                        <i class="fas fa-times ml-2 mt-2 cursor-pointer"
                           style="font-size:20px; color: #525F7F"
                           @click.prevent="editDescription = false"></i>
                    </div>
                </div>

            </div>
        </div>
        <attachments-component class="mt-5" :element="ticket"
                               type="Ticket"></attachments-component>
        <!--End description -->

    </div>
</template>

<script>
import {Editor, Viewer} from '@toast-ui/vue-editor';
import vClickOutside from 'v-click-outside'
import AttachmentsComponent from '../AttachmentComponent'
export default {
    directives: {
        clickOutside: vClickOutside.directive
    },
    components: {
        Editor,
        Viewer,
        AttachmentsComponent,
    },
    props: ['ticket'],
    data (){
        return {
            editDescription: false,
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
        }
    },
    methods:{
        canEdit(){
            return this.ticket.user.id === window.user.id
        },
        onClickOutsideEditComment() {
            this.editDescription = false
            this.updateDescription()
        },
        updateDescription() {
            axios.put('/api/tickets/' + this.ticket.id + '/editDescription', {
                description: this.ticket.description
            }).then(() => {
                this.editDescription = false;
            })
        },
    }
}
</script>
