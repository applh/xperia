<?php
// security
if (!function_exists("add_action")) return;

// https://vuejs.org/guide/quick-start.html

?>
<div id="app">
    <h3>XP Sub-Domains</h3>
    <p>{{ message }}</p>
</div>

<script type="module">
    let media_url = "<?php xp_subdomain::e("media_url") ?>";
    // add vue app
    let vue = await import(media_url + '/vue.esm-browser.prod.js');

    vue.createApp({
        data() {
            return {
                message: 'Hello Vue!'
            }
        }
    }).mount('#app')
</script>