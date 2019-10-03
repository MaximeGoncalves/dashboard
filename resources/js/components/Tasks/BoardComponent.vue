<template>
    <div class="board-main-content">
        <!--<div class="class=dropdown">-->
            <!--<button class="nav-link dropdown-toggle btn btn-sm btn-seconday" href="#" id="navbarDropdownMenuLink"-->
                    <!--role="button"-->
                    <!--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                <!--Boards-->
            <!--</button>-->
            <!--<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">-->
                <!--<h4 style="padding: .5rem 1rem;">Personal Board</h4>-->
                <!--<a class="dropdown-item" href="#">Action</a>-->
                <!--<a class="dropdown-item" href="#">Another action</a>-->
                <!--<a class="dropdown-item" href="#">Something else here</a>-->
            <!--</div>-->
            <!--<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">-->
                <!--<a class="dropdown-item" href="#">Create new board</a>-->
            <!--</div>-->
        <!--</div>-->
        <div class="board-header">
            <span class="h3">{{board.name}}</span>
            <div class="board-button-header float-right btn btn-lighter" style="font-size: 10px;" @click="showMenu()">
                Paramètres
            </div>
        </div>
        <div class="board-canvas">
            <draggable id="board" :list="lists" @end="updateOrder($event)"
                       :options="{dragClass: 'dragClass',swapThreshold: 5,emptyInsertThreshold: 1, handle: '.card-header'}">
                <div class="list-wrapper" v-for="(list, key) in lists">
                    <list-component :list="list"
                                    :key="list.id"
                                    :datakey="key"
                                    @delete_list="delete_list"
                                    @viewTask="viewTask($event)"
                                    :data-list-id="list.id"></list-component>
                </div>
                <div class="list-wrapper">
                    <a href="#" @click="newListState = true">
                        <div v-if="!newListState" class="bg-default p-2 rounded">
                            <span class="text-white ml-2"><i class="fas fa-plus mr-2"></i>Ajouter une liste</span>
                        </div>
                    </a>
                    <div v-if="newListState" class="card shadow rounded p-2 bg-transparent">
                        <input type="text" class="form-control form-control-alternative"
                               placeholder="Entrer un titre"
                               v-model="list">
                        <div class="row mt-1">
                            <div class="col">
                                <button class="btn btn-sm btn-success" @click="createList()">Ajouter</button>
                                <a href="#" @click="newListState = false" style="color: #525F7F"><i
                                    class="fas fa-times ml-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </draggable>
        </div>
        <sidebar-setting :archives="archived" :board="board" @viewTask="viewTask" @close="hideMenu" @resendToBoard="eventUpdateTask"></sidebar-setting>
        <modal-task-component :task="task"
                              @addComment="eventUpdateTask"
                              @checkItem="eventUpdateTask"
                              @archivingTask="archivingTask"

        ></modal-task-component>
    </div>

</template>

<script>
import './scroll'
import ModalTaskComponent from './ModalTaskComponent'
import draggable from 'vuedraggable'
import datePicker from 'vue-bootstrap-datetimepicker'
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css'
import taskproject from './TaskProjectComponent'
import moment from 'moment'
import attachmentsComponent from '../AttachmentComponent'
import listComponent from './ListComponent'
import sidebarSetting from './SidebarSettingComponent'

