<script>
import { Bar } from 'vue-chartjs'

export default {
    components: {
        Bar
    },
    extends: Bar,
    data (){
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
                },
                scales: {
                    xAxes: [{
                        barThickness : 25,
                        gridLines : false,
                        ticks: {
                            padding: 20,
                            fontColor: '#32325d',
                            fontStyle: "normal"
                        },
                    }],
                    yAxes: [{
                        gridLines: {
                            borderDash: [2],
                            borderDashOffset: [2],
                            color: 'rgba(222, 226, 230, 0.7)',
                            drawBorder: false,
                            drawTicks: false,
                            lineWidth: 0,
                            zeroLineWidth: 0,
                            zeroLineColor: '#8898aa',
                            zeroLineBorderDash: [2],
                            zeroLineBorderDashOffset: [2]
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },

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
