<?php
// security
if (!function_exists("add_action")) return;

// https://vuejs.org/guide/quick-start.html

?>
<div id="app">
</div>
<template id="appTemplate" data-compos="test options">
    <div>
        <h3>XP Sub-Domains</h3>
        <xp-options></xp-options>
        <xp-test></xp-test>
        <p>{{ message }}</p>
    </div>
</template>
<script type="module">
    let media_url = "<?php xp_subdomain::e("media_url") ?>";
    // add vue app
    let vue = await import(media_url + '/vue.esm-browser.prod.js');
    let ds = await import(media_url + '/xp-datastore.js');    
    let mixins = [ds.default]; // warning: must add .default

    let created = function ()
    {
        console.log("created");
        // register components
        this.load_components(app);

        // add resize event listener
        this.act_resize();
        window.addEventListener('resize', this.act_resize);
    }

    let data = {
        window_w: window.innerWidth,
        window_h: window.innerHeight,
        message: 'Hello Vue!'
    }

    let methods = {
        act_resize() {
            this.window_w = window.innerWidth;
            this.window_h = window.innerHeight;
            this.message = '' + this.window_w + 'x' + this.window_h;
        },
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
                        'xp-' + name,
                        vue.defineAsyncComponent(() => import(media_url + `/xp-${name}.js`))
                    );
                });
            }

        },

    }

    let app = vue.createApp({
        template: '#appTemplate',
        mixins,
        data: () => data,
        methods,
        created,
    });
    // hack: need app to be available in created()
    app.mount('#app')

</script>