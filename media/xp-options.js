let media_url = import.meta.url;
media_url = media_url.replace('/xp-options.js', '');

let vue = await import(media_url + '/vue.esm-browser.prod.js');
let ds = await import(media_url + '/xp-datastore.js');
let dsc = ds.center;

let mixins = [ds.default]; // warning: must add .default


let template = `
<div class="compo">
    <h3>Options</h3>
    <button @click="center.count++">{{ center.count }} Click</button>
</div>
`

export default {
    template,
    mixins,
}