
let mixin_store = await import('./mixin-store.js');
let mixins = [ mixin_store.default ];


// KO
// https://www.chartjs.org/docs/latest/getting-started/integration.html
// const { Chart } = await import('../chart/chart.js');

let template = `
<div class="compo">
    <h3>Chart</h3>
    <canvas id="myChart" ref="myChart"></canvas>
</div>
`

export default {
    template,
    mixins,
    props: {
        chart_type: {
            type: String,
            default: 'bar'
        }
    },
    created() {
        console.log(this.chart_type);
    },
    mounted() {
        // path relative to web page
        let script_js = [{
            'name': 'chart',
            'url': 'assets/chart/chart.min.js'
        }]
    
        this.load_js_order(script_js, 0, this.compo_init)
    },
    methods: {
        compo_init: function() {
            
            // FIXME: hack to wait for Chart to be defined
            // if Chart is not defined, it means that the script is not loaded
            // then we wait 100ms and try again
            if (typeof Chart === 'undefined') {
                setTimeout(this.compo_init, 100);
                return;
            }

            const ctx = this.$refs.myChart; // document.getElementById('myChart');

            new Chart(ctx, {
                type: this.chart_type,
                data: {
                    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                    datasets: [{
                        label: '# of Votes',
                        data: [12, 19, 3, 5, 2, 3],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }
}