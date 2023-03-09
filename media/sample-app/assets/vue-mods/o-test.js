
let mixin_store = await import('./mixin-store.js');
let mixins = [ mixin_store.default ];


let template = `
<div class="compo">
    <hr>
    <textarea v-model="user_note" class="uk-textarea"></textarea>
    <button @click="count++">{{ count }}</button>
</div>
`

export default {
    template,
    mixins,
}