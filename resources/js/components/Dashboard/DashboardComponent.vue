<template>
    <div>
        <!-- Page content -->
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
                            <line-chart :chartdata="chartData"
                                        class="chart-canvas"
                                        :width="300"
                                        :height="300"
                                        :options="options"
                                        v-if="loaded"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                                <h2 class="mb-0">Total orders</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="chart-orders" class="chart-canvas"></canvas>
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
                            <tr>
                                <th scope="row">
                                    /argon/
                                </th>
                                <td>
                                    4,569
                                </td>
                                <td>
                                    340
                                </td>
                                <td>
                                    <i class="fas fa-arrow-up text-success mr-3"></i> 46,53%
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    /argon/index.html
                                </th>
                                <td>
                                    3,985
                                </td>
                                <td>
                                    319
                                </td>
                                <td>
                                    <i class="fas fa-arrow-down text-warning mr-3"></i> 46,53%
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    /argon/charts.html
                                </th>
                                <td>
                                    3,513
                                </td>
                                <td>
                                    294
                                </td>
                                <td>
                                    <i class="fas fa-arrow-down text-warning mr-3"></i> 36,49%
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    /argon/tables.html
                                </th>
                                <td>
                                    2,050
                                </td>
                                <td>
                                    147
                                </td>
                                <td>
                                    <i class="fas fa-arrow-up text-success mr-3"></i> 50,87%
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    /argon/profile.html
                                </th>
                                <td>
                                    1,795
                                </td>
                                <td>
                                    190
                                </td>
                                <td>
                                    <i class="fas fa-arrow-down text-danger mr-3"></i> 46,53%
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
</template>

<script>
import LineChart from './LineComponent'

export default {
    name: 'LineChartContainer',
    components: {LineChart},
    data() {
        return {
            loaded: false,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                defaultColor: '#8898aa',
                defaultFontColor: '#8898aa',
                defaultFontSize: 13,
                layout: {
                    padding: 0
                },
                legend: {
                    display: false,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 16
                    }
                },
                elements: {
                    point: {
                        radius: 0,
                        backgroundColor: '#5e72e4'
                    },
                    line: {
                        tension: .4,
                        borderWidth: 4,
                        borderColor: '#5e72e4',
                        backgroundColor: 'transparent',
                        borderCapStyle: '#fb6340'
                    },
                    rectangle: {
                        backgroundColor: '#fb6340'
                    },
                    arc: {
                        backgroundColor: '#5e72e4',
                        borderColor: '#8898aa',
                        borderWidth: 4
                    }
                },
                tooltips: {
                    enabled: false,
                    mode: 'index',
                    intersect: false,
                    custom: function (model) {

                        // Get tooltip
                        var $tooltip = $('#chart-tooltip');

                        // Create tooltip on first render
                        if (!$tooltip.length) {
                            $tooltip = $('<div id="chart-tooltip" class="popover bs-popover-top" role="tooltip"></div>');
                            // Append to body
                            $('body').append($tooltip);
                        }

                        // Hide if no tooltip
                        if (model.opacity === 0) {
                            $tooltip.css('display', 'none');
                            return;
                        }

                        function getBody(bodyItem) {
                            return bodyItem.lines;
                        }

                        // Fill with content
                        if (model.body) {
                            var titleLines = model.title || [];
                            var bodyLines = model.body.map(getBody);
                            var html = '';

                            // Add arrow
                            html += '<div class="arrow"></div>';

                            // Add header
                            titleLines.forEach(function (title) {
                                html += '<h3 class="popover-header text-center">' + title + '</h3>';
                            });

                            // Add body
                            bodyLines.forEach(function (body, i) {
                                var colors = model.labelColors[i];
                                var styles = 'background-color: ' + colors.backgroundColor;
                                var indicator = '<span class="badge badge-dot"><i class="bg-primary"></i></span>';
                                var align = (bodyLines.length > 1) ? 'justify-content-left' : 'justify-content-center';
                                html += '<div class="popover-body d-flex align-items-center ' + align + '">' + indicator + body + '</div>';
                            });

                            $tooltip.html(html);
                        }

                        // Get tooltip position
                        var $canvas = $(this._chart.canvas);

                        var canvasWidth = $canvas.outerWidth();
                        var canvasHeight = $canvas.outerHeight();

                        var canvasTop = $canvas.offset().top;
                        var canvasLeft = $canvas.offset().left;

                        var tooltipWidth = $tooltip.outerWidth();
                        var tooltipHeight = $tooltip.outerHeight();

                        var top = canvasTop + model.caretY - tooltipHeight - 16;
                        var left = canvasLeft + model.caretX - tooltipWidth / 2;

                        // Display tooltip
                        $tooltip.css({
                            'top': top + 'px',
                            'left': left + 'px',
                            'display': 'block',
                            'z-index': '100'
                        });

                    },
                    callbacks: {
                        label: function (item, data) {
                            var label = data.datasets[item.datasetIndex].label || '';
                            var yLabel = item.yLabel;
                            var content = '';

                            if (data.datasets.length > 1) {
                                content += '<span class="badge badge-primary mr-auto">' + label + '</span>';
                            }

                            content += '<span class="popover-body-value">' + yLabel + '</span>';
                            return content;
                        }
                    }
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            padding: 20,
                            fontColor: '#dee2e6',
                            fontStyle: "normal"
                        },
                        borderWidth: 0,
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            borderDash: [2],
                            borderDashOffset: [2],
                            color: 'rgba(222, 226, 230, 0.1)',
                            drawBorder: false,
                            drawTicks: false,
                            lineWidth: 0,
                            zeroLineWidth: 0,
                            zeroLineColor: 'rgba(222, 226, 230, 0.1)',
                            zeroLineBorderDash: [2],
                            zeroLineBorderDashOffset: [2]
                        },
                        ticks: {
                            beginAtZero: false,
                            padding: 10,
                            callback: function(value) {
                                if (!(value % 10)) {
                                    return value
                                }
                            }
                        }
                    }]
                }
            },
            chartData: {}
        }
    },
    async mounted() {
        this.loaded = false
        try {
            await axios.get('/api/tickets/stats').then(response => {
                this.$set(this.chartData, "labels", [])
                this.$set(this.chartData, "datasets", [])
                this.chartData.labels.push('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre')
                this.chartData.datasets.push({
                    data: response.data,
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
                this.loaded = true
            })
        } catch (e) {
            console.error(e)
        }
    }
}
</script>
