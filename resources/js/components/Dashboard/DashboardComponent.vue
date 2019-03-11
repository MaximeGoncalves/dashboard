<template>
    <div>
        <!-- Page content -->
        <div v-if="$gate.isAdmin()">
            <div class="row">

            </div>
            <div class="row">
                <div class="col-xl-8 mb-5 mb-xl-0">
                    <div class="card bg-gradient-default shadow">
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                                    <h2 class="text-white mb-0">Nbr de Tickets</h2>
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
                                <line-chart :chartdata="chartDataTickets"
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
                                    <h2 class="mb-0">Tickets - 5 derniers jours</h2>
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
                                <bar-chart :chartdata="chartDataLastFiveDays"
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
                                    <h3 class="mb-0">Ticket Pending</h3>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <!-- Projects table -->
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Topic</th>
                                    <th scope="col">Utilisateur</th>
                                    <th scope="col">Priorité</th>
                                    <th scope="col">editer</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="ticket in tickets">
                                    <th scope="row">
                                        {{ ticket.topic}}
                                    </th>
                                    <td>
                                        {{ ticket.user.name }}
                                    </td>
                                    <td>
                                        {{ ticket.importance }}
                                    </td>
                                    <td>
                                        <router-link :to="{name: 'ticket', params: {id: ticket.id }}">
                                            <i class="fas fa-edit"></i>
                                        </router-link>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <progress-state :items="ticketPerSociety"
                                    title="Tickets par société"
                                    :array="['Société', 'Total']"
                                    :total="total"></progress-state>
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
import ProgressState from './Components/ProgressStateComponent'

moment.locale('fr');
export default {
    name: 'LineChartContainer',
    components: {LineChart, BarChart, ProgressState},
    data() {
        return {
            loaded: false,
            chartDataTickets: {},
            chartDataLastFiveDays: {},
            tickets: {},
            ticketPerSociety: {},
            total: 0
        }
    },
    methods: {
        width() {
            return 'width:' + (100 * society / this.total).toFixed(2);
        },
        statsInit() {
            this.$set(this.chartDataTickets, "labels", [])
            this.$set(this.chartDataTickets, "datasets", [])
            this.$set(this.chartDataLastFiveDays, "labels", [])
            this.$set(this.chartDataLastFiveDays, "datasets", [])
        },
        async loadData() {
            this.$Progress.start()
            this.loaded = false
            try {
                await axios.get('/api/tickets/stats').then(response => {
                    console.log(response)
                    this.statsInit()
                    // Ticket sur l'année
                    var last = [0, 0, 0, 0]
                    for (var i = 0; i < response.data.lastYear.length; i++) {
                        last.push(response.data.lastYear[i])
                    }

                    this.chartDataTickets.labels.push('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre')
                    this.chartDataTickets.datasets.push({
                        data: response.data.ticket,
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
                    }, {
                        data: last,
                        label: "# de tickets (N-1)",
                        borderColor: "rgba(222, 226, 230, 0.3)",
                        pointBorderColor: "transparent",
                        pointBackgroundColor: "transparent",
                        pointHoverBackgroundColor: "rgba(222, 226, 230, 0.3)",
                        pointHoverBorderColor: 'rgba(222, 226, 230, 0.3)',
                        pointBorderWidth: 2,
                        pointHoverRadius: 5,
                        pointHoverBorderWidth: 1,
                        pointRadius: 10,
                        fill: false,
                        borderWidth: 1,
                    })

                    // Ticket sur les 5 derniers jours
                    this.chartDataLastFiveDays.labels = Object.keys(response.data.lastFive)
                    var created = []
                    var closed = []
                    for (var i = 0; i < response.data.createdCount.length; i++) {
                        created.push(response.data.createdCount[i])
                        closed.push(response.data.closedCount[i])
                    }
                    this.chartDataLastFiveDays.datasets.push({
                        data: created,
                        label: "# de tickets",
                        backgroundColor: '#6772E5',
                        hoverBackgroundColor: '#6672E5',
                    }, {
                        data: closed,
                        label: "# de tickets clos",
                        backgroundColor: '#19194D',
                        hoverBackgroundColor: '#19194D',
                    })

                    // Ticket status pending
                    this.tickets = response.data.pending

                    this.ticketPerSociety = response.data.ticketSociety

                    for (var i = 0; i < response.data.ticketSociety.length; i++) {

                        this.total = this.total + response.data.ticketSociety[i].count
                    }
                    this.$Progress.finish()

                    this.loaded = true

                })
            } catch (e) {
                console.error(e)
            }
        },
    },
    mounted() {
        this.loadData()
    }
}
</script>
