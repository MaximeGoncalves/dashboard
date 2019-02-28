<template>
    <div>
        <div class="row">
            <div class="col">
                <div class="card shadow" v-if="$gate.isAdmin()">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Users</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="#"
                                   class="btn btn-sm btn-primary" @click="NewModal">Add
                                    Login</a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Service</th>
                                <th scope="col">Url</th>
                                <th scope="col">Identifiant</th>
                                <th scope="col">Password</th>
                                <th scope="col">Société</th>
                                <th scope="col">Modify</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="login in logins">
                                <td>{{ login.name }}</td>
                                <td>{{ login.url }}</td>
                                <td>{{ login.username }}</td>
                                <td>{{ login.password }}</td>
                                <td>{{ login.society.name }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <form action="https://argon-dashboard-laravel.creative-tim.com/user/8642"
                                                  method="post">
                                                <input type="hidden" name="_token"
                                                       value="F3fqjyIFWnoHEzAeYoetOWiaHkr9ZdnoaXaxFsx7">
                                                <input
                                                    type="hidden" name="_method" value="delete">
                                                <a class="dropdown-item"
                                                   href="#" @click.prevent="EditLogin(login.id)">Edit</a>
                                                <a class="dropdown-item" href="#"
                                                   @click.prevent="deleteLogin(login.id)">
                                                    Delete
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">

                    </div>
                </div>
                <div v-else>
                    <not-found></not-found>
                </div>
            </div>
        </div>

        <!-- Modal -->
            <logins-modal></logins-modal>
    </div>
</template>

<script>
import LoginsModal from './ModalLoginsComponent'
import Base64 from 'crypto-js/enc-base64';
export default {
    components : {
      LoginsModal
    },
    data() {
        return {
            user: {
                society:{}
            },
            society: [],
            errors: [],
            logins: {},
            editMode: true,
            selected: "",
            key: "ITPl8kGKDzILfMHnZQH/AAUQHham4catr9KllIn/zWE="
        }
    },
    methods: {
        loadLogins() {
            axios.get('/api/logins').then((response) => {
                this.logins = response.data.logins
            });

        },
        UpdateUser(user) {
            this.$Progress.start();
            axios.put('/api/user/' + user, {
                name: this.user.name,
                fullname: this.user.fullname,
                email: this.user.email,
                password: this.user.password,
                role: this.user.role,
                society: this.user.society_id,
                profession: this.user.profession,
                phone: this.user.phone,
            }).then((response) => {
                $('#addNewUser').modal('hide')
                Fire.$emit('UpdateUser')
                this.$toasted.global.flash({message: "L'utilisateur à été mis à jour"});

                this.$Progress.finish();
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail()
            })
        },
        async EditUser(user) {
            this.errors = []
            this.editMode = true
            let getUser = await axios.get('/api/user/' + user).then((user) => {
                this.user = user.data
            })
            this.selected = this.user.society_id
            $('#addNewUser').modal('show')
        },
        NewModal() {
            this.editMode = false
            this.errors = []
            this.user.name = ''
            this.user.fullname = ''
            this.user.email = ''
            this.user.password = ''
            this.user.role = ''
            this.user.society = ''
            $('#addNewUser').modal('show')
        },
        createUser() {
            this.$Progress.start();
            axios.post('/api/user', {
                name: this.user.name,
                fullname: this.user.fullname,
                email: this.user.email,
                password: this.user.password,
                role: this.user.role.value,
                society: this.user.society.id,
                profession: this.user.profession,
                phone: this.user.phone,
            }).then(response => {
                Fire.$emit('CreateUser')
                this.$Progress.finish();
                $('#addNewUser').modal('hide')
                this.$toasted.global.flash({message: "L'utilisateur à été créé."});
                this.errors = []
            }).catch(error => {

                this.errors = error.response.data.errors;
                this.$Progress.fail()

            })
        },
        deleteUser(id) {
            swal({
                title: "Etes vous sur ? ",
                text: "Cette manipulation est irréversible !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.delete('/api/user/' + id).then(() => {
                            swal("L'utilisateur à été supprimé.", {
                                icon: "success",
                            });
                            Fire.$emit('UpdateUser')
                        }).catch(error => {
                            this.errors = error.response.data.message;
                            swal(this.errors);
                        })
                    } else {
                        swal("Ok on annule !");
                    }
                });
        },
        getSociety() {
            axios.get('/api/society').then((society) => {
                this.society = society.data
            })

        },

    },
    created() {
        if (this.$gate.isAdmin()) {
            this.loadLogins()
            Fire.$on('CreateUser', () => {
                this.$Progress.start();
                this.loadUsers()
                this.$Progress.finish();
            })
            Fire.$on('UpdateUser', () => {
                this.$Progress.start();

                this.loadUsers()
                this.$Progress.finish();
            })
        }
    },
}
</script>

