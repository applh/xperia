
let mixin_store = await import('./mixin-store.js');
let mixins = [mixin_store.default];


let template = `
<div class="compo">
    <div :class="class_v" ref="grid">
        <div class="uk-card uk-card-default uk-card-body uk-width-1-2">
            <h1>Dashboard</h1>
            <em>Welcome {{ login_user }}</em>
            <p>
                Sortable:
                <input type="checkbox" v-model="option_sortable" @click="act_toggle_sortable()" />
            </p>
            <p>
                <input type="text" v-model="input_label" />
                <input type="number" v-model="input_data" />

                <button class="uk-button" @click.prevent="act_update_chart_data">update chart data</button>
                <button class="uk-button" @click.prevent="act_remove_chart_data">remove chart data</button>
            </p>
        </div>
        <div class="uk-width-1-2@m">
            <o-chart chart_type='doughnut' />
        </div>
        <div class="uk-width-1-2@m">
            <o-map />
        </div>
        <div class="uk-width-1-2">
            <o-scene />
        </div>
        <div class="uk-width-1-2">
            <o-test />
        </div>
        <div class="uk-width-1-2">
            <o-test />
        </div>
        <div v-for="p in 16" class="uk-card uk-card-default uk-width-1-2@s uk-width-1-3@m uk-width-1-4@l">
            <div class="uk-grid-small uk-flex-middle" uk-grid>
                <img class="uk-border-circle" width="40" height="40" src="assets/images/avatar.jpg" alt="Avatar">
                <h3 class="uk-card-title">Item {{ p }}</h3>
            </div>
            <div class="uk-card-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
                <a href="#offcanvas-usage" uk-toggle class="uk-button uk-button-text">Read more</a>
            </div>
        </div>
        <div class="uk-width-1-4@m">
            <o-chart chart_type='bar' />
        </div>
        <div class="uk-width-1-4@m">
            <o-chart chart_type='radar' />
        </div>
        <div class="uk-width-1-4@m">
            <o-chart chart_type='line' />
        </div>
        <div class="uk-width-1-4@m">
            <o-chart chart_type='polarArea' />
        </div>

    </div>
</div>
`

let data_compo = {
    option_sortable: false,
    class_v: {
        'uk-flex-center': true,
        'uk-grid': true,
    },
}

export default {
    template,
    mixins,
    data: () => data_compo,
    methods: {
        act_toggle_sortable() {
            this.option_sortable = !this.option_sortable;
            if (this.option_sortable) {
                // add attribute uk-sortable
                this.$refs.grid.setAttribute('uk-sortable', '');
            }
            else {
                // remove attribute uk-sortable
                this.$refs.grid.removeAttribute('uk-sortable');
            }
            // console.log(this.$refs.grid);
        },
        act_update_chart_data() {
            // update only the first chart as data is shared
            let chart = mixin_store.charts[0];
            let label = this.input_label;
            let data = this.input_data;

            chart.data.labels.push(label);
            chart.data.datasets.forEach((dataset) => {
                dataset.data.push(data);
            });

            // update each chart
            mixin_store.charts.forEach((chart) => {
                chart.update();
            });
        },
        act_remove_chart_data() {
            // update only the first chart as data is shared
            let chart = mixin_store.charts[0];
            chart.data.labels.pop();
            chart.data.datasets.forEach((dataset) => {
                dataset.data.pop();
            });
            // update each chart
            mixin_store.charts.forEach((chart) => {
                chart.update();
            });
        }
    }
}