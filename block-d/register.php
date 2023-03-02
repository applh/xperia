<?php

// dynamic block
// https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/

// automatically load dependencies and version
$asset_file = include(plugin_dir_path(__FILE__) . 'block.asset.php');

wp_register_script(
    'xperia-block-d',
    plugins_url('block.js', __FILE__),
    $asset_file['dependencies'],
    $asset_file['version']
);

register_block_type('xperia/block-d', array(
    'api_version' => 2,
    'editor_script' => 'xperia-block-d', // The script name we gave in the wp_register_script() call.
    'render_callback' => 'xperia::block_d_render_callback',
    // 'supports' => array('color' => true, 'align' => true),
));
