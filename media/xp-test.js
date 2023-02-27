let media_url = import.meta.url;
media_url = media_url.replace('/xp-test.js', '');

let vue = await import(media_url + '/vue.esm-browser.prod.js');
let ds = await import(media_url + '/xp-datastore.js');
let dsc = ds.center;

let mixins = [ds.default]; // warning: must add .default


let template = `
<div class="compo">
    <h3>Test</h3>
    <input type="range" v-model="center.count" min="0" max="100">
    <button @click="center.count++">add ({{ center.count }})</button>
    <button @click="center.count--">remove ({{ center.count }})</button>
</div>
`

export default {
    template,
    mixins,
}