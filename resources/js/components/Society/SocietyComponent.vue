<template>
    <div v-if="society">
        <div class="row">
            <div class="col">
                <button @click="NewModal" class="btn btn-default mt-1">Ajouter un utilisateur</button>

            </div>
        </div>
        <div class="row mt-2">
            <div class="col-4">
                <div class="card h-100">
                    <input-component :society="society"></input-component>
                </div>
            </div>
            <div class="col-8">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-light ls-1 mb-1">Tickets</h6>
                                <h2 class="text-white mb-0">Année en cours</h2>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales"
                                        data-update='{"data":{"datasets":[{"data":[0, 100, 10, 30, 15, 40, 20, 60, 60]}]}}'
                                        data-prefix="$" data-suffix="k">
                                    </li>
                                    <li class="nav-item" data-toggle="chart" data-target="#chart-sales"
                                        data-update='{"data":{"datasets":[{"data":[0, 1000, 5, 25, 10, 30, 15, 40, 40]}]}}'
                                        data-prefix="$" data-suffix="k">
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!--<canvas id="chart-sales" class="chart-canvas"></canvas>-->
                            <line-chart :chartdata="chartDataTickets"
                                        class="chart-canvas"
                                        :width="300"
                                        :height="300" v-if="loaded"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <user-component v-if="society" :users="users.data" :society="society.id"></user-component>
        </div>
<!--        <modal-user :user="user" :editMode="editMode" :society="society.id"></modal-user>-->
    </div>
</template>
<script>
import LineChart from '../Dashboard/LineChartComponent'
import {mapActions, mapState} from 'vuex'
import store from '../Store/Store'
import UserComponent from '../Users/UserSocietyComponent'
import InputComponent from './SocietyInputComponent'
import ModalUser from '../Users/UserModalComponent'

export default {
    name: 'Society',
    store: store,

    data() {
        return {
            chartDataTickets: {},
            loaded: false,
            user : {},
            editMode: true,
            society: {},
            tickets: {}
        }
    },
    components: {UserComponent, LineChart, InputComponent, ModalUser},

    computed: {
        ...mapState({
            users: state => state.user.users,
            errors: state => state.errors,
        }),

    },
    methods: {
        ...mapActions({
            addUserStore: 'user/ADD_USER',
            deleteUser: 'user/DELETE_USER',
            search: 'user/SEARCH'
        }),
        NewModal() {
            this.editMode = false
            this.errors = []
            this.user.name = ''
            this.user.fullname = ''
            this.user.profession = ''
            this.user.phone = ''
            this.user.email = ''
            this.user.password = ''
            this.user.role = ''
            $('#addNewUser').modal('show')
        },
        initChart() {
            this.$set(this.chartDataTickets, "labels", [])
            this.$set(this.chartDataTickets, "datasets", [])
            this.chartDataTickets.labels.push('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre')
            this.chartDataTickets.datasets.push({
                data: this.tickets,
                label: "# de tickets",
                borderColor: "#6772E5",
                backgroundColor: "#6772E5",
                pointBorderColor: "transparent",
                pointBackgroundColor: "transparent",
                pointHoverBackgroundColor: "#6772E5",
                pointHoverBorderColor: "#6772E5",
                pointBorderWidth: 2,
                pointHoverRadius: 5,
                pointHoverBorderWidth: 1,
                pointRadius: 10,
                fill: false,
                borderWidth: 4,
            })

            this.loaded = true
        }
    },
    created() {
        this.$store.dispatch('user/GET_USERS', this.$route.params.id)
        axios.get('/api/society/' + this.$route.params.id).then(response => {
            this.society = response.data.society
            this.tickets = response.data.tickets
            this.initChart()
        })
    },
}
</script>
