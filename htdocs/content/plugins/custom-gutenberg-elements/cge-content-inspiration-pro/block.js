const {__, setLocaleData} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {
    RichText,
    MediaUpload,
    URLInput,
    URLInputButton,
    InnerBlocks
} = wp.editor;
const {Button} = wp.components;

const BLOCKS_TEMPLATE = [
    ['core/paragraph', {}]
];

registerBlockType('custom-gutenberg-elements/cge-content-inspiration-pro', {
    title: __('CGE: Content - Inspiration Pro', 'custom-gutenberg-elements'),
    icon: 'index-card',
    category: 'formatting',
    attributes: {
        mediaId: {
            type: 'number',
        },
        mediaUrl: {
            type: 'string',
            source: 'attribute',
            selector: '.cge-content-inspiration-pro-img-1',
            attribute: 'src',
        },
        mediaAlt: {
            type: 'string',
            source: 'attribute',
            selector: '.cge-content-inspiration-pro-img-1',
            attribute: 'alt',
        },
        title: {
            type: 'string',
            source: 'html',
            selector: '.cge-content-inspiration-pro-title'
        },
        footer_text: {
            type: 'string',
            source: 'html',
            selector: '.cge-content-inspiration-pro-footer-text'
        },
        btnUrl: {
            type: 'string',
            selector: '.cge-content-inspiration-pro-url',
        },
        btnText: {
            type: 'string',
        },
    },
    edit: (props) => {
        const {
            className,
            attributes: {
                mediaId,
                mediaUrl,
                mediaAlt,
                title,
                footer_text,
                btnUrl,
                btnText
            },
            setAttributes,
        } = props;

        const onSelectImage = (media) => {
            setAttributes({
                mediaUrl: media.url,
                mediaId: media.id,
                mediaAlt: media.alt
            });
        };
        const onChangeTitle = (value) => {
            setAttributes({title: value});
        };
        const onChangeFooterText = (value) => {
            setAttributes({footer_text: value});
        };
        const onChangeBtn = (value) => {
            setAttributes({btnUrl: value});
        };
        const onChangeBtnText = (value) => {
            setAttributes({btnText: value});
        };

        return (
            <div className={className + ' cge-bloc-container'}>
                <div className="alert alert-info alert-yellow">
                    <div className="cge-bloc-title">
                        <span className="title">CGE: Content - Inspiration Catalogue</span>
                    </div>
                    <div className="row">
                        <div className="col-sm-6 col-md-6 col-lg-6">
                            <div className="row">
                                <div className="col-sm-12">
                                    <MediaUpload
                                        onSelect={onSelectImage}
                                        allowedTypes="image"
                                        value={mediaId}
                                        render={({open}) => (
                                            <Button className={mediaId ? 'image-button' : 'button button-large'}
                                                    onClick={open}>
                                                {!mediaId ? __('Upload Image', 'custom-gutenberg-elements') :
                                                    <img src={mediaUrl}
                                                         alt={mediaAlt}
                                                         className="img-thumbnail cge-content-inspiration-pro-img-1"
                                                         width="194" height="102"/>}
                                            </Button>
                                        )}
                                    />
                                </div>
                            </div>
                        </div>
                        <div className="col-sm-6 col-md-6 col-lg-6">
                            <div className="form-row">
                                <div className="col">
                                    <div className="form-group">
                                        <label>Titre du bloc</label>
                                        <RichText
                                            className="cge-content-inspiration-pro-title title"
                                            tagName="h2"
                                            placeholder="Saisir le titre du bloc"
                                            aria-describedby="cge-depanne-btn-title"
                                            value={title}
                                            onChange={onChangeTitle}
                                        />
                                    </div>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12">
                                    <h4>Textes</h4>
                                    <InnerBlocks template={BLOCKS_TEMPLATE} templateLock={false}/>
                                </div>
                            </div>
                            <div className="form-row">
                                <div className="col">
                                    <div className="form-group">
                                        <label>Text pied de page</label>
                                        <RichText
                                            className="cge-content-inspiration-pro-footer-text"
                                            tagName="p"
                                            placeholder="Saisir le titre du footer"
                                            aria-describedby="cge-content-inspiration-pro-footer-text"
                                            value={footer_text}
                                            onChange={onChangeFooterText()}
                                        />
                                    </div>
                                </div>
                            </div>
                            <div className="form-row">
                                <div className="col">
                                    <div className="form-group">
                                        <label>Texte dans le logo</label>
                                        <RichText
                                            className="form-control"
                                            placeholder="Saisir le titre du bouton"
                                            value={btnText}
                                            onChange={onChangeBtnText}
                                        />
                                    </div>
                                    <URLInput className="cge-content-page-brands-images-url"
                                              placeholder="Lien du bouton"
                                              value={btnUrl} onChange={onChangeBtn}/>
                                </div>
                            </div>
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
                mediaId,
                mediaUrl,
                mediaAlt,
                title,
                footer_text,
                btnUrl,
                btnText
            },
        } = props;

        return (
            <div
                className={'block block-inspiration__pro uk-width-1-1@s uk-width-1-2@m uk-flex uk-flex-right ' + className}>
                <div className="block-content uk-width-5-5">
                    <div className="block-header img-container img-middle">
                        {
                            mediaUrl && (
                                <img src={mediaUrl} alt={mediaAlt}
                                     className="cge-content-inspiration-pro-img-1" width="285" height="218"/>
                            )
                        }
                    </div>
                    <div className="block-body uk-width-4-5@m">
                        <div className="content">
                            <RichText.Content
                                className="cge-content-inspiration-pro-title title"
                                tagName="h2"
                                value={title}
                            />
                            <div className="summary">
                                <InnerBlocks.Content/>
                            </div>
                            <a className="readmore" href={btnUrl}>
                                {btnText}
                                <i className="icon icon-preview-arrow-right"/>
                            </a>
                        </div>
                    </div>
                    <div className="block-footer uk-width-4-5">
                        <RichText.Content
                            className="cge-content-inspiration-pro-footer-text"
                            tagName="p"
                            value={footer_text}
                        />
                    </div>
                </div>
            </div>
        );
    },
});
