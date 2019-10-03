<template>
    <div>
        <!-- Page content -->
        <div v-if="$gate.isAdmin()">
            <div class="row mb-2 align-items-center">
                <div class="col col-md-3">
                    <v-select class="form-control-alternative"
                              label="name"
                              :options="views"
                              v-model="view"
                              :clearable="false"
                              @input="loadData">
                    </v-select>
                </div>
                <div class="col">
                    <button class="btn btn-primary" @click="showModal">Ajouter une view</button>
                </div>
                <div class="col">
                    <v-select
                        label="label"
                        :options="options"
                        class="form-control-alternative"
                        :clearable="false"
                        v-model="period">
                    </v-select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-xl-3 col-lg-6">
                    <card-stats :data="UserAndReturning.NewUser" title="New Visitor" icon-class="fas fa-user-plus"
                                bgColor="bg-primary" :data-percent="parseFloat(UserAndReturning.PercentNew).toFixed(2)"
                    :period="period"></card-stats>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <card-stats :data="UserAndReturning.ReturningUser" title="Returning visitor" icon-class="fas fa-user-check"
                                bgColor="bg-success" :data-percent="parseFloat(UserAndReturning.PercentReturning).toFixed(2)"
                                :period="period"></card-stats>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <card-stats :data="parseFloat(bounceRate.bounce).toFixed(2)" :percent="true" title="Bounce Rate"
                                icon-class="fas fa-percent" bgColor="bg-info"
                                :data-percent="parseFloat(bounceRate.Percent).toFixed(2)"
                                :period="period"></card-stats>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <card-stats :data="parseFloat(sessionDuration.duration).toFixed(2)" title="Average session time (seconds)"
                                icon-class="fas fa-clock" bgColor="bg-warning"
                                :data-percent="parseFloat(sessionDuration.Percent).toFixed(2)"
                                :period="period"></card-stats>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mb-5 mb-xl-0">
                    <div class="card bg-gradient-default shadow">
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                                    <h2 class="text-white mb-0">CA {{ period.label }} - {{ ca.toFixed(2) }} $</h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Chart -->
                            <div class="chart">
                                <line-chart :chartdata="chartDataCA"
                                            class="chart-canvas"
                                            :width="300"
                                            :height="300"
                                            v-if="loaded"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card shadow">
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                                    <h2 class="mb-0">Visites - {{ period.label }}</h2>
                                    <small class="text-muted"></small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Chart -->
                            <div class="chart">
                                <!--<canvas id="chart-orders" class="chart-canvas"></canvas>-->
                                <bar-chart :chartdata="chartDataVisit"
                                           class="chart-canvas"
                                           id="chart-visit"
                                           :width="300"
                                           :height="300"
                                           v-if="loaded"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-xl-8 mb-5 mb-xl-0">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0">Nbr de visites par pages - {{ period.label }}</h3>
                                </div>
                                <div class="col text-right">
                                    <a href="#!" class="btn btn-sm btn-primary">See all</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <!-- Projects table -->
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Page name</th>
                                    <th scope="col">Visitors</th>
                                    <th scope="col">Average Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="page in pages">
                                    <th scope="row">
                                        {{ page.pageTitle }}
                                    </th>
                                    <td>
                                        {{ page.pageViews }}
                                    </td>
                                    <td>
                                        {{ (parseInt(page.AverageTime) / 60).toFixed(2) }} min
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <progress-state :items="socials" :total="total" :array="['Site', 'Visteurs']"
                                    title="Social Traffic"></progress-state>
                </div>
            </div>

            <div class="modal fade" id="addNewView" tabindex="-1" role="dialog" aria-labelledby="addNewView"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Ajouter une view</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="addView">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group " :class="errors.name ? 'has-danger' : ''">
                                            <input type="text" v-model="NewView.name"
                                                   class="form-control form-control-alternative"
                                                   :class="errors.name ? 'is-invalid' : ''" name="name"
                                                   placeholder="Nom">
                                            <small v-if="errors.name" :class="errors.name ? 'text-danger' : ''"
                                                   v-for="error in errors.name">{{ error }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" :class="errors.url ? 'has-danger' : ''">
                                            <input type="text" v-model="NewView.view"
                                                   class="form-control form-control-alternative" name="url"
                                                   :class="errors.url ? 'is-invalid' : ''"
                                                   placeholder="View ID">
                                            <small v-if="errors.url" :class="errors.url ? 'text-danger' : ''"
                                                   v-for="error in errors.url">{{ error }}
                                            </small>

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Ajouter</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div v-if="$gate.isUser() || $gate.isLeader() ">
            <div class="container">
                <div class="jumbotron ">
                    <h1 class="display-4">Support Softease</h1>
                    <p class="lead">Softease vous présente la nouvelle interface de son support.</p>
                    <hr class="my-4">
                    <p>Si vous rencontré des difficultés ou des améliorations à effectuer, merci de nous envoyer un
                        email via le boutton ci-dessous.</p>
                    <a class="btn btn-primary btn-lg"
                       href="mailto:technique@softease.fr?subject=Support%20Problèmes%20/%20Amélioration" role="button">Contact</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LineChart from './LineChartComponent'
import BarChart from './BarComponent'
import moment from "moment/moment"
import ProgressState from './Components/ProgressStateComponent'
import CardStats from './Components/CardStatsComponent'

moment.locale('fr');
export default {
    name: 'LineChartContainer',
    components: {LineChart, BarChart, ProgressState, CardStats},
    data() {
        return {
            loaded: false,
            options: [
                {label: "Aujourd'hui", value: 1},
                {label: "Hier", value: -1},
                {label: '1 semaine', value: 7},
                {label: '1 mois', value: 30},
            ],
            period: {label: '1 semaine', value: 7},
            analytics: {},
            chartDataVisit: {},
            chartDataCA: {},
            pages: {},
            views: [],
            view: {},
            errors: [],
            NewView: {},
            ca: 0,
            socials: {},
            total: 0,
            UserAndReturning: 0,
            bounceRate: 0,
            sessionDuration: 0,
        }
    },
    methods: {
        showModal() {
            $('#addNewView').modal('show')
        },
        addView() {
            this.$Progress.start()
            axios.post('/api/analytics', {
                ...this.NewView
            }).then(response => {
                console.log(response)
                this.views.push(response.data)
                this.$toasted.global.flash({message: "La vue à été ajoutée"});
                $('#addNewView').modal('hide')
                this.$Progress.finish()

            })
        },
        loadView() {
            axios.get('/api/analytics/getViews').then(response => {
                this.views = response.data
                this.view = response.data[0]
                this.loadData()
            })
        },
        fetchVisits(response) {
            let visit = []
            this.$set(this.chartDataVisit, "labels", [])
            this.$set(this.chartDataVisit, "datasets", [])
            for (var i = 0; i < response.data.visitors.length; i++) {
                this.chartDataVisit.labels.push(response.data.visitors[i].date)
                visit.push(response.data.visitors[i].visitors)
            }
            this.chartDataVisit.datasets.push({
                data: visit,
                label: "# de visites",
                backgroundColor: '#6772E5',
                hoverBackgroundColor: '#6672E5',
            })
        },
        fetchTotalCA(response) {
            this.$set(this.chartDataCA, "labels", [])
            this.$set(this.chartDataCA, "datasets", [])

            var data = []
            var lastdata = []
            var ca = 0

            for (var i = 0; i < response.data.CA.length; i++) {
                this.chartDataCA.labels.push(response.data.CA[i].date)
                data.push(response.data.CA[i].ca)
                lastdata.push(response.data.CA[i].lastCA)
                ca = ca + parseFloat(response.data.CA[i].ca)
            }

            this.ca = ca
            this.chartDataCA.datasets.push({
                data: data,
                label: "# de $",
                borderColor: "#6772E5",
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
            },{
                data: lastdata,
                label: "# de $",
                borderColor: "#a6a9ff",
                pointBorderColor: "transparent",
                pointBackgroundColor: "transparent",
                pointHoverBackgroundColor: "#A6A9FF",
                pointHoverBorderColor: "#A6A9FF",
                pointBorderWidth: 2,
                pointHoverRadius: 5,
                pointHoverBorderWidth: 1,
                pointRadius: 10,
                fill: false,
                borderWidth: 4,
            })
        },
        async loadData() {
            this.$Progress.start()
            this.loaded = false
            try {
                await axios.get('/api/analytics/getData', {
                    params: {
                        view: this.view.view,
                        period: this.period.value
                    }
                }).then(response => {
                    console.log(response)
                    // Graphique CA
                    this.fetchTotalCA(response)

                    // Visites Graphique
                    this.fetchVisits(response)

                    // Tableau des pages visités
                    this.pages = response.data.pages

                    //Social trafique
                    this.socials = response.data.refered
                    for (var i = 0; i < response.data.refered.length; i++) {
                        this.total = this.total + parseInt(response.data.refered[i].count)
                    }

                    // Datas about New User, Returning User bounceRate and sessionDuration
                    this.UserAndReturning = response.data.userVsReturning
                    this.bounceRate = response.data.bounceRate
                    this.sessionDuration = response.data.sessionDuration

                    //affichage des données dans la vue.
                    this.loaded = true
                    this.$Progress.finish()

                })
            } catch (e) {
                console.error(e)
            }
        },
    },
    watch: {
        period: function () {
            this.loadData()
        }
    },
    mounted() {
        this.loadView()
    }
}
</script>
