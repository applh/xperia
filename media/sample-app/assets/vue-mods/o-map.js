
let mixin_store = await import('./mixin-store.js');
let mixins = [ mixin_store.default ];


let template = `
<div class="compo">
    <h3>Map</h3>
    <div class="map" ref="map"></div>
</div>
`

export default {
    template,
    mixins,
    mounted() {
        // path relative to web page
        this.load_css('leaflet', 'assets/leaflet/leaflet.css')
        let script_js = [{
            'name': 'leaflet',
            'url': 'assets/leaflet/leaflet.js'
        }]
    
        this.load_js_order(script_js, 0, this.leaflet_init)
    },
    methods: {
        leaflet_init: function() {
            console.log('leaflet_init')
            const map = L.map(this.$refs.map).setView([48.2872, 2.842929], 8);

            const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: ''
            }).addTo(map);

            const marker = L.marker([48.2872, 2.842929]).addTo(map)
                .bindPopup('<b>Hello world!</b><br />I am a popup.').openPopup();

            const circle = L.circle([48.2872, 2.842929], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 500
            }).addTo(map).bindPopup('I am a circle.');

            const polygon = L.polygon([
                [48.27, 2.84],
                [48.30, 2.85],
                [48.28, 2.86],
            ]).addTo(map).bindPopup('I am a polygon.');


            const popup = L.popup()
                .setLatLng([48.2872, 2.842929])
                .setContent('I am a standalone popup.')
                .openOn(map);

            function onMapClick(e) {
                popup
                    .setLatLng(e.latlng)
                    .setContent(`You clicked the map at ${e.latlng.toString()}`)
                    .openOn(map);
            }

            map.on('click', onMapClick);

        }
    }
}