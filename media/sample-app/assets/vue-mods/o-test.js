
let mixin_store = await import('./mixin-store.js');
let mixins = [ mixin_store.default ];


let template = `
<div class="compo">
    <h3>Notes</h3>
    <textarea v-model="user_note" class="uk-textarea" rows="10"></textarea>
    <button @click="count++">{{ count }}</button>
</div>
`

export default {
    template,
    mixins,
}