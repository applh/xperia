
let mixin_store = await import('./mixin-store.js');
let mixins = [ mixin_store.default ];


let template = `
<div class="uk-container compo">
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
`

export default {
    template,
    mixins,
}