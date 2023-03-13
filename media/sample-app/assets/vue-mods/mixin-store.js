let vue = await import('../vue/vue.esm-browser.prod.js');

let store = vue.reactive({
    input_label: 'label',
    input_data: 10,
    chart_data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            borderWidth: 1
        }]
    },
    max: 100,
    user_note: '',
    login_email: '',
    login_user: '',
    panel: 'login',
    count: 0,
    message: 'Hello Vue!'
})

// keep the charts out of vue
export let charts = [];
// chartjs share the data with each chart
// must be updated manually or by repainting the chart
export let chart_data = {
    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
    datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
    }]
}

export default {
    data:() => store,
    methods: {
        load_components (app) {
            // WARNING: REGISTER ASYNC COMPONENTS FIRST
            // <template id="appTemplate" data-compos="test">
            let appTemplate = document.querySelector('#appTemplate');
            let compos = appTemplate?.getAttribute("data-compos");
            if (compos) {
                console.log('compos: ' + compos);
                compos = compos.split(' ');
                compos.forEach(function(name) {
                    console.log('register async component: ' + name);
                    app.component(
                        'o-' + name,
                        vue.defineAsyncComponent(() => import(`./o-${name}.js`))
                    );
                });
            }

        },
        async load_js_order(urls=[], index = 0, cb=null) {
            if (index >= urls.length) {
                // console.log('load_js callback', cb)
                // FIXME: should addEventListener('load', cb)
                if (cb) cb();
                return;
            }

            let id = urls[index]?.name ?? '';
            id = 'script-' + id;
            let el = document.getElementById(id);
            if (el) {
                console.log('File already loaded: ' + id)
                this.load_js_order(urls, index + 1, cb);
                return;
            }

            let url = urls[index]?.url ?? '';
            if (url) {
                let el = document.createElement('script');

                el.setAttribute('id', id);
                el.setAttribute('src', url);
                el.setAttribute('type', 'text/javascript');
                // el.setAttribute('async', async);
                // defer
                el.setAttribute('defer', true);

                document.body.appendChild(el);

                // success event
                el.addEventListener('load', () => {
                    console.log('File loaded: ' + url)
                    this.load_js_order(urls, index + 1, cb);
                });
                // error event
                el.addEventListener('error', (e) => {
                    console.log('Error on loading file: ' + url, e);
                    this.load_js_order(urls, index + 1, cb);
                });

            }
        },
        async load_js(url, async = true) {
            let el = document.createElement('script');

            el.setAttribute('src', url);
            el.setAttribute('type', 'text/javascript');
            // el.setAttribute('async', async);
            // defer
            el.setAttribute('defer', true);

            document.body.appendChild(el);

            // success event
            el.addEventListener('load', () => {
                console.log('File loaded: ' + url)
            });
            // error event
            el.addEventListener('error', (e) => {
                console.log('Error on loading file: ' + url, e);
            });
        },
        async load_css(name, url, async = true) {
            let id = 'css-' + name;
            let el = document.getElementById(id);
            if (el) {
                console.log('File already loaded: ' + url)
                return;
            }
            el = document.createElement('link');

            el.setAttribute('id', id);
            el.setAttribute('href', url);
            el.setAttribute('type', 'text/css');
            el.setAttribute('rel', 'stylesheet');
            document.head.appendChild(el);

            // success event
            el.addEventListener('load', () => {
                console.log('File loaded: ' + url)
            });
            // error event
            el.addEventListener('error', (e) => {
                console.log('Error on loading file: ' + url, e);
            });
        },
    }
}