export default {
    components: {
        sidebarSetting,
        draggable,
        datePicker,
        taskproject,
        attachmentsComponent,
        listComponent,
        ModalTaskComponent,

    },

    data() {
        return {
            // errors: [],
            // pending: [],
            // open: [],
            // review: [],
            // close: [],
            // projects: [],
            // newTask: '',
            // newSubTask: '',
            task: {
                list: ''
            },
            // loading: true,
            // loadingModal: true,
            // addSubtask: false,
            // users: [],
            // editMode: false,
            // options: {
            //     format: 'YYYY-MM-DD HH:mm:ss',
            //     useCurrent: false,
            // },
            // files: [],
            // checked: false,
            // message: '',
            // messages: {},
            // filter: {name: 'Tous', id: 0},

            newListState: false,
            list: '',
            lists: [],
            board: {},
            archived : {}
        }
    },
    methods: {
        getBoard() {
            let board = this.$route.params.id;
            axios.get('/api/board/' + board).then(response => {
                this.board = response.data
                this.lists = response.data.lists
                this.archived = response.data.tasks
            })
        },
        createList() {
            let order = this.lists.length + 1;
            axios.post('/api/lists', {
                name: this.list,
                board: this.board.id,
                order: order,
            }).then(response => {
                this.list = ''
                this.lists.push(response.data)
            })
        },
        updateOrder($event) {
            let newLists = this.lists.map((list, index) => {
                list.order = index + 1;
                return list;
            })
            axios.post('/api/lists/order', {
                lists: newLists,
                board: this.board.id
            }).then(response => {
            })

        },
        delete_list(key) {
            console.log(key)
            this.lists.splice(key, 1);
        },
        viewTask(id) {
            axios.get('/api/tasks/' + id).then(response => {
                this.task = response.data
            })
            $('#taskModal').modal('show')
        },
        archivingTask(task) {
            this.task = task
            this.getBoard()
        },
        eventUpdateTask() {
            this.getBoard()
        },
        showMenu() {
            $('.board-menu').addClass('active')
        },
        hideMenu() {
            $('.board-menu').removeClass('active')
        },
        getArchives() {
            axios.get('/api/tasks/archive').then(response => {
                this.archived = response.data
            })
        },

//Recupere les photo de profile
        getProfilePhoto(user) {
            if (user) {
                if (user.photo !== 'profile.png') {
                    return '/img/profile/' + user.name + '/' + user.photo
                } else {
                    return '/img/profile/profile.png'
                }
            }
        },

// Ajoute une tache
// addTask()
// {
//     this.editMode = false
//     this.$Progress.start();
//     axios.post('/api/tasks', {
//         project: this.task.project,
//         name: this.task.name,
//         priority: this.task.priority,
//         description: this.task.description,
//         users: this.task.users,
//         dueDate: this.task.dueDate,
//     }).then(response => {
//         $('#addModal').modal('hide')
//         this.$emit('updateSub')
//         this.$Progress.finish();
//         this.$toasted.global.flash({message: "La tâche à été créée. "});
//     }).catch(error => {
//         this.errors = error.response.data.errors;
//         this.$Progress.fail()
//     });
// }
// ,

// Edite une tache
// editTask(id)
// {
//     this.loadingModal = true
//     this.editMode = true
//     this.errors = [];
//     this.openAddModal()
//     axios.get('/api/tasks/' + id).then(response => {
//         this.task = response.data
//         this.loadingModal = false
//     })
// }


//Affiche une tache
// showTask(id)
// {
//     this.addSubtask = false
//     this.loadingModal = true
//     this.openmodal()
//     axios.get('/api/tasks/' + id).then(response => {
//         this.task = response.data
//         this.loadingModal = false
//     })
// }
// ,
// Ajoute une sous tache
//Update lors des déplacement des box dans les différentes colonnes.
// updateStateOfTask(event, state)
// {
//     let id = event.item.getAttribute('data-task-id');
//     axios.put('/api/tasks/updateStateOfTask/' + id, {
//         state: state
//     }).then(response => {
//     })
// }
// ,

// updateTask(id)
// {
//     this.$Progress.start();
//     axios.put('/api/tasks/' + id, {
//         project: this.task.project,
//         name: this.task.name,
//         priority: this.task.priority,
//         description: this.task.description,
//         users: this.task.users,
//         dueDate: this.task.dueDate,
//     }).then(response => {
//         $('#addModal').modal('hide')
//         this.$emit('updateSub')
//         this.$Progress.finish();
//         this.$toasted.global.flash({message: "La tâche à été mis à jour. "});
//     }).catch(error => {
//         this.errors = error.response.data.errors;
//         this.$Progress.fail()
//     });
// }
// ,

// Supprime une tache
// deleteTask(id, key, state)
// {
//     swal({
//         title: "Etes vous sur ? ",
//         text: "Cette manipulation est irréversible !",
//         icon: "warning",
//         buttons: true,
//         dangerMode: true,
//     })
//         .then(willDelete => {
//             if (willDelete) {
//                 axios.delete('/api/tasks/' + id).then(response => {
//                     if (state === "close") {
//                         this.close.splice(key, 1);
//                     } else if (state === "open") {
//                         this.open.splice(key, 1);
//                     }
//                     else if (state === "review") {
//                         this.review.splice(key, 1);
//                     }
//                     else if (state === "pending") {
//                         this.pending.splice(key, 1);
//                     }
//                     swal("La tâche à été supprimée", {
//                         icon: "success",
//                     });
//                 }).catch(error => {
//                     this.errors = error.response.data.message;
//                     swal(this.errors);
//                 })
//             } else {
//                 swal("Opération annulé!");
//             }
//         });
// }
// ,

//Charge les taches
// loadTasks()
// {
//     this.loading = true
//     axios.get('/api/tasks').then(response => {
//         for (var i = 0; i < response.data.tasks.length; i++) {
//             if (response.data.tasks[i].state === 'pending') {
//                 this.pending.push(response.data.tasks[i])
//             } else if (response.data.tasks[i].state === 'open') {
//                 this.open.push(response.data.tasks[i])
//             } else if (response.data.tasks[i].state === 'review') {
//                 this.review.push(response.data.tasks[i])
//             } else {
//                 if (moment(response.data.tasks[i].updated_at).isSameOrAfter(moment(), 'day')) {
//                     this.close.push(response.data.tasks[i])
//                 }
//
//             }
//         }
//         this.users = response.data.users
//         this.projects = response.data.projects
//         // this.subtasks = response.data.subtasks
//         this.loading = false
//         this.projects.push({name: 'Tous', id: 0})
//     })
// }
// ,

//Met à jours une sous tache
// updateSub($event, id)
// {
//     axios.put("/api/subtask/" + id, {
//         state: $event.target.checked
//     }).then(() => {
//         this.$emit('updateSub')
//     })
//
// }
// ,
//
// // Function pour ouvrir les différentes modal
// openmodal()
// {
//     $('#modal').modal('show')
// }
// ,
// openAddModal()
// {
//     this.task = {}
//     this.errors = [];
//     this.loadingModal = false
//     $('#addModal').modal('show')
//
// }
// ,
//
// getPercent()
// {
//     let total = this.task.subtasks.length
//     var checked = 0
//     for (let i = 0; i < this.task.subtasks.length; i++) {
//         if (this.task.subtasks[i].state === 1) {
//             checked++
//         }
//     }
//     this.task.percent = (checked * 100) / total
// }
// ,
// addMessage()
// {
//     this.$Progress.start();
//     axios.post('/api/message/' + this.task.id, {
//         content: this.message,
//         type: 'Task'
//     }).then(response => {
//         this.$Progress.finish();
//         this.message = ""
//         this.$toasted.global.flash({message: "Votre commentaire à bien été ajouté"});
//         this.task.comments.push(response.data.messages)
//     }).catch(error => {
//         this.errors = error.response.data.errors;
//         this.$Progress.fail();
//     })
// }
// ,
// //Met à jour l'affichage
// updateDisplay()
// {
//     axios.get('/api/tasks').then(response => {
//         this.pending = []
//         this.open = []
//         this.review = []
//         this.close = []
//         for (var i = 0; i < response.data.tasks.length; i++) {
//             if (response.data.tasks[i].state === 'pending') {
//                 this.pending.push(response.data.tasks[i])
//             } else if (response.data.tasks[i].state === 'open') {
//                 this.open.push(response.data.tasks[i])
//             } else {
//                 this.close.push(response.data.tasks[i])
//             }
//         }
//     })
// }
// ,
// FilterByProject()
// {
//     this.loading = true
//     this.pending = []
//     this.open = []
//     this.review = []
//     this.close = []
//     axios.get('/api/tasks', {
//         params: {
//             filterBy: this.filter.id
//         }
//     }).then(response => {
//         for (var i = 0; i < response.data.tasks.length; i++) {
//             if (response.data.tasks[i].state === 'pending') {
//                 this.pending.push(response.data.tasks[i])
//             } else if (response.data.tasks[i].state === 'open') {
//                 this.open.push(response.data.tasks[i])
//             } else if (response.data.tasks[i].state === 'review') {
//                 this.review.push(response.data.tasks[i])
//             } else {
//                 this.close.push(response.data.tasks[i])
//             }
//         }
//         this.users = response.data.users
//         this.projects = response.data.projects
//         this.loading = false
//         this.projects.push({name: 'Tous', id: 0})
//     })
// }
// ,


    },
    created() {
        this.getBoard()
        // this.loadTasks()
        // this.getLists()
        // this.$on('addTask', function () {
        //     this.loadTasks()
        // })
        this.$on("delete_list", function (key) {
            this.lists.splice(key, 1);
        })

    }
}
</script>

