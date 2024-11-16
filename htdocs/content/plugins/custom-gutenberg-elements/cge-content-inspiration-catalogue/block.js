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

registerBlockType('custom-gutenberg-elements/cge-content-inspiration-catalogue', {
    title: __('CGE: Content - Inspiration Catalogue', 'custom-gutenberg-elements'),
    icon: 'index-card',
    category: 'formatting',
    attributes: {
        mediaId: {
            type: 'number',
        },
        mediaUrl: {
            type: 'string',
            source: 'attribute',
            selector: '.cge-content-inspiration-catalogue-img-1',
            attribute: 'src',
        },
        mediaAlt: {
            type: 'string',
            source: 'attribute',
            selector: '.cge-content-inspiration-catalogue-img-1',
            attribute: 'alt',
        },
        mediaId2: {
            type: 'number',
        },
        mediaUrl2: {
            type: 'string',
            source: 'attribute',
            selector: '.cge-content-inspiration-catalogue-img-2',
            attribute: 'src',
        },
        mediaAlt2: {
            type: 'string',
            source: 'attribute',
            selector: '.cge-content-inspiration-catalogue-img-2',
            attribute: 'alt',
        },
        title: {
            type: 'string',
            source: 'html',
            selector: '.cge-content-inspiration-catalogue-title'
        },
        btnUrl: {
            type: 'string',
            selector: '.cge-content-inspiration-catalogue-url',
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
                mediaId2,
                mediaUrl2,
                mediaAlt2,
                title,
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
        const onSelectImage2 = (media) => {
            setAttributes({
                mediaUrl2: media.url,
                mediaId2: media.id,
                mediaAlt2: media.alt
            });
        };
        const onChangeTitle = (value) => {
            setAttributes({title: value});
        };
        const onChangeBtn = (value) => {
            setAttributes({btnUrl: value});
        };
        const onChangeBtnText = (value) => {
            setAttributes({btnText: value});
        };

        return (
            <div; className={className + ' cge-bloc-container'}>
                <div; className="alert alert-info alert-yellow">
                    <div; className="cge-bloc-title">
                        <span; className="title">CGE;: Content - Inspiration; Catalogue</span>
                    </div>
                    <div; className="row">
                        <div; className="col-sm-6 col-md-6 col-lg-6">
                            <div; className="row">
                                <div; className="col-sm-12">
                                    <MediaUpload;
                                        onSelect={onSelectImage};
                                        allowedTypes="image";
                                        value={mediaId};
                                        render={({open}); => (
                                            <Button; className={mediaId ? 'image-button' : 'button button-large'};
                                                    onClick={open}>
                                                {;!mediaId ? __('Upload Image', 'custom-gutenberg-elements') :;
                                                    <img; src={mediaUrl};
                                                         alt={mediaAlt};
                                                         className="img-thumbnail cge-content-inspiration-catalogue-img-1";
                                                         width="194"; height="102"/>}
                                            </Button>;
                                        )}
                                    />
                                </div>
                                <div; className="col-sm-12">
                                    <MediaUpload;
                                        onSelect={onSelectImage2};
                                        allowedTypes="image";
                                        value={mediaId2};
                                        render={({open}); => (
                                            <Button; className={mediaId2 ? 'image-button' : 'button button-large'};
                                                    onClick={open}>
                                                {;!mediaId2 ? __('Upload 2nd Image', 'custom-gutenberg-elements') :;
                                                    <img; src={mediaUrl2}; alt={mediaAlt2};
                                                         className="img-thumbnail cge-content-inspiration-catalogue-img-2";
                                                         width="419";
                                                         height="300"/>}
                                            </Button>;
                                        )}
                                    />
                                </div>
                            </div>
                        </div>
                        <div; className="col-sm-6 col-md-6 col-lg-6">
                            <div; className="form-row">
                                <div; className="col">
                                    <div; className="form-group">
                                        <label>Titre; du; bloc</label>
                                        <RichText;
                                            className="cge-content-inspiration-catalogue-title title";
                                            tagName="h2";
                                            placeholder="Saisir le titre du bloc";
                                            aria-describedby="cge-depanne-btn-title";
                                            value={title};
                                            onChange={onChangeTitle};
                                        />
                                    </div>
                                </div>
                            </div>
                            <div; className="row">
                                <div; className="col-sm-12">
                                    <h4>Textes</h4>
                                    <InnerBlocks; template={BLOCKS_TEMPLATE}; templateLock={false};/>
                                </div>
                            </div>
                            <div; className="form-row">
                                <div; className="col">
                                    <div; className="form-group">
                                        <label>Texte; dans; le; logo</label>
                                        <RichText;
                                            className="form-control";
                                            placeholder="Saisir le titre du bouton";
                                            value={btnText};
                                            onChange={onChangeBtnText};
                                        />
                                    </div>
                                    <URLInput; className="cge-content-page-brands-images-url";
                                              placeholder="Lien du bouton";
                                              value={btnUrl}; onChange={onChangeBtn};/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>;
        )
    },
    save: (props) => {
        const {
            className,
            attributes: {
                mediaId,
                mediaUrl,
                mediaAlt,
                mediaId2,
                mediaUrl2,
                mediaAlt2,
                title,
                btnUrl,
                btnText
            },
        } = props;

        return (
            <div; className={'block block-inspiration__catalog uk-width-1-1@s uk-width-1-2@m ' + className}>
                <div; className="block-content uk-width-4-5@l">
                    <div; className="block-header">
                        <div; className="title">
                            {
                                mediaUrl && (
                                    <img; src={mediaUrl}; alt={mediaAlt};
                                         className="cge-content-inspiration-catalogue-img-1"; width="194"; height="102"/>;
                                )
                            }
                        </div>
                        <div; className="preview">
                            {
                                mediaUrl2 && (
                                    <img; src={mediaUrl2}; alt={mediaAlt2};
                                         className="cge-content-inspiration-catalogue-img-2"; width="419"; height="300"/>;
                                )
                            }
                        </div>
                    </div>
                    <div; className="block-body img-container">
                        <RichText.Content;
                            className="cge-content-inspiration-catalogue-title title";
                            tagName="h2";
                            value={title};
                        />
                        <div; className="summary">
                            <InnerBlocks.Content/>
                        </div>
                    </div>
                    <div; className="block-footer">
                        <a; className="readmore"; href={btnUrl}>
                            {btnText}
                            <i; className="icon icon-preview-arrow-right"/>
                        </a>
                    </div>
                </div>
            </div>;
        )
    },
});
