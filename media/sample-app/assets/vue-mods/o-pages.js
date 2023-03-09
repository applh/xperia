
let mixin_store = await import('./mixin-store.js');
let mixins = [ mixin_store.default ];


let template = `
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


</div>
`

export default {
    template,
    mixins,
}