
let mixin_store = await import('./mixin-store.js');
let mixins = [ mixin_store.default ];


let template = `
<div class="compo">
    <div class="uk-flex-center" uk-grid uk-sortable>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-2">
            <h1>Dashboard</h1>
            <em>Welcome {{ login_user }}</em>
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
    </div>
</div>
`

export default {
    template,
    mixins,
}