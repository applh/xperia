<!DOCTYPE html>
<html>

<head>
    <title>Title</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/uikit/css/uikit.min.css" />
    <link rel="stylesheet" href="assets/leaflet/leaflet.css" />

    <script src="assets/leaflet/leaflet.js"></script>
    <script src="assets/uikit/js/uikit.min.js"></script>
    <script src="assets/uikit/js/uikit-icons.min.js"></script>

    <style>
        #map {
            width:100%;
            height: 180px;
        }
    </style>
    <script>
        let leaflet_init = function() {
            const map = L.map('map').setView([51.505, -0.09], 13);

            const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            const marker = L.marker([51.5, -0.09]).addTo(map)
                .bindPopup('<b>Hello world!</b><br />I am a popup.').openPopup();

            const circle = L.circle([51.508, -0.11], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 500
            }).addTo(map).bindPopup('I am a circle.');

            const polygon = L.polygon([
                [51.509, -0.08],
                [51.503, -0.06],
                [51.51, -0.047]
            ]).addTo(map).bindPopup('I am a polygon.');


            const popup = L.popup()
                .setLatLng([51.513, -0.09])
                .setContent('I am a standalone popup.')
                .openOn(map);

            function onMapClick(e) {
                popup
                    .setLatLng(e.latlng)
                    .setContent(`You clicked the map at ${e.latlng.toString()}`)
                    .openOn(map);
            }

            map.on('click', onMapClick);

        }
    </script>
</head>