<style>
    .label-file {
        cursor: pointer;
        color: #5e72e4;
        font-weight: bold;
        font-size: 16px;
    }

    .label-file:hover {
        color: #8965e0;
    }

    .input-file {
        display: none;
    }

    .flip-list-move {
        transition: transform 0.5s;
    }

    .no-move {
        transition: transform 0s;
    }

    .border-left-primary {
        border-left: solid 5px var(--primary);
    }

    .border-left-yellow {
        border-left: solid 5px var(--orange);
    }

    .border-left-success {
        border-left: solid 5px var(--green);
    }

    .border-left-danger {
        border-left: solid 5px var(--danger);

    }

    .task {
        position: relative;
        margin-bottom: 1px;
        padding: 10px 10px 10px 10px;
    }

    .avatar-task {
        width: 26px;
        height: 26px;
    }

    .task.nav-pills .nav-link {
        border-bottom: 2px solid #dee2e6;
        color: #6c757d;
    }

    .task.nav-pills .nav-link.active {
        color: #172b4d;
        background-color: transparent;
        border-bottom: 2px solid #5e72e4;
    }

    .attachment-avatar-sm {
        height: 3rem;
        width: 3rem;
    }

    .avatar-title {
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        background-color: #727cf5;
        color: #fff;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        font-weight: 600;
        height: 100%;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        width: 100%;
    }

    .tasks .card-body, .tasks .card-footer {
        padding: 1rem 1rem 0.25rem 1rem;
    }

    .tasks .card-footer {
        padding: 0.25rem 1rem 0.25rem 1rem;
    }

    /*board*/
    .board-main-content {
        position: relative;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        min-height: calc(100vh - (82px));
        display: flex;
        flex-direction: column;
        margin-right: 0;
        transition: margin .1s ease-in;
        overflow: hidden;
    }

    .board-header {
        flex: none;
        height: auto;
        padding: 8px 4px 8px 8px;
        position: relative;
        transition: padding .1s ease-in;
    }

    .board-canvas {
        position: relative;
        flex-grow: 1;
        height: 100%;
    }

    #board {
        user-select: none;
        white-space: nowrap;
        margin-bottom: 8px;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 8px;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;

    }

    .list-wrapper {
        width: 272px;
        margin: 0 4px;
        height: 100%;
        box-sizing: border-box;
        display: inline-block;
        vertical-align: top;
        white-space: nowrap;
        z-index: 10;
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>
