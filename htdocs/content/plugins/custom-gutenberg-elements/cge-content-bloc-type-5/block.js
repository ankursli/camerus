const {__, setLocaleData} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {
    RichText,
    InnerBlocks
} = wp.editor;

const BLOCKS_TEMPLATE = [
    ['core/heading', {}],
    ['core/paragraph', {}],
    ['core/list', {}],
];

registerBlockType('custom-gutenberg-elements/cge-content-bloc-type-5', {
    title: __('CGE: Content - Bloc type 5', 'custom-gutenberg-elements'),
    icon: 'index-card',
    category: 'formatting',
    attributes: {},
    edit: (props) => {
        const {
            className,
            attributes: {},
            setAttributes,
        } = props;


        return (
            <div className={className + ' cge-bloc-container'}>
                <div className="alert alert-info alert-yellow">
                    <div className="cge-bloc-title">
                        <span className="title">CGE: Content - Bloc Type 5</span>
                    </div>
                    <div className="row">
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
            <div className={"block block-content__type5 uk-width-1-1 " + className}>
                <div className="block-content row">
                    <div className="block-text col-md-7 col-sm-6">
                        <div className="summary rte">
                            <InnerBlocks.Content/>
                        </div>
                    </div>
                </div>
            </div>
        );
    },
});
