const {__, setLocaleData} = wp.i18n;
const {
    registerBlockType,
} = wp.blocks;
const {
    InnerBlocks
} = wp.editor;

const BLOCKS_TEMPLATE = [
    // ['custom-gutenberg-elements/cge-content-breadcrumbs', {}],
    // [ 'core/paragraph', { placeholder: 'Image Details' } ],
];

registerBlockType('custom-gutenberg-elements/cge-layout-sidebar', {
    title: __('CGE: Layout - Sidebar', 'custom-gutenberg-elements'),
    icon: 'index-card',
    category: 'layout',
    attributes: {},
    edit: (props) => {
        const {
            className,
            attributes: {},
            setAttributes,
        } = props;


        return (
            <div className={className + ' container-fluid'}>
                <div className="row">
                    <div className="col-sm-12">
                        <InnerBlocks template={BLOCKS_TEMPLATE} templateLock={false}/>
                    </div>
                </div>
            </div>
        );
    },
    save: (props) => {
        const {
            className,
            attributes: {},
        } = props;

        return (
            <aside className={"col-sm-2 col-sm-offset-1 " + className}>
                <div className="uk-grid-small" data-uk-grid>
                    <InnerBlocks.Content/>
                </div>
            </aside>
        );
    }
});
