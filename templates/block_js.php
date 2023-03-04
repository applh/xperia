<?php

// TODO: should load blog-header.php to get the wp functions

// header
header("Content-Type: application/javascript");
$block = $_GET['block'] ?? "";
?>
(async function (blocks, element, data, blockEditor) {
    console.log('DYNAMIC BLOCK BY PHP');

    // GET modules OK
    let toto = await import('./module_js.php');
    toto.default.test();
    // POST fetch KO :-/
    // await toto.default.test_fetch();

    var el = element.createElement,
        registerBlockType = blocks.registerBlockType,
        useSelect = data.useSelect,
        useBlockProps = blockEditor.useBlockProps;

    registerBlockType('xperia/<?php echo $block ?>', {
        apiVersion: 2,
        title: 'XPeria: Dynamic',
        icon: 'megaphone',
        category: 'widgets',
        edit: function () {
            // TODO: use wp.apiFetch
            // https://developer.wordpress.org/block-editor/reference-guides/packages/packages-api-fetch/

            /* method needed if you want to see block in the editor */
            /* react can't activate script tags in gutenberg editor */
            let blockProps = useBlockProps();
            let content = 'testing...';

            // react element
            let h1 = el('h1', null, 'title1');
            let elem = el('div', blockProps, h1, content);

            return elem;
        },
        supports: { color: { gradients: true, link: true }, align: true },
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.data,
    window.wp.blockEditor
);