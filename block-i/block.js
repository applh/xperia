( function ( blocks, element, blockEditor ) {
    var el = element.createElement;
    var InnerBlocks = blockEditor.InnerBlocks;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType( 'xperia/block-i', {
            title: 'XPeria: Inner Blocks',
        category: 'design',

        edit: function () {
            var blockProps = useBlockProps();

            return el( 'div', blockProps, el( InnerBlocks ) );
        },

        save: function () {
            var blockProps = useBlockProps.save();

            return el( 'div', blockProps, el( InnerBlocks.Content ) );
        },
    } );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );