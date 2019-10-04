<template>
    <div>
        <!-- Page content -->
        <div v-if="$gate.isAdmin()">
            <div class="row">
                <div class="col-xl-8 mb-5 mb-xl-0">
                    <div class="card bg-gradient-default shadow">
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-uppercase text-light ls-1 mb-1">Tickets</h6>
                                    <h2 class="text-white mb-0">Année en cours / Année précédente</h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-body tab-content">
                            <!-- Chart -->
                            <div class="tab-pane fade show active" id="chart">
                                <line-chart :chartdata="chartDataTickets"
                                            class="chart-canvas"
                                            :width="300"
                                            :height="300"
                                            v-if="loaded"/>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-4 ">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0">Ticket en attente</h3>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" v-if="tickets.data">
                            <!-- Projects table -->
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Topic</th>
                                    <th scope="col">Utilisateur</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr v-for="ticket in tickets.data">
                                    <th scope="row">
                                        <router-link :to="{name: 'ticket', params: {id: ticket.id }}" class="text-default">
                                            {{ ticket.topic }}
                                        </router-link>
                                    </th>
                                    <td>
                                        {{ ticket.user.name }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else>
                            <h2 class="h2">Aucun ticket en attente</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-xl-4 mb-5 mb-xl-0">
                    <div class="card shadow">
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <h6 class="text-uppercase text-muted ls-1 mb-1">Tickets</h6>
                                    <h2 class="mb-0">Par sources - {{year}}</h2>
                                    <small class="text-muted"></small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Chart -->
                            <div class="chart">
                                <!--<canvas id="chart-orders" class="chart-canvas"></canvas>-->
                                <bar-chart :chartdata="chartPerSource"
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
                                    <h6 class="text-uppercase text-muted ls-1 mb-1">Tickets</h6>
                                    <h2 class="mb-0">Par catégories - {{year}}</h2>
                                    <small class="text-muted"></small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Chart -->
                            <div class="chart">
                                <!--<canvas id="chart-orders" class="chart-canvas"></canvas>-->
                                <bar-chart :chartdata="chartPerCategory"
                                           class="chart-canvas"
                                           id="chart-visit"
                                           :width="300"
                                           :height="300"
                                           v-if="loaded"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <progress-state :items="ticketPerSociety"
                                    :title="'Tickets par société -' + year "
                                    :array="['Société', 'Total']"
                                    :total="total"></progress-state>
                </div>
            </div>
        </div>
        <div v-if="$gate.isUser() || $gate.isLeader() ">
            <div class="row">
                <div class="col-xl-8 mb-5 mb-xl-0">
                    <div class="card bg-gradient-default shadow">
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-uppercase text-light ls-1 mb-1">Tickets</h6>
                                    <h2 class="text-white mb-0">Année en cours / Année précédente</h2>
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
                                            :height="300"
                                            v-if="loaded"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0">Tickets ouverts</h3>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <!-- Projects table -->
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Topic</th>
                                    <th scope="col">Etat</th>
                                    <th scope="col">Voir</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="ticket in tickets.data">
                                    <td style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                        {{ ticket.topic }}
                                    </td>
                                    <td>
                                        {{ ticket.state.name }}
                                    </td>
                                    <td>
                                        <router-link :to="{name: 'ticket', params: {id: ticket.id }}">
                                            <i class="fas fa-eye text-default" style="font-size: 16px;"></i>
                                        </router-link>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center pb-2">
                                <router-link :to="{name: 'tickets'}">
                                    <button class="btn btn-secondary " v-if="tickets.next_page_url">Voir tous les
                                        tickets
                                    </button>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2" v-if="$gate.isLeader()">
                <div class="col-xl-4">
                    <progress-state :items="ticketPerSociety"
                                    title="Tickets par utilisateur"
                                    :array="['Utilisateurs', 'Total']"
                                    :total="total"></progress-state>
                </div>
            </div>
        </div>
        <footer class="footer">

        </footer>
    </div>
</template>

<script>
import LineChart from './LineChartComponent'
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
            chartDataTicketsPerPerson: {},
            chartPerCategory: {},
            tickets: {},
            ticketPerSociety: {},
            chartPerSource: {},
            total: 0
        }
    },
    computed: {
        year() {
            return moment().format('YYYY')
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
            this.$set(this.chartPerCategory, "labels", [])
            this.$set(this.chartPerCategory, "datasets", [])
            this.$set(this.chartPerSource, "labels", [])
            this.$set(this.chartPerSource, "datasets", [])

        },
        async loadData() {
            this.$Progress.start()
            this.loaded = false
            try {
                await axios.get('/api/tickets/stats').then(response => {
                    console.log(response)
                    this.statsInit()
                    // Ticket sur l'année N & N-1
                    this.chartDataTickets.labels.push('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre')
                    this.chartDataTickets.datasets.push({
                        data: response.data.ticket,
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
                    }, {
                        data: response.data.lastYear,
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

                    //ticket par source
                    let sourceDataset = []
                    for (let i = 0; i < response.data.sources.length; i++) {
                        this.chartPerSource.labels.push(response.data.sources[i].name)
                        sourceDataset.push(response.data.sources[i].count)
                    }
                    this.chartPerSource.datasets.push({
                        data: sourceDataset,
                        label: "# Nombre de ticket",
                        backgroundColor: '#19194D',
                        hoverBackgroundColor: '#19194D',
                    })

                    // Ticket par category
                    let labelCat = [];
                    let CountCat = [];
                    for (let i = 0; i < response.data.ticketCategory.length; i++) {
                        labelCat.push(response.data.ticketCategory[i].name)
                        CountCat.push(response.data.ticketCategory[i].count)
                    }
                    this.chartPerCategory.labels = labelCat
                    this.chartPerCategory.datasets.push({
                        data: CountCat,
                        label: "# de tickets clos",
                        backgroundColor: '#19194D',
                        hoverBackgroundColor: '#19194D',
                    })

                    // Ticket status pending
                    this.tickets = response.data.pending

                    // Ticket par société.
                    this.ticketPerSociety = response.data.ticketSociety
                    this.total = response.data.totalTicket
                    // for (var i = 0; i < response.data.ticketSociety.length; i++) {
                    //     this.total = this.total + response.data.ticketSociety[i].count
                    // }
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
