(function (blocks, element, data, blockEditor) {
    var el = element.createElement,
        registerBlockType = blocks.registerBlockType,
        useSelect = data.useSelect,
        useBlockProps = blockEditor.useBlockProps;

    registerBlockType('xperia/block-d', {
        apiVersion: 2,
        title: 'XPeria: Last Post',
        icon: 'megaphone',
        category: 'widgets',
        edit: function () {
            var content;
            var blockProps = useBlockProps();
            var posts = useSelect(function (select) {
                return select('core').getEntityRecords('postType', 'post');
            }, []);
            if (!posts) {
                content = 'Loading...';
            } else if (posts.length === 0) {
                content = 'No posts';
            } else {
                var post = posts[0];
                content = el('a', { href: post.link }, post.title.rendered);
            }

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