// console.log(import.meta);

let media_url = import.meta.url;
media_url = media_url.replace('/xp-datastore.js', '');

let vue = await import(media_url + '/vue.esm-browser.prod.js');

// common data store
export let center = vue.reactive({
    count: 0,
    subdomains: [],
    api_key: '',
})

let computed = {
    center: () => center,
}

export default {
    computed,
}