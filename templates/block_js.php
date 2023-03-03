<?php

// TODO: should load blog-header.php to get the wp functions

// header
header("Content-Type: application/javascript");
$block = $_GET['block'] ?? "";
?>
(function (blocks, element, data, blockEditor) {
    console.log('DYNAMIC BLOCK BY PHP');
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
            // method needed if you want to see block in the editor
            let content;
            let blockProps = useBlockProps();
            content = 'testing...';
            return el('div', blockProps, content);
        },
        supports: { color: { gradients: true, link: true }, align: true },
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.data,
    window.wp.blockEditor
);