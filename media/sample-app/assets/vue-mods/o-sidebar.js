
let mixin_store = await import('./mixin-store.js');
let mixins = [ mixin_store.default ];


let template = `
<div class="compo">
    <div class="uk-container">
        <ul class="uk-nav uk-nav-default">
            <li><a href="#">Media</a></li>
            <li><a href="#">Articles</a></li>
            <li class="uk-active"><a href="#">Pages</a></li>
            <li><a href="#">Templates</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#offcanvas-usage" uk-toggle>Options</a></li>
        </ul>
        <o-test />
    </div>
</div>
`

export default {
    template,
    mixins,
}