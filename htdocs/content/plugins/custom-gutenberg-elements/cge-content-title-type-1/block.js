const {__, setLocaleData} = wp.i18n;
const {registerBlockType,} = wp.blocks;
const {
    URLInput,
    URLInputButton,
    RichText,
    InspectorControls
} = wp.editor;

registerBlockType('custom-gutenberg-elements/cge-content-title-type-1', {
    title: __('CGE: Content - Titre type 1', 'custom-gutenberg-elements'),
    icon: 'index-card',
    category: 'formatting',
    attributes: {
        title: {
            type: 'string',
            source: 'html',
            selector: '.cge-content-title-type-1-title'
        },
        url: {
            type: 'string',
        }
    },
    edit: (props) => {
        const {
            className,
            attributes: {
                title,
                url
            },
            setAttributes,
        } = props;

        const onChangeTitle = (value) => {
            setAttributes({title: value});
        };
        const onChangeUrl = (url, post) => {
            setAttributes({url: url});
        };

        return (
            <div className={className + ' cge-bloc-container'}>
                <div className="alert alert-info alert-green">
                    <div className="cge-bloc-title">
                        <span className="title">CGE: Content - Titre Type 1</span>
                    </div>
                    <div className="row">
                        <div className="col-sm-12">
                            <h4 className="alert-heading mb-2">Titre</h4>
                            <RichText
                                tagName="h2"
                                className="cge-content-title-type-1-title block-body"
                                placeholder="Saisir le titre"
                                value={title}
                                onChange={onChangeTitle}
                            />
                        </div>
                        <div className="col-sm-12">
                            <h4 className="alert-heading mb-2">Lien</h4>
                            <URLInput className="cge-content-title-type-1-url" placeholder="Lien"
                                      value={url} onChange={onChangeUrl}/>
                        </div>
                    </div>
                </div>
            </div>
        );
    },
    save: (props) => {
        const {
            className,
            attributes: {
                title,
                url
            },
        } = props;
        return (
            <div className={"block block-download__title uk-width-1-1 " + className}>
                <a className="block-content" href={url}>
                    <RichText.Content tagName="h2" className="cge-content-title-type-1-title block-body" value={title}/>
                </a>
            </div>
        )
    },
});
