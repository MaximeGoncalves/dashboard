<template>
    <div class="card-body p-1" @click="$emit('viewTask', task.id)">
        <div style="height:auto;">
            <span class="card-task-title mb-2" v-if="!editTaskTitle" >{{task.name}}</span>
            <textarea class="card-task-title form-control w-100" v-else
                      v-model="task.name"
                      v-click-outside="onClickOutside" @click.stop></textarea>
            <i class="fas fa-pen edit-task-title" v-if="!editTaskTitle" @click.stop="editTaskTitle = true"></i>
        </div>
        <div class="badges w-100 px-1">
            <div class="badge is-icon-only" title="Cette carte comporte une description." v-if="task.description">
                <i class="fas fa-align-left"></i>
            </div>
            <div class="badge d-flex" :class="Date.parse(this.task.dueDate) > new Date() ? 'bg-yellow' : 'bg-danger' " title="écheance" v-if="task.dueDate">
                <i class="far fa-clock icon-comment ml-1"></i>
                <!--style="font-size: 12px;"-->
                <div class="badge-text text-comment text-lowercase" ><small>{{task.dueDate | shortDate }}</small></div>
            </div>
            <div class="badge d-flex " title="Commentaires." v-if="task.comments && task.comments != 0">
                <i class="far fa-comment icon-comment"></i>
                <!--style="font-size: 12px;"-->
                <div class="badge-text text-comment">{{task.comments.length}}</div>
            </div>
            <div class="badge d-flex" :class="getChecked() == task.subtasks.length ? 'bg-success' : '' " title="Checklist" v-if="task.subtasks && task.subtasks != 0">
                <i class="far fa-check-square text-comment"></i>
                <div class="badge-text text-comment" v-if="task.subtasks">{{ getChecked() }}/{{ task.subtasks.length }}</div>
            </div>
            <div class="badge d-flex " title="Fichiers" v-if="task.attachments && task.attachments != 0">
                <i class="fas fa-paperclip text-comment"></i>
                <div class="badge-text text-comment">{{ task.attachments.length }}</div>
            </div>
            <div class="badge d-flex badge-member" title="members" v-if="task.members && task.members != 0">
                <img :src="getProfilePhoto(member)"
                     class="avatar avatar-task mr-1 "
                     data-toggle="tooltip"
                     data-placement="bottom"
                     :title="member.name"
                     v-for="member in task.members">
            </div>
        </div>
    </div>
</template>

<script>
import vClickOutside from 'v-click-outside'
export default {
    directives: {
        clickOutside: vClickOutside.directive
    },
    props: ['task', 'notifications'],

    data() {
        return {
            editTaskTitle: false,
        }
    },
    methods: {
        getProfilePhoto(user) {
            if (user) {
                if (user.photo !== 'profile.png') {
                    return '/img/profile/' + user.name + '/' + user.photo
                } else {
                    return '/img/profile/profile.png'
                }
            }
        },
        onClickOutside(event) {
            if (this.task.name) {
                this.editTaskTitle = false
                axios.put('/api/tasks/' + this.task.id, {
                    name: this.task.name
                }).then(response => {
                    console.log(response)
                })
            }
        },
        getChecked(){
            let checked = 0;
            let sub = this.task.subtasks
            for(var i=0 ; i < sub.length ; i++){
                if(sub[i].state === 1){
                    checked++
                }
            }
            return checked
        },
        viewTask(){
            if($(event.target).hasClass('edit-task-title')){
                return false;
            }else{
                this.$emit('viewTask', this.task.id)
            }
            // if(event.target(''))
            this.$on('checkItem', function (){

            })
        },
    },
    mounted(){
    }
}
</script>

<style>
    .badges{
        display:flex;
        float: left;
        max-width: 100%;
        position:relative;
    }
    .badge-text{
        font-size: 12px;
        padding: 0 4px 0 2px;
        vertical-align: top;
        white-space: nowrap;
        line-height: 20px;
    }
    .is-icon-only{
        color: var(--default)!important;
        line-height: 20px;
    }
    .badge{
        display: inline-block;
        margin: 0 8px 4px 0;
        max-width: 100%;
        min-height: 20px;
        overflow: hidden;
        position: relative;
        padding: 2px;
        text-decoration: none;
        text-overflow: ellipsis;
        color: white;
        font-size: 12px;
    }
    .badge:last-child{
        margin: 0 0px 4px 0;

    }
    .badge-icon-notification{
        padding: 0 4px 0 2px;
        vertical-align: middle;
        height: 20px;
        font-size: 16px;
        line-height: 20px;
        width: 20px;
    }
    .icon-comment, .text-comment{
        color: var(--default) !important;
        line-height:20px;
    }
    .badge-member{
        position : absolute;
        top: -5px;
        right: 0;

    }
    .card-task-title {
        height:auto;
        font-size: 14px;
        clear: both;
        display: block;
        overflow: hidden;
        word-wrap: break-word;
        width:inherit;
        white-space: pre-wrap;
    }
    .edit-task-title{
        position:absolute;
        top: .5rem;
        right: .5rem;
        font-size: 14px;
        display:none;
    }
    .card-task-title:hover + .edit-task-title {
        display:block;
        z-index: 20;
    }
    .edit-task-title:hover{
        display: block;
    }
    .footer-task:hover {
        background: #adb5bd;
    }
</style>
