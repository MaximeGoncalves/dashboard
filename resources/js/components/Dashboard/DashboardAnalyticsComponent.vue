<template>
    <div>
        <!-- Page content -->
        <div v-if="$gate.isAdmin()">
            <div class="row">
                <v-select class="form-control-alternative"
                label="name"
                :options="views"
                v-model="view"
                @input="loadData">
                </v-select>
            </div>
            <div class="row">
                <div class="col-xl-8 mb-5 mb-xl-0">
                    <div class="card bg-gradient-default shadow">
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                                    <h2 class="text-white mb-0">CA 30 derniers jours</h2>
                                </div>
                                <div class="col">
                                    <ul class="nav nav-pills justify-content-end">
                                        <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales"
                                            data-update='{"data":{"datasets":[{"data":[0, 100, 10, 30, 15, 40, 20, 60, 60]}]}}'
                                            data-prefix="$" data-suffix="k">
                                            <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                                <span class="d-none d-md-block">Month</span>
                                                <span class="d-md-none">M</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" data-toggle="chart" data-target="#chart-sales"
                                            data-update='{"data":{"datasets":[{"data":[0, 1000, 5, 25, 10, 30, 15, 40, 40]}]}}'
                                            data-prefix="$" data-suffix="k">
                                            <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                                <span class="d-none d-md-block">Week</span>
                                                <span class="d-md-none">W</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Chart -->
                            <div class="chart">
                                <!--<canvas id="chart-sales" class="chart-canvas"></canvas>-->
                                <line-chart :chartdata="chartDataCA"
                                            class="chart-canvas"
                                            :width="300"
                                            :height="300"
                                            v-if="loaded"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card shadow">
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                                    <h2 class="mb-0">Visites - 5 derniers jours</h2>
                                    <small class="text-muted"></small>
                                </div>
                                <div class="col">
                                    <ul class="nav nav-pills justify-content-end">
                                        <li class="nav-item mr-2 mr-md-0" data-toggle="chart"
                                            data-target="#chart-visit">
                                            <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                                <span class="d-none d-md-block">Month</span>
                                                <span class="d-md-none">M</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" data-toggle="chart"
                                            data-target="#chart-visit">
                                            <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                                <span class="d-none d-md-block">Week</span>
                                                <span class="d-md-none">W</span>
                                            </a>
                                        </li>
                                    </ul>
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
                                    <h3 class="mb-0">Page visits</h3>
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
                                    <th scope="col">Unique users</th>
                                    <th scope="col">Bounce rate</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="page in pages">
                                    <th scope="row">
                                        {{ page.url}}
                                    </th>
                                    <td>
                                        {{ page.pageViews }}
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-up text-success mr-3"></i> 46,53%
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0">Social traffic</h3>
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
                                    <th scope="col">Referral</th>
                                    <th scope="col">Visitors</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">
                                        Facebook
                                    </th>
                                    <td>
                                        1,480
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">60%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-danger" role="progressbar"
                                                         aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                                         style="width: 60%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Facebook
                                    </th>
                                    <td>
                                        5,480
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">70%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-success" role="progressbar"
                                                         aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"
                                                         style="width: 70%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Google
                                    </th>
                                    <td>
                                        4,807
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">80%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-primary" role="progressbar"
                                                         aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"
                                                         style="width: 80%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Instagram
                                    </th>
                                    <td>
                                        3,678
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">75%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-info" role="progressbar"
                                                         aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                                         style="width: 75%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        twitter
                                    </th>
                                    <td>
                                        2,645
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">30%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-warning" role="progressbar"
                                                         aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"
                                                         style="width: 30%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
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
import LineChart from './LineComponent'
import BarChart from './BarComponent'
import moment from "moment/moment";

moment.locale('fr');
export default {
    name: 'LineChartContainer',
    components: {LineChart, BarChart},
    data() {
        return {
            loaded: false,
            analytics: {},
            chartDataVisit: {},
            chartDataCA: {},
            pages : {},
            views : [],
            view: {}
        }
    },
    methods :{
        loadView (){
            axios.get('/api/analytics/getViews').then(response => {
                this.views = response.data
                this.view = response.data[0]
                this.loadData()
            })
        },
        async loadData(){
            this.$Progress.start()
            this.loaded = false
            try {
                await axios.get('/api/analytics/getData', {
                    params: {
                        view : this.view.view
                    }
                }).then(response => {
                    console.log(response)
                    // Graphique CA
                    this.$set(this.chartDataCA, "labels", [])
                    this.$set(this.chartDataCA, "datasets", [])

                    var data = []
                    for(var i = 0 ; i < response.data.CA.length; i++){
                        this.chartDataCA.labels.push(moment(response.data.CA[i].date).format('DD'))
                        data.push(response.data.CA[i].ca)
                    }
                    this.chartDataCA.datasets.push({
                        data: data,
                        label: "# de tickets",
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
                    })

                    // Visites Graphique
                    let visit = []
                    this.$set(this.chartDataVisit, "labels", [])
                    this.$set(this.chartDataVisit, "datasets", [])
                    for(var i = 0 ; i < response.data.visitors.length; i++){
                        this.chartDataVisit.labels.push(moment(response.data.visitors[i].date.date).format('DD'))
                        visit.push(response.data.visitors[i].visitors)
                    }
                    this.chartDataVisit.datasets.push({
                        data: visit,
                        label: "# de visites",
                        backgroundColor: '#6772E5',
                        hoverBackgroundColor: '#6672E5',
                    })

                    // Tableau des pages visités
                    this.pages = response.data.pages

                    //affichage des données dans la vue.
                    this.loaded = true
                    this.$Progress.finish()

                })
            } catch (e) {
                console.error(e)
            }
        },
    },
    mounted() {
        this.loadView()
    }
}
</script>
