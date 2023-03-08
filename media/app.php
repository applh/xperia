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
                            <li class="uk-active"><a href="#">Dashboard</a></li>
                            <li>
                                <a href="#">Standard</a>
                                <div class="uk-navbar-dropdown">
                                    <ul class="uk-nav uk-navbar-dropdown-nav">
                                        <li class="uk-active"><a href="#">Dashboard</a></li>
                                        <li><a href="#">Item</a></li>
                                        <li><a href="#">Item</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#">Custom</a>
                                <div class="uk-navbar-dropdown">
                                    <ul class="uk-nav uk-navbar-dropdown-nav">
                                        <li class="uk-active"><a href="#">Dashboard</a></li>
                                        <li><a href="#">Item</a></li>
                                        <li><a href="#">Item</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#">Expert</a>
                                <div class="uk-navbar-dropdown">
                                    <ul class="uk-nav uk-navbar-dropdown-nav">
                                        <li class="uk-active"><a href="#">Dashboard</a></li>
                                        <li><a href="#">Item</a></li>
                                        <li><a href="#">Item</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li><a href="#">Logout</a></li>
                        </ul>

                    </div>

                </div>
            </div>
        </nav>

        <div uk-grid>

            <div class="uk-navbar-container uk-width-1-6">
                <div class="uk-container">
                    <ul class="uk-nav uk-nav-default">
                        <li class="uk-active"><a href="#">Pages</a></li>
                        <li><a href="#">Templates</a></li>
                        <li><a href="#">Profile</a></li>
                        <li><a href="#offcanvas-usage" uk-toggle>Options</a></li>
                    </ul>
                </div>
            </div>
            <div class="uk-container uk-width-5-6">
                <div class="uk-container uk-width-6-6">
                    <h1>Pages</h1>
                    <input type="range" v-model="max" min="0" max="1000" step="1">
                    <input type="number" v-model="max" min="0" max="1000" step="1">
                    <span> pages</span>
                </div>
                <div uk-grid uk-sortable>

                    <div v-for="n in (1 * max)" class="uk-card uk-card-small uk-card-default uk-width-1-2@s uk-width-1-3@m uk-width-1-4@l uk-width-1-5@xl">
                        <div class="uk-card-header">
                            <div class="uk-grid-small uk-flex-middle" uk-grid>
                                <div class="uk-width-auto">
                                    <img class="uk-border-circle" width="40" height="40" src="images/avatar.jpg" alt="Avatar">
                                </div>
                                <div class="uk-width-expand">
                                    <h3 class="uk-card-title uk-margin-remove-bottom">Title ({{ n }})</h3>
                                    <p class="uk-text-meta uk-margin-remove-top"><time datetime="2016-04-01T19:00">April 01, 2016</time></p>
                                </div>
                            </div>
                        </div>
                        <div class="uk-card-body">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
                            <a href="#offcanvas-usage" uk-toggle class="uk-button uk-button-text">Read more</a>
                        </div>
                        <div class="uk-card-footer">
                            <div>
                                <div class="uk-button-group">
                                    <button class="uk-button uk-button-small uk-button-secondary" uk-toggle="target: #modal-copy">copy</button>
                                    <button class="uk-button uk-button-small uk-button-primary" uk-toggle="target: #modal-update">update</button>
                                    <button class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-delete">delete</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="uk-container uk-width-6-6">
                <span>{{ message }}</span>
                <button class="uk-button uk-button-default uk-margin-small-right" type="button" uk-toggle="target: #offcanvas-usage">Open</button>
                <a href="#offcanvas-usage" uk-toggle>Open</a>
            </div>

        </div>

        <div id="modal-delete" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Confirm Delete ?</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <p class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                    <button class="uk-button uk-button-primary" type="button">Delete Item</button>
                </p>
            </div>
        </div>

        <div id="modal-update" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Update Item</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <p class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                    <button class="uk-button uk-button-primary" type="button">Save Item</button>
                </p>
            </div>
        </div>

        <div id="modal-copy" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Add Item</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <p class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                    <button class="uk-button uk-button-primary" type="button">Save Item</button>
                </p>
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
                    max: 100,
                    message: 'Hello Vue!'
                }
            }
        }).mount('#app')
    </script>

</body>

</html>