<template>
    <div>
        <div class="modal fade" id="ProjectSetting" tabindex="-1" role="dialog" aria-labelledby="modal"
             aria-hidden="true">
            <div class=" modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-primary">
                        <h5 class="modal-title text-white text-uppercase" id="exampleModalLabel">Gérer les projets</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="text-white">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-success mb-2" @click="edit = false">Ajouter un projet</button>

                                <ul class="list-group">
                                    <li class="list-group-item" :class="" @click="edit = true" v-for="p in projects">
                                        <a href="#" @click="editProject(p.id)">{{ p.name }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <div v-if="!edit">

                                    <h4 class="text-center">Ajouter un projet</h4>
                                    <hr>
                                    <form @submit.prevent="createProject">
                                        <div class="form-group">
                                            <label>Nom</label>
                                            <div class="form-group" :class="errors.name ? 'has-danger' : ''">
                                                <input type="text" class="form-control form-control-alternative"
                                                       v-model="project.name">
                                                <small v-if="errors.name" :class="errors.name ? 'text-danger' : ''"
                                                       v-for="error in errors.name">{{ error }}
                                                </small>
                                            </div>
                                            <button type="submit" class="btn btn-success mt-2 float-right">Créer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div v-else>
                                    <div v-if="!loading">
                                        <h4 class="text-center">Editer {{ project.name }}</h4>
                                        <div class="form-group" :class="errors.name ? 'has-danger' : ''">
                                            <input type="text" class="form-control form-control-alternative"
                                                   v-model="project.name">
                                            <small v-if="errors.name" :class="errors.name ? 'text-danger' : ''"
                                                   v-for="error in errors.name">{{ error }}
                                            </small>
                                        </div>

                                        <button class="btn btn-danger mt-2" @click="deleteProject">Supprimer</button>
                                        <button class="btn btn-primary mt-2 float-right" @click="updateProject">Editer
                                        </button>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center"
                                         style="min-height:100px;"
                                         v-if="loading">
                                        <pulse-loader :loading="loading" :color="'#5e72e4'"></pulse-loader>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <!--<pulse-loader :loading="loadingModal" :color="'#5e72e4'"></pulse-loader>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data() {
        return {
            projects: {},
            project: {},
            edit: false,
            loading: true,
            errors : []
        }
    },
    methods: {
        createProject() {
            this.$Progress.start()

            axios.post('/api/taskproject', {
                name: this.project.name,
            }).then(response => {
                this.projects.push(response.data)
                this.errors = []
                this.$Progress.finish()
                this.$toasted.global.flash({message: "Le projet à été créé."});
                this.project = {}

            }).catch(errors => {
                this.errors = errors.response.data.errors;
                this.$Progress.fail()
            })
        },
        deleteProject() {
            swal({
                title: "Etes vous sur ? ",
                text: "Cette manipulation est irréversible ! Elle supprimera l'ensemble des tâches qui y sont associées.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then(willDelete => {
                if (willDelete) {
                    axios.delete('/api/taskproject/' + this.project.id).then(() => {
                        swal("Le projet à été supprimé", {
                            icon: "success",
                        });
                        this.project = {}
                        this.$emit('updateProjects')
                    }).catch(error => {
                        this.errors = error.response.data.message;
                        swal(this.errors);

                    })
                } else {
                    swal("La suppression à été annulé.");
                }
            });
        },
        getProjects() {
            axios.get('/api/taskproject').then(response => {
                this.projects = response.data
            })
        },
        editProject(id) {
            this.loading = true
            axios.get('/api/taskproject/' + id).then(response => {
                this.project = response.data
                this.loading = false
            })
        },
        updateProject() {
            axios.put('/api/taskproject/' + this.project.id, {
                name: this.project.name
            }).then(response => {
                this.$emit('updateProjects')
            })
        }
    },
    mounted() {
        this.getProjects();
        this.$on('updateProjects', function () {
            this.getProjects();
        })
    }
}

</script>