<body>

    <div id="app"></div>

    <template id="appTemplate">
        <nav class="uk-navbar-container">
            <div class="uk-container">
                <div uk-navbar>

                    <div class="uk-navbar-center">

                        <ul class="uk-navbar-nav" v-if="login_user">
                            <li class="uk-active"><a href="#" @click.prevent="panel='dashboard'">Dashboard</a></li>
                            <li>
                                <a href="#" @click.prevent="panel='pages'">Standard</a>
                                <div class="uk-navbar-dropdown">
                                    <ul class="uk-nav uk-navbar-dropdown-nav">
                                        <li class="uk-active"><a href="#">Dashboard</a></li>
                                        <li><a href="#">Item</a></li>
                                        <li><a href="#">Item</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#" @click.prevent="panel='pages'">Custom</a>
                                <div class="uk-navbar-dropdown">
                                    <ul class="uk-nav uk-navbar-dropdown-nav">
                                        <li class="uk-active"><a href="#">Dashboard</a></li>
                                        <li><a href="#">Item</a></li>
                                        <li><a href="#">Item</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#" @click.prevent="panel='pages'">Expert</a>
                                <div class="uk-navbar-dropdown">
                                    <ul class="uk-nav uk-navbar-dropdown-nav">
                                        <li class="uk-active"><a href="#">Dashboard</a></li>
                                        <li><a href="#">Item</a></li>
                                        <li><a href="#">Item</a></li>
                                    </ul>
                                </div>
                            </li>

                            <li><a href="#" @click.prevent="login_user='';panel='login'">Logout</a></li>
                        </ul>

                    </div>

                </div>
            </div>
        </nav>

        <div uk-grid v-if="panel=='login'">
            <div class="uk-container uk-width-6-6">
                <div class="uk-flex uk-flex-center">
                    <div class="uk-card uk-card-default uk-card-body">
                        <h1>Welcome</h1>
                    </div>
                    <div class="uk-card uk-card-default uk-card-body uk-margin-left">
                        <form @submit.prevent="login_user=login_email;panel='dashboard'">
                            <fieldset class="uk-fieldset">
                                <legend class="uk-legend">Login</legend>
                                <div class="uk-margin">
                                    <input class="uk-input" type="email" required placeholder="email" aria-label="email" v-model="login_email">
                                </div>
                                <div class="uk-margin">
                                    <input class="uk-input" type="password" required placeholder="password" aria-label="password">
                                </div>
                                <div class="uk-margin">
                                    <button type="submit" class="uk-button uk-button-primary">Login</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="uk-card uk-card-default uk-card-body uk-margin-left">
                        <h3>Create an account</h3>
                        <button class="uk-button uk-button-small uk-button-primary" uk-toggle="target: #modal-register">Register</button>
                    </div>
                </div>
            </div>
            <map-box />
        </div>

        <div uk-grid v-if="panel=='dashboard'">
            <div class="uk-navbar-container uk-width-1-6">
                <menu-sidebar />
            </div>
            <div class="uk-width-5-6">
                <dashboard-main />
            </div>
        </div>

        <div uk-grid v-else-if="panel=='pages'">

            <div class="uk-navbar-container uk-width-1-6">
                <menu-sidebar />
            </div>
            <div class="uk-container uk-width-5-6">
                <pages-main />
            </div>
        </div>

        <div id="modal-delete" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Confirm Delete ?</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <p class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                    <button class="uk-button uk-button-danger" type="button">Delete Item</button>
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

        <div id="modal-register" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Create your account</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <form>
                    <fieldset class="uk-fieldset">

                        <legend class="uk-legend">Please enter your profile informations</legend>

                        <div class="uk-margin">
                            <input class="uk-input" type="email" placeholder="email" aria-label="email">
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="password" placeholder="password" aria-label="password">
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="text" placeholder="Input" aria-label="Input">
                        </div>

                        <div class="uk-margin">
                            <select class="uk-select" aria-label="Select">
                                <option>Option 01</option>
                                <option>Option 02</option>
                            </select>
                        </div>

                        <div class="uk-margin">
                            <textarea class="uk-textarea" rows="5" placeholder="Textarea" aria-label="Textarea"></textarea>
                        </div>

                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="radio2" checked> A</label>
                            <label><input class="uk-radio" type="radio" name="radio2"> B</label>
                        </div>

                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                            <label><input class="uk-checkbox" type="checkbox" checked> A</label>
                            <label><input class="uk-checkbox" type="checkbox"> B</label>
                        </div>

                        <div class="uk-margin">
                            <input class="uk-range" type="range" value="2" min="0" max="10" step="0.1" aria-label="Range">
                        </div>

                    </fieldset>
                </form>
                <p class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                    <button class="uk-button uk-button-primary" type="button">Create your account</button>
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

    <template id="sidebarTemplate">
        <div class="uk-container">
            <ul class="uk-nav uk-nav-default">
                <li><a href="#">Media</a></li>
                <li><a href="#">Articles</a></li>
                <li class="uk-active"><a href="#">Pages</a></li>
                <li><a href="#">Templates</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#offcanvas-usage" uk-toggle>Options</a></li>
            </ul>
        </div>

    </template>

    <template id="DashboardMain">
        <div class="uk-flex-center" uk-grid uk-sortable>
            <div class="uk-card uk-card-default uk-card-body uk-width-6-6">
                <h1>Dashboard</h1>
                <em>Welcome {{ login_user }}</em>
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
        </div>

    </template>

    <template id="PagesMain">
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
                            <img class="uk-border-circle" width="40" height="40" src="assets/images/avatar.jpg" alt="Avatar">
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
                            <button class="uk-button uk-button-small uk-button-secondary" uk-toggle="target: #modal-copy"><span title="copy" class="" uk-icon="icon: copy; ratio: 1"></span></button>
                            <button class="uk-button uk-button-small uk-button-primary" uk-toggle="target: #modal-update"><span title="update" class="" uk-icon="icon: file-edit; ratio: 1"></span></button>
                            <button class="uk-button uk-button-small uk-button-danger" uk-toggle="target: #modal-delete"><span title="delete" class="" uk-icon="icon: trash; ratio: 1"></span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="uk-container uk-width-6-6">
            <div class="uk-flex-center" uk-grid uk-sortable>
                <div class="uk-card uk-card-default uk-card-body uk-width-6-6">
                    <span>{{ message }}</span>
                </div>
                <div class="uk-card uk-card-default uk-card-body uk-width-1-4">
                    <span>{{ message }}</span>
                </div>
                <div class="uk-card uk-card-default uk-card-body uk-width-1-4">
                    <button class="uk-button uk-button-default uk-margin-small-right" type="button" uk-toggle="target: #offcanvas-usage">Open</button>
                </div>
                <div class="uk-card uk-card-default uk-card-body uk-width-1-4">
                    <a href="#offcanvas-usage" uk-toggle>Open</a>
                </div>
                <div class="uk-card uk-card-default uk-card-body uk-width-1-4">
                    <a href="#offcanvas-usage" uk-toggle>Open</a>
                </div>
            </div>
        </div>
    </template>

    <template id="mapBox">
        <div id="map"></div>
    </template>

    <script type="module">
        import {
            createApp
        } from './assets/vue/vue.esm-browser.prod.js'

        let app = createApp({
            template: '#appTemplate',
            data() {
                return {
                    login_email: '',
                    login_user: '',
                    panel: 'login'
                }
            },
            mounted () {
            }
        })

        app.component('mapBox', {
            template: '#mapBox',
            data() {
                return {}
            },
            mounted () {
                leaflet_init();
            }
        })

        app.component('MenuSidebar', {
            template: '#sidebarTemplate',
            data() {
                return {}
            }
        })

        app.component('DashboardMain', {
            template: '#DashboardMain',
            data() {
                return {}
            }
        })
        app.component('PagesMain', {
            template: '#PagesMain',
            data() {
                return {
                    max: 100,
                    message: 'Hello Vue!'
                }
            }
        })

        app.mount('#app')
    </script>

</body>

</html>