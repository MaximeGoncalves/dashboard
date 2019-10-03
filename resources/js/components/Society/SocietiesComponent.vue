<template>
    <div>
        <div class="row justify-content-between">
            <div class="col-12 col-md-4">
                <a href="#"
                   class="btn btn-default" @click="NewModal">Ajouter une société</a>
            </div>
            <div class="col-4"></div>
            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <div class="form-group mb-0">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend"><span class="input-group-text"><i
                            class="fas fa-search"></i></span></div>
                        <input placeholder="Search" type="text" class="form-control"
                               @input="search" v-model="searchData"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-md-6 col-12 mt-2" v-for="society in societies">
                <card-society :society="society"></card-society>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNewSociety" tabindex="-1" role="dialog" aria-labelledby="addNewSociety"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ editMode ? "Modification de : " +
                            society.name:
                            'Nouvelle Société'}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="editMode ? UpdateSociety(society.id)  : create()">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group " :class="errors.name ? 'has-danger' : ''">
                                        <input type="text" v-model="society.name"
                                               class="form-control form-control-alternative"
                                               :class="errors.name ? 'is-invalid' : ''" name="name"
                                               placeholder="Nom de la société">
                                        <small v-if="errors.name" :class="errors.name ? 'text-danger' : ''"
                                               v-for="error in errors.name">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group " :class="errors.address ? 'has-danger' : ''">
                                        <input type="text" v-model="society.address"
                                               class="form-control form-control-alternative"
                                               :class="errors.address ? 'is-invalid' : ''" name="address"
                                               placeholder="Adresse de la société">
                                        <small v-if="errors.address" :class="errors.address ? 'text-danger' : ''"
                                               v-for="error in errors.address">{{ error }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.city ? 'has-danger' : ''">
                                        <input type="text" v-model="society.city"
                                               class="form-control form-control-alternative" name="city"
                                               :class="errors.city ? 'is-invalid' : ''"
                                               placeholder="Ville">
                                        <small v-if="errors.city" :class="errors.city ? 'text-danger' : ''"
                                               v-for="error in errors.city">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.cp ? 'has-danger' : ''">
                                        <input type="text" v-model="society.cp"
                                               class="form-control form-control-alternative" name="cp"
                                               :class="errors.email ? 'is-invalid' : ''"
                                               placeholder="Code postale">
                                        <small v-if="errors.cp" :class="errors.cp ? 'text-danger' : ''"
                                               v-for="error in errors.cp">{{ error }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.email ? 'has-danger' : ''">
                                        <input type="email" v-model="society.email"
                                               class="form-control form-control-alternative" name="email"
                                               :class="errors.email ? 'is-invalid' : ''"
                                               placeholder="E-mail">
                                        <small v-if="errors.email" :class="errors.email ? 'text-danger' : ''"
                                               v-for="error in errors.email">{{ error }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" :class="errors.phone ? 'has-danger' : ''">
                                        <input type="text" v-model="society.phone"
                                               class="form-control form-control-alternative" name="phone"
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
                                    <div class="form-group" :class="errors.fax ? 'has-danger' : ''">
                                        <input type="text" v-model="society.fax"
                                               class="form-control form-control-alternative" name="fax"
                                               :class="errors.fax ? 'is-invalid' : ''"
                                               placeholder="Fax">
                                        <small v-if="errors.fax" :class="errors.fax ? 'text-danger' : ''"
                                               v-for="error in errors.fax">{{ error }}
                                        </small>

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-default">{{ editMode ? 'Sauvegarder' : 'Créer'}}
                                </button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CardSociety from './CardSocietyComponent'
import store from '../Store/Store'
import {mapActions, mapState} from 'vuex'

export default {
    store: store,
    components: {CardSociety},
    data() {
        return {
            society: {},
            errors: [],
            // societies: {},
            editMode: true,
            selected: "",
            searchData: ''
        }
    },
    computed: {
        ...mapState({
            societies: state => state.society.societies
        })
    },
    methods: {
        ...mapActions({
            addSociety: 'society/ADD_SOCIETY',
            getSocieties: 'society/GET_SOCIETIES'
        }),

        NewModal() {
            this.editMode = false
            this.errors = []
            this.society.name = ''
            this.society.address = ''
            this.society.city = ''
            this.society.cp = ''
            this.society.email = ''
            this.society.phone = ''
            this.society.fax = ''
            $('#addNewSociety').modal('show')
        },
        create() {
            this.addSociety(this.society)
        },
        // loadSocieties() {
        //     axios.get('/api/society').then((data) => {
        //         this.societies = data.data
        //     });
        // },
        search() {
            axios.get('/api/society/search', {
                params: {
                    search: this.searchData
                }
            }).then(response => {
                this.$store.commit('society/GET_SOCIETIES',response.data )
            })
        },
    },
    created() {
        if (window.user.role === "user") {
            this.$router.push({path: '/'})
        }
        this.getSocieties()
        // Fire.$on('createSociety', () => {
        //     this.loadSocieties()
        // })
        // Fire.$on('UpdateSociety', () => {
        //     this.loadSocieties()
        // })
    },
}
</script>

