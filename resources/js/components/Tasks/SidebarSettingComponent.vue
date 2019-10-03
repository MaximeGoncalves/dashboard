<template>
    <div class="board-menu">
        <div class="board-menu-container">
            <div class="board-menu-tab-content">
                <div class="board-menu-header d-flex justify-content-between px-3 py-2">
                    <i class="fas fa-arrow-left mt-1 pointer board-menu-header-icon " v-show="!menu"
                       @click="() => {viewArchive = false; viewMembers = false; menu = true}"></i>
                    <h3>Menu</h3>
                    <i class="fas fa-times pointer board-menu-header-icon" @click="close()"></i>

                </div>
                <div class="board-menu-content">
                    <div class="board-menu-content-frame" v-if="menu">
                        <div class="link-menu" @click="() => {viewArchive = true; viewMembers = false; menu = false}">
                            <b><i class="fas fa-archive"></i>
                                <span class="w-100">Archives</span></b>
                        </div>
                        <div class="link-menu" @click="() => {viewMembers = true;viewArchive = false; menu = false}">
                            <b><i class="fas fa-user-plus"></i>
                                <span class="w-100">Inviter</span></b>
                        </div>
                    </div>
                    <div class="board-menu-content-frame" v-if="viewArchive">
                        <h4>Tâches archivées</h4>
                        <div v-for="archive in archives"
                             :data-task-id="archive.id">
                            <task-component :task="archive" @viewTask="$emit('viewTask', archive.id)"
                                            class="card shadow-card-task mb-2"></task-component>
                            <div class="mt--2 mb-2">
                                <u><a href="#" class="text-default" @click="resendToBoard(archive.id)">
                                    <small>Restorer</small>
                                </a></u>
                                <u><a href="#" class="text-default">
                                    <small>Supprimer</small>
                                </a></u>
                            </div>
                        </div>
                    </div>
                    <div class="board-menu-content-frame" v-if="viewMembers">
                        <h4>Membres</h4>
                        <h6>Ajouter</h6>
                        <v-select label="fullname" :options="members" selectOnTab v-model="board.members"
                                  class="form-control-alternative" :on-change="save" multiple clearable></v-select>

                        <h6 class="mt-3">Membres actuels</h6>
                        <ul>
                            <li v-for="member in board.members">
                                {{ member.fullname }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TaskComponent from './TaskComponent'

export default {
    name: 'SidebarComponent',
    components: {TaskComponent},
    props: ['archives', 'board'],
    data() {
        return {
            archived: {},
            viewArchive: false,
            menu: true,
            viewMembers: false,
            members: [],
            chooseMembers: [],
        }
    },
    methods: {
        close() {
            this.viewArchive = false
            this.menu = true
            this.viewMembers = false

            this.$emit('close');
        },

        resendToBoard(id) {
            axios.post('/api/tasks/resendToBoard/' + id).then(response => {
                this.$emit('resendToBoard')
            })
        },
        save(val) {
            axios.post('/api/board/addMembers/' + this.board.id, {
                members: this.board.members
            }).then(response => {
                console.log(response)
            })
        }
    },
    mounted() {
        axios.get('/api/user/getAllUsers').then(response => {
            console.log(response)
            this.members = response.data
        })
    }
}
</script>

<style lang="scss">
    .board-menu-header-icon {
        line-height: 24px;
    }

    .pointer {
        cursor: pointer
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity .5s
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0

    }

    .board-menu {
        bottom: 0;
        position: absolute;
        right: 0;
        top: 0;
        transition-property: width, -webkit-transform;
        transition-property: transform, width;
        transition-property: transform, width, -webkit-transform;
        transition-duration: .1s;
        transition-timing-function: ease-in;
        -webkit-transform: translateX(339px);
        transform: translateX(339px);
        width: 339px;
        z-index: 5;
        &.active {
            box-shadow: 0 12px 24px -6px rgba(9, 30, 66, .25), 0 0 0 1px rgba(9, 30, 66, .08);
            -webkit-transform: translateX(0);
            transform: translateX(0);
        }
        .board-menu-container {
            display: flex;
            flex-direction: row;
            left: 0;
            bottom: 0;
            position: absolute;
            right: 0;
            top: 0;
        }
        .board-menu-header {
            box-sizing: border-box;
            flex: 0 0 auto;
            height: 48px;
            opacity: 1;
            overflow: hidden;
            padding: 0 6px 0 12px;
            position: relative;
            transition: opacity .2s ease-in;
            width: 100%;
        }
        .board-menu-tab-content {
            background-color: #f4f5f7;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            flex-wrap: nowrap;
            justify-content: flex-start;
            overflow: hidden;
        }
        .board-menu-content {
            box-sizing: border-box;
            display: flex;
            flex: 1 1 auto;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 12px 6px 12px 12px;
            width: 100%;
            height: 100%;
            .board-menu-content-frame {
                /*transition-property: opacity, -webkit-transform;*/
                /*transition-property: transform, opacity;*/
                /*transition-property: transform, opacity, -webkit-transform;*/
                /*transition-duration: .12s;*/
                /*transition-timing-function: ease-in;*/
                -webkit-transform: translateX(0);
                transform: translateX(0);
                flex: 1 auto;
                width: 100%;
                position: relative;
                .link-menu {
                    width: 100%;
                    padding: 5px 5px;
                    cursor: pointer;
                }
                .link-menu:hover {
                    background: #D5D9E0;
                    border-radius: 3px;
                    width: 100%;
                }
            }
        }

    }
</style>
