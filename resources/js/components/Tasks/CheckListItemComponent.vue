<template>
    <div class="row checklist-item" :class="hovering()" @mouseover="hover = true" @mouseleave="hover = false">
        <div class="col-1 pr-0 d-flex justify-content-center align-item-center">
            <div class="checklist-item-checkbox" :class="item.state == 1 ? 'complete' : '' " @click="CheckItem">
                <i class="fas fa-check" v-if="item.state == 1" style="font-size: 14px;"></i>
            </div>
        </div>
        <div class="col-11" @click="editItem = true">
            <span v-if="!editItem" class="check-list-name" :class="item.state == 1 ? 'complete' : '' "
            >{{item.name}}</span>

            <div v-else>
                <textarea class="textarea-description" name="" id="" height="56px" style="min-height: 56px !important;"
                  v-model="item.name"
                  v-click-outside="vClickOutside">
                  </textarea>
                <button class="btn btn-sm btn-success" @click.prevent="updateSubtask()">
                    Enregistrer
                </button>
                <a href="#" @click.prevent="editItem = false" style="color: #525F7F"><i
                    class="fas fa-times ml-2" @click.prevent="editItem = false"></i></a>
            </div>
        </div>
        <div class="dropdown checklist-actions" v-if="!editItem">
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
                       @click.prevent="Destroy">
                        <i class="fas fa-trash-alt"></i>Delete
                    </a>
                </form>
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
    props: ['item', 'datakey'],
    data() {
        return {
            editItem: false,
            hover:false,
        }
    },
    methods: {
        vClickOutside(event) {
            if (this.item.name) {
                this.updateSubtask()
            } else {
                alert('Nom vide impossible')
            }
        },
        updateSubtask(){
            this.editItem = false
            axios.put('/api/subtask/name/' + this.item.id, {
                name: this.item.name
            }).then(response => {
                this.editItem = false
            })
        },
        CheckItem($event) {
            let state = !this.item.state
            axios.put("/api/subtask/state/" + this.item.id, {
                state: state
            }).then(response => {
                this.item.state = state
                this.$emit('checkItem')
            })
        },
        Destroy() {
            axios.delete('/api/subtask/destroy/' + this.item.id).then(response => {
                console.log('delete')
                this.$emit('subtask_deleted', this.datakey)
            })
        },
        hovering(){
          if(this.hover && !this.editItem){
              return 'hover'
          }else if (this.hover && this.editItem){
              return ''
          }

        },
    }
}
</script>
<style>
    .checklist-item {
        position: relative;
        cursor: pointer;
    }

    .checklist-item.hover {
        background: #E2E4E9;
    }

    .checklist-item-checkbox {
        background-color: #fafbfc;
        border-radius: 3px;
        box-shadow: inset 0 0 0 2px #dfe1e6;
        box-sizing: border-box;
        line-height: 20px;
        overflow: hidden;
        text-indent: 100%;
        height: 20px;
        width: 20px;
        white-space: nowrap;
        left: 0;
        margin: 6px;
        text-align: center;
        top: 0;
    }

    .checklist-item-checkbox.complete {
        background-color: white;
        border-radius: 3px;
        box-shadow: inset 0 0 0 2px #dfe1e6;
        box-sizing: border-box;
        line-height: 20px;
        overflow: hidden;
        text-indent: 100%;
        height: 20px;
        width: 20px;
        white-space: nowrap;
        background-color: rgba(9, 30, 66, .04);
        box-shadow: none;
        text-indent: 0;
    }

    .check-list-name {
        font-size: 14px;
        line-height: 32px;
    }

    .check-list-name.complete {
        text-decoration: line-through;;
    }

    .checklist-actions {
        position: absolute;
        top: 0;
        right: 0;
        width: 2rem;
        height: 2rem;
        display: none;
    }

    .checklist-item:hover .checklist-actions {
        display: block
    }
</style>
