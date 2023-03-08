<!DOCTYPE html>
<html>

<head>
    <title>Title</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="uikit/css/uikit.min.css" />
    <script src="uikit/js/uikit.min.js"></script>
    <script src="uikit/js/uikit-icons.min.js"></script>
</head>

<body>

    <div id="app"></div>

    <template id="appTemplate">
        <nav class="uk-navbar-container">
            <div class="uk-container">
                <div uk-navbar>

                    <div class="uk-navbar-center">

                        <ul class="uk-navbar-nav">
                            <li class="uk-active"><a href="#">Active</a></li>
                            <li>
                                <a href="#">Parent</a>
                                <div class="uk-navbar-dropdown">
                                    <ul class="uk-nav uk-navbar-dropdown-nav">
                                        <li class="uk-active"><a href="#">Active</a></li>
                                        <li><a href="#">Item</a></li>
                                        <li><a href="#">Item</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li><a href="#">Item</a></li>
                        </ul>

                    </div>

                </div>
            </div>
        </nav>

        <div uk-grid>

            <div class="uk-navbar-container uk-width-1-6">
                <div class="uk-container">
                    <ul class="uk-nav uk-nav-default">
                        <li class="uk-active"><a href="#">Active</a></li>
                        <li><a href="#">Item</a></li>
                        <li><a href="#">Item</a></li>
                        <li><a href="#offcanvas-usage" uk-toggle>Open</a></li>
                    </ul>
                </div>
            </div>
            <div class="uk-container uk-width-5-6">
                <div>{{ message }}</div>

                <button class="uk-button uk-button-default uk-margin-small-right" type="button" uk-toggle="target: #offcanvas-usage">Open</button>
                <a href="#offcanvas-usage" uk-toggle>Open</a>
            </div>
        </div>

        <div id="offcanvas-usage" uk-offcanvas>
            <div class="uk-offcanvas-bar">

                <button class="uk-offcanvas-close" type="button" uk-close></button>

                <h3>Title</h3>

                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat.</p>

                <div class="">
                    <ul class="uk-nav uk-nav-default">
                        <li class="uk-active"><a href="#">Active</a></li>
                        <li><a href="#">Item</a></li>
                        <li><a href="#">Item</a></li>
                    </ul>
                </div>

            </div>

        </div>

    </template>

    <script type="module">
        import {
            createApp
        } from './vue.esm-browser.prod.js'

        createApp({
            template: '#appTemplate',
            data() {
                return {
                    message: 'Hello Vue!'
                }
            }
        }).mount('#app')
    </script>

</body>

</html>