<!DOCTYPE html>
<html>

<head>
    <title>Xperia App</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/images/avatar.jpg" type="image/x-icon" />
    <link rel="stylesheet" href="assets/uikit/css/uikit.min.css" />

    <script src="assets/uikit/js/uikit.min.js"></script>
    <script src="assets/uikit/js/uikit-icons.min.js"></script>

    <style>
        .map {
            width: 100%;
            height: 50vmin;
        }

        .leaflet-control-attribution {
            display: none;
        }
        .compo > h3 {
            text-align: center;
            padding-top:1rem;
        }
        .render {
            width: 100%;
            height: 50vmin;
        }
    </style>
</head>

<body>
    <div id="app"></div>

    <template id="appTemplate" data-compos="app-md test map chart pages dashboard header sidebar scene">
        <o-app-md />
    </template>

    <script type="module">
        let vue = await import('./assets/vue/vue.esm-browser.prod.js');
        let mixin_store = await import('./assets/vue-mods/mixin-store.js');
        let mixins = [mixin_store.default];

        let app = vue.createApp({
            template: '#appTemplate',
            mixins,
            data: () => {},
            created() {
                this.load_components(app);
            },
            mounted() {}
        })

        app.mount('#app')
    </script>

</body>

</html>