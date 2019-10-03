<template>
    <div >
        <button class="btn btn-warning" @click="ProjectSetting">Gérer les projets</button>
        <projectSetting></projectSetting>
    </div>
</template>

<script>
import projectSetting from './ProjectSettingComponent'
export default {
    components: {
        projectSetting,
    },
    data () {
        return {

        }
    },
    methods: {
        ProjectSetting() {
            $('#ProjectSetting').modal('show')
        }
    }
}
</script>
