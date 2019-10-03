<script>
import { Line } from 'vue-chartjs'

export default {
    name: 'LineChart',
    components: {
        Line
    },
    extends: Line,
    data () {
        return {
            options : {
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
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#FFFFFF',
                    titleFontColor: '#172B4D',
                    bodyFontColor: '#172B4D',
                    displayColors: true,
                    // custom: function (model) {
                    //
                    //     // Get tooltip
                    //     var $tooltip = $('#chart-tooltip');
                    //
                    //     // Create tooltip on first render
                    //     if (!$tooltip.length) {
                    //         $tooltip = $('<div id="chart-tooltip" class="popover bs-popover-top" role="tooltip"></div>');
                    //         // Append to body
                    //         $('body').append($tooltip);
                    //     }
                    //
                    //     // Hide if no tooltip
                    //     if (model.opacity === 0) {
                    //         $tooltip.css('display', 'none');
                    //         return;
                    //     }
                    //
                    //     function getBody(bodyItem) {
                    //         return bodyItem.lines;
                    //     }
                    //
                    //     // Fill with content
                    //     if (model.body) {
                    //         var titleLines = model.title || [];
                    //         var bodyLines = model.body.map(getBody);
                    //         var html = '';
                    //
                    //         // Add arrow
                    //         html += '<div class="arrow"></div>';
                    //
                    //         // Add header
                    //         titleLines.forEach(function (title) {
                    //             html += '<h3 class="popover-header text-center">' + title + '</h3>';
                    //         });
                    //
                    //         // Add body
                    //         bodyLines.forEach(function (body, i) {
                    //             var colors = model.labelColors[i];
                    //             var styles = 'background-color: #fff';
                    //             // colors.backgroundColor
                    //             var indicator = '<span class="badge badge-dot"><i class="bg-default"></i></span>';
                    //             var align = (bodyLines.length > 1) ? 'justify-content-left' : 'justify-content-center';
                    //             html += '<div class="popover-body d-flex align-items-center ' + align + '">' + indicator + body + '</div>';
                    //         });
                    //
                    //         $tooltip.html(html);
                    //     }
                    //
                    //     // Get tooltip position
                    //     var $canvas = $(this._chart.canvas);
                    //
                    //     var canvasWidth = $canvas.outerWidth();
                    //     var canvasHeight = $canvas.outerHeight();
                    //
                    //     var canvasTop = $canvas.offset().top;
                    //     var canvasLeft = $canvas.offset().left;
                    //
                    //     var tooltipWidth = $tooltip.outerWidth();
                    //     var tooltipHeight = $tooltip.outerHeight();
                    //
                    //     var top = canvasTop + model.caretY - tooltipHeight - 16;
                    //     var left = canvasLeft + model.caretX - tooltipWidth / 2;
                    //
                    //     // Display tooltip
                    //     $tooltip.css({
                    //         'top': top + 'px',
                    //         'left': left + 'px',
                    //         'display': 'block',
                    //         'z-index': '100'
                    //     });
                    //
                    // },
                    // callbacks: {
                    //     label: function (item, data) {
                    //         var label = data.datasets[item.datasetIndex].label || '';
                    //         var yLabel = item.yLabel;
                    //         var content = '';
                    //
                    //         if (data.datasets.length > 1) {
                    //             content += '<span class="badge badge-primary mr-auto">' + label + '</span>';
                    //         }
                    //
                    //         content += '<span class="popover-body-value">' + yLabel + '</span>';
                    //         return content;
                    //     }
                    // }
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
            }
        }
    },
    props: {
        chartdata: {
            type: Object,
            default: null
        },
    },
    mounted () {
        this.renderChart(this.chartdata,this.options)
    }
}
</script>
