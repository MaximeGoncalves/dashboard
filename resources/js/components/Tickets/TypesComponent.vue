<template>
    <li class="list-group-item " @mouseover="hover = true" @mouseleave="hover = false">
      {{type.name}}
        <i class="fas fa-times cursor-pointer float-right mt-1" @click="deleteType" v-if="hover"></i>
    </li>

</template>

<script>
export default{
    props: ['type', 'index'],
    data(){
        return {
            hover: false,
        }
    },
    methods : {
        deleteType(){
          axios.delete('api/type/' + this.type.id).then(response => {
            this.$emit('deleteType', this.index)
          })
        },
    }
}
</script>
