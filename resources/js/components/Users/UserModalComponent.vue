<template>
    <div class="modal fade" id="addNewUser" tabindex="-1" role="dialog" aria-labelledby="addNewUser"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ editMode ? "Modification de : " +
                        user.fullname:
                        'Nouvelle utilisateur'}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="editMode ? UpdateUser(user.id)  : createUser()">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group " :class="errors.name ? 'has-danger' : ''">
                                    <input type="text" v-model="user.name"
                                           class="form-control form-control-alternative"
                                           :class="errors.name ? 'is-invalid' : ''" name="name"
                                           placeholder="Identifiant de connexion">
                                    <small v-if="errors.name" :class="errors.name ? 'text-danger' : ''"
                                           v-for="error in errors.name">{{ error }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.fullname ? 'has-danger' : ''">
                                    <input type="text" v-model="user.fullname"
                                           class="form-control form-control-alternative" name="fullname"
                                           :class="errors.fullname ? 'is-invalid' : ''"
                                           placeholder="Nom Complet">
                                    <small v-if="errors.fullname" :class="errors.fullname ? 'text-danger' : ''"
                                           v-for="error in errors.fullname">{{ error }}
                                    </small>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.email ? 'has-danger' : ''">
                                    <input type="email" v-model="user.email"
                                           class="form-control form-control-alternative" name="email"
                                           :class="errors.email ? 'is-invalid' : ''"
                                           placeholder="Email">
                                    <small v-if="errors.email" :class="errors.email ? 'text-danger' : ''"
                                           v-for="error in errors.email">{{ error }}
                                    </small>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.password ? 'has-danger' : ''">
                                    <input type="password" v-model="user.password"
                                           class="form-control form-control-alternative" name="password"
                                           :class="errors.password ? 'is-invalid' : ''"
                                           placeholder="Mot de passe" v-if="!editMode">
                                    <input type="password" v-model="user.password"
                                           class="form-control form-control-alternative" name="password"
                                           :class="errors.password ? 'is-invalid' : ''"
                                           placeholder="Mot de passe" v-if="editMode" disabled>
                                    <small v-if="errors.password" :class="errors.password ? 'text-danger' : ''"
                                           v-for="error in errors.password">{{ error }}
                                    </small>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.profession ? 'has-danger' : ''">
                                    <input name="profession" id="profession"
                                           v-model="user.profession"
                                           class="form-control form-control-alternative"
                                           :class="errors.profession ? 'is-invalid' : ''"
                                           placeholder="Profession">
                                    <small v-if="errors.profession"
                                           :class="errors.profession ? 'text-danger' : ''"
                                           v-for="error in errors.profession"
                                    >{{ error }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.phone ? 'has-danger' : ''">
                                    <input name="phone" id="phone"
                                           v-model="user.phone"
                                           class="form-control form-control-alternative"
                                           :class="errors.phone ? 'is-invalid' : ''"
                                           placeholder="Téléphone">
                                    <small v-if="errors.phone" :class="errors.phone ? 'text-danger' : ''"
                                           v-for="error in errors.phone">{{ error }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.role ? 'has-danger' : ''">
                                    <v-select
                                        :options="['user',
                                                  'leader','admin']"
                                        v-model="user.role"
                                        class="form-control-alternative"
                                        placeholder="Rôle"></v-select>
                                    <small v-if="errors.role" :class="errors.role ? 'text-danger' : ''"
                                           v-for="error in errors.role">{{
                                        error }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">{{ editMode ? 'Sauvegarder' :
                                'Créer'}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import store from '../Store/Store'
import{mapActions,mapState} from 'vuex'
export default {
    name:'modal',
    store: store,
    props: ['user','editMode', 'society'],
    data (){
        return {
            newUser: {}
        }
    },
    computed: {
        ...mapState({
            errors: state => state.errors,
        }),
    },
    methods: {
        // ...mapActions({
        //     addUserStore: 'user/ADD_USER',
        // }),
        createUser() {
            // this.addUserStore({user : this.user, society : this.society})
            let payload = {user : this.user, society: this.society}
            this.$store.dispatch('user/ADD_USER', payload)
        },
        UpdateUser() {
            this.$store.dispatch('user/UPDATE_USER', this.user)
        },
        // async EditUser(user) {
        //     this.editMode = true
        //     let getUser = await axios.get('/api/user/' + user).then((user) => {
        //         this.user = user.data
        //     })
        //     this.selected = this.user.society_id
        //     $('#addNewUser').modal('show')
        // },
    },

}

</script>
