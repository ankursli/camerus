const {__, setLocaleData} = wp.i18n;
const {registerBlockType,} = wp.blocks;
const {
    RichText,
    InspectorControls
} = wp.editor;

registerBlockType('custom-gutenberg-elements/cge-full-page-heading', {
    title: __('CGE: Full - Heading Page', 'custom-gutenberg-elements'),
    icon: 'index-card',
    category: 'formatting',
    attributes: {
        title: {
            type: 'string',
            source: 'html',
            selector: '.cge-full-page-heading-1'
        },
        subtitle: {
            type: 'string',
            source: 'html',
            selector: '.cge-full-page-heading-2'
        }
    },
    edit: (props) => {
        const {
            className,
            attributes: {
                title,
                subtitle
            },
            setAttributes,
        } = props;

        const onChangeTitle = (value) => {
            setAttributes({title: value});
        };
        const onChangeSubtitle = (value) => {
            setAttributes({subtitle: value});
        };

        return (
            <div className={className + ' cge-bloc-container'}>
                <div className="alert alert-info alert-green">
                    <div className="cge-bloc-title">
                        <span className="title">CGE: Full - Heading Page</span>
                    </div>
                    <div className="row">
                        <div className="col-sm-12">
                            <h4 className="alert-heading mb-2">Titre</h4>
                            <RichText
                                tagName="h1"
                                className="cge-full-page-heading-1 block-header"
                                placeholder="Saisir le titre"
                                value={title}
                                onChange={onChangeTitle}
                            />
                        </div>
                        <div className="col-sm-12">
                            <h4 className="alert-heading mb-2">Sous titre</h4>
                            <RichText
                                tagName="h2"
                                className="cge-full-page-heading-2 block-body"
                                placeholder="Saisir le sous titre"
                                value={subtitle}
                                onChange={onChangeSubtitle}
                            />
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
                subtitle
            },
        } = props;
        return (
            <div id="heading" className={"layout container-fluid " + className}>
                <div className="layout-body inner">
                    <div className="row">
                        <div className="col-sm-10 col-sm-offset-1">
                            <div className="uk-grid-small" data-uk-grid>
                                <div className="block block-content__title uk-width-1-1">
                                    <div className="block-content">
                                        <RichText.Content tagName="h1"
                                                          className="cge-full-page-heading-1 block-header"
                                                          value={title}/>
                                        {
                                            subtitle && (
                                                <RichText.Content tagName="h2"
                                                className="cge-full-page-heading-2 block-body"
                                                value={subtitle}/>
                                            )
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    },
});
