const {__, setLocaleData} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {
    RichText,
    InnerBlocks,
} = wp.editor;
const {Button} = wp.components;
const {withState} = wp.compose;

const BLOCKS_TEMPLATE = [
    ['custom-gutenberg-elements/cge-content-page-brands-images', {}],
    ['custom-gutenberg-elements/cge-content-page-brands-images', {}],
];
const ALLOWED_BLOCKS = ['custom-gutenberg-elements/cge-content-page-brands-images'];

registerBlockType('custom-gutenberg-elements/cge-content-page-brands', {
    title: __('CGE: Content Layout - Brands Publication', 'custom-gutenberg-elements'),
    icon: 'index-card',
    category: 'layout',
    attributes: {
        titleTop: {
            type: 'string',
            source: 'html',
            selector: '.cge-content-page-brands-images-title',
        },
    },
    edit: (props) => {
        const {
            className,
            attributes: {
                titleTop,
            },
            setAttributes,
        } = props;

        const onChangeTitle = (value) => {
            setAttributes({titleTop: value});
        };

        return (
            <div className={className + ' cge-bloc-container'}>
                <div className="alert alert-info alert-pink">
                    <div className="cge-bloc-title">
                        <span className="title">CGE: Content Layout - Brands</span>
                    </div>
                    <div className="row">
                        <div className="col-sm-12 col-md-12">
                            <div className="form-group">
                                <label>Titre</label>
                                <RichText
                                    tagName="h2"
                                    className="cge-content-page-brands-images-title"
                                    placeholder="Saisir le titre"
                                    value={titleTop}
                                    onChange={onChangeTitle}
                                />
                            </div>
                        </div>
                    </div>
                    <div className="col-sm-12 col-md-12 item-list ">
                        <InnerBlocks template={BLOCKS_TEMPLATE} allowedBlocks={ALLOWED_BLOCKS} templateLock={false}/>
                    </div>
                </div>
            </div>
        );
    },
    save: (props) => {
        const {
            className,
            attributes: {
                titleTop,
            },
        } = props;

        return (
            <div className={"block block-content__trust uk-width-1-1 " + className}>
                <div className="block-content">
                    <RichText.Content tagName="h2" className="cge-content-page-brands-images-title block-header"
                                      value={titleTop}/>
                    <div className="block-body">
                        <ul className="uk-grid uk-child-width-1-4@m uk-child-width-1-2@s uk-flex-middle uk-grid-medium uk-flex-center">
                            <InnerBlocks.Content/>
                        </ul>
                    </div>
                </div>
            </div>
        );
    },
});
