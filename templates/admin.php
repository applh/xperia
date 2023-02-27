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
// add vue app
import { createApp } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.prod.js'
createApp({
    data() {
      return {
        message: 'Hello Vue!'
      }
    }
  }).mount('#app')

</script>