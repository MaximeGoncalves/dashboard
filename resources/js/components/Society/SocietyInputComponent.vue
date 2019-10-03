<template>
    <div>
        <div v-if="edit">
            <div class="card-body">
                <h2 class="h2">{{ society.name }}</h2>
                <div class="row mt-1">
                    <div class="col">
                        <h4>Adresse</h4>
                        <input type="text" v-model="society.address" class="form-control">
                        <small v-if="errors.address" :class="errors.address ? 'text-danger' : ''"
                               v-for="error in errors.address">{{ error }}
                        </small>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <h4>Ville</h4>
                        <input type="text" v-model="society.city" class="form-control">
                        <small v-if="errors.city" :class="errors.city ? 'text-danger' : ''"
                               v-for="error in errors.city">{{ error }}
                        </small>
                    </div>
                    <div class="col">
                        <h4>Code postal</h4>
                        <input type="text" v-model="society.cp" class="form-control">
                        <small v-if="errors.cp" :class="errors.cp ? 'text-danger' : ''"
                               v-for="error in errors.cp">{{ error }}
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h4>Téléphone</h4>
                        <input type="text" v-model="society.phone" class="form-control">
                        <small v-if="errors.phone" :class="errors.phone ? 'text-danger' : ''"
                               v-for="error in errors.phone">{{ error }}
                        </small>
                    </div>
                    <div class="col">
                        <h4>FAX</h4>
                        <input type="text" v-model="society.fax" class="form-control">
                        <small v-if="errors.fax" :class="errors.fax ? 'text-danger' : ''"
                               v-for="error in errors.fax">{{ error }}
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h4>E-mail</h4>
                        <input type="email" v-model="society.email" class="form-control">
                        <small v-if="errors.email" :class="errors.email ? 'text-danger' : ''"
                               v-for="error in errors.email">{{ error }}
                        </small>
                    </div>
                </div>
                <button class="btn btn-success float-right mt-1" @click="update()">Enregistrer</button>
            </div>
        </div>
        <div v-if="!edit">
            <div class="card-body">
                <h2 class="h2 d-inline">{{ society.name }}</h2>
                <a href="#" class="text-default float-right" @click="edit = true">Editer</a>
                <div class="row">
                    <div class="col">
                        <h4>Adresse</h4>
                        <p>{{society.address}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h4>Ville</h4>
                        <p>{{society.city}}</p>

                    </div>
                    <div class="col">
                        <h4>Code postal</h4>
                        <p>{{society.cp}}</p>

                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h4>Téléphone</h4>
                        <p>{{society.phone}}</p>

                    </div>
                    <div class="col">
                        <h4>FAX</h4>
                        <p>{{society.fax}}</p>

                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h4>E-mail</h4>
                        <p>{{society.email}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import store from '../Store/Store'
import {mapActions, mapState} from 'vuex'

export default {
    store: store,
    props: ['society'],
    data() {
        return {
            edit: false,
            SocietyInput: {}
        }
    },
    methods: {
        ...mapActions({
            updateStore: 'society/UPDATE_SOCIETY'
        }),
        update() {
            this.updateStore(this.society).then(() => {
                this.edit = false
            })
        }
    },
    computed: {
        ...mapState({
            errors: state => state.errors,
        })
    },
    created() {
        this.inputSociety = this.society
    }

}
</script>
