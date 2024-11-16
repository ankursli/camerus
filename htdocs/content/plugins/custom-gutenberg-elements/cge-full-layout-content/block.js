const {__, setLocaleData} = wp.i18n;
const {
    registerBlockType,
} = wp.blocks;
const {
    InnerBlocks
} = wp.editor;

const BLOCKS_TEMPLATE = [
    ['custom-gutenberg-elements/cge-layout-content', {}],
];

registerBlockType('custom-gutenberg-elements/cge-full-layout-content', {
    title: __('CGE: Full - Layout Content', 'custom-gutenberg-elements'),
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
            <div className={className + ' cge-bloc-container'}>
                <div className="alert alert-info alert-orange-light">
                    <div className="cge-bloc-title">
                        <span className="title">CGE: Full Layout Content</span>
                    </div>
                    <div className="row">
                        <div className="col-sm-12">
                            <InnerBlocks template={BLOCKS_TEMPLATE} templateLock={false}/>
                        </div>
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
            <div id="layout" className={"layout container-fluid " + className}>
                <div className="layout-body inner">
                    <div className="row">
                        <InnerBlocks.Content/>
                    </div>
                </div>
            </div>
        );
    }
});
