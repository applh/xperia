let media_url = import.meta.url;
media_url = media_url.replace('/xp-options.js', '');

let vue = await import(media_url + '/vue.esm-browser.prod.js');
let ds = await import(media_url + '/xp-datastore.js');
let dsc = ds.center;

let mixins = [ds.default]; // warning: must add .default


let template = `
<div class="compo">
    <h3>Options</h3>
    <div>
        <div v-for="sd in center.subdomains">
            <input type="text" v-model="sd.name"/>
        </div>
    </div>
    <button @click="act_add_subdomain">Add Sub-Domain</button>
    <hr/>
    <button @click="act_save_subdomains">Save</button>
    <hr/>
    <button @click="center.count++">{{ center.count }} Click</button>
</div>
`

let methods = {
    act_add_subdomain: function() {
        let next = this.center.subdomains.length + 1;
        this.center.subdomains.push({
            name: 'www-' + next,
        });
    },
    act_save_subdomains: async function() {
        console.log('save');
        let fd = new FormData();
        fd.append('action', 'xpsubdomain');
        let response = await fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: fd,
        });
        let json = await response.json();
        console.log(json);
    }
}

export default {
    template,
    mixins,
    methods,
}