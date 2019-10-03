<template>
    <div class="card bg-card-list" style="height: auto">
        <div class="card-header bg-card-list p-2 border-0">
            <div class="row justify-content-between p-0 m-0" style="position:relative">
                    <strong class="card-list-title" v-if="!EditListName" @click="EditListName= true"
                            style="cursor:pointer">{{list.name}}</strong>
                    <input class="form-control w-100" v-else="EditListName" v-model="list.name"
                           v-click-outside="onClickOutside"/>
                <div class="text-right edit-list-title" v-if="!EditListName">
                    <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only mr-0" href="#"
                           style="color:#525f7f"
                           role="button"
                           data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <form>

                                <a class="dropdown-item" href="#"
                                   @click.prevent="destroy(list.id)">
                                    <i class="fas fa-trash-alt"></i>Delete
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-1">
            <draggable :list="list.tasks" @end="updateTaskList($event)" group="task" :sort="true"
                       :options="{filter: 'textarea',preventOnFilter: false}">
                <div v-for="(task, key) in list.tasks" :key="task.order" class="card shadow-card-task mb-2"
                     :data-task-id="task.id">
                        <task :task="task"
                              :notifications="notifications"
                              @viewTask="$emit('viewTask', $event)"></task>
                </div>
            </draggable>
        </div>
        <div class="p-2 footer-task" v-if="!newTaskState">
            <span @click.prevent="newTaskState = true" style="cursor:pointer " class="w-100"><small>Ajouter une tâche</small></span>
        </div>
        <div class="p-2" v-else>
                <textarea name="task" id="task" rows="3" class="form-control" style="height:50px"
                          placeholder="Entrer un titre" v-model="task" autofocus></textarea>
            <div class="row mt-1">
                <div class="col">
                    <button class="btn btn-sm btn-success" @click.prevent="addTask()">Ajouter</button>
                    <a href="#" @click.prevent="newTaskState = false" style="color: #525F7F"><i
                        class="fas fa-times ml-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
import Task from './TaskComponent'
import vClickOutside from 'v-click-outside'

export default {
    directives: {
        clickOutside: vClickOutside.directive
    },
    props: ['list', 'datakey'],
    components: {
        draggable,
        Task
    },
    data() {
        return {
            newTaskState: false,
            task: '',
            EditListName: false,
            notifications: {}
        }
    },
    methods: {
        onClickOutside(event) {
            console.log('Clicked outside. Event: ', event)
            if (this.list.name) {
                this.EditListName = false
                axios.put('/api/lists/' + this.list.id, {
                    name: this.list.name
                }).then(response => {
                    console.log(response)
                })
            }
        },
        addTask() {
            axios.post('/api/tasks', {
                name: this.task,
                list: this.list.id,
                board: this.list.board_id
            }).then(response => {
                this.task = ''
                console.log(response)
                this.list.tasks.push(response.data.task)
            }).catch(error => {
                console.log(error)
            });
        },
        updateTaskList(event){
            let id = event.item.getAttribute('data-task-id')
            let list_id = event.to.parentNode.parentNode.getAttribute('data-list-id')
            axios.put('/api/tasks/'+ id + '/updateList', {
                list: list_id
            }).then(response => {
            }).catch(error => {
                console.log(error)
            })
        },
        destroy(id) {
            axios.delete('/api/lists/' + id).then(response => {
                this.$emit('delete_list', this.datakey)
            })
        },
    },
}

</script>
<style>
    .bg-card-list{
        background: #E2E4E9;
        /*background: #5E71E3;*/
    }
    .shadow-card-task {
        background-color: #fff;
        border-radius: 3px;
        box-shadow: 0 1px 0 rgba(9,30,66,.25);
        cursor: pointer;
    }

    .shadow-card-task:hover {
        background-color: #eee;
    }

    .card-list-title{
        clear: both;
        display: block;
        overflow: hidden;
        word-wrap: break-word;
        width:inherit;
        white-space: pre-wrap;
    }
    .edit-list-title{
        position:absolute;
        top: 0;
        right:0;
        font-size: 14px;
    }

</style>
