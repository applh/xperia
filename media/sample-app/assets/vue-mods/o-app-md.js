
let mixin_store = await import('./mixin-store.js');
let mixins = [ mixin_store.default ];


let template = `
<div class="compo">
    <nav class="uk-navbar-container">
    <o-header />
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
    </div>

    <div uk-grid v-if="panel=='dashboard'">
        <div class="uk-navbar-container uk-width-1-6@m">
            <o-sidebar />
        </div>
        <div class="uk-width-5-6@m">
            <o-dashboard />
        </div>
        <div class="uk-width-1-2@m">
            <o-chart />
        </div>
        <div class="uk-width-1-2@m">
            <o-map />
        </div>
        <div class="uk-width-1-2@m">
            <o-chart chart_type='doughnut' />
        </div>
        <div class="uk-width-1-2@m">
            <o-chart chart_type='line' />
        </div>
    </div>

    <div uk-grid v-else-if="panel=='pages'">

    <div class="uk-navbar-container uk-width-1-6">
        <o-sidebar />
    </div>
    <div class="uk-container uk-width-5-6">
        <o-pages />
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
</div>
`

export default {
    template,
    mixins,
}