const {__, setLocaleData} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {
    RichText,
    MediaUpload,
    InnerBlocks
} = wp.editor;
const {Button} = wp.components;

const BLOCKS_TEMPLATE = [
    ['core/paragraph', {}],
    ['core/list', {}],
];

registerBlockType('custom-gutenberg-elements/cge-content-bloc-type-1', {
    title: __('CGE: Content - Bloc type 1', 'custom-gutenberg-elements'),
    icon: 'index-card',
    category: 'formatting',
    attributes: {
        mediaId: {
            type: 'number',
        },
        mediaUrl: {
            type: 'string',
            source: 'attribute',
            selector: 'img',
            attribute: 'src',
        },
        mediaAlt: {
            type: 'string',
            source: 'attribute',
            selector: 'img',
            attribute: 'alt',
        },
        title: {
            type: 'string',
            source: 'html',
            selector: '.cge-content-bloc-type-1-title'
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
        const onChangeTitle = (url, post) => {
            setAttributes({title: url});
        };

        return (
            <div className={className + ' cge-bloc-container'}>
                <div className="alert alert-info alert-yellow">
                    <div className="cge-bloc-title">
                        <span className="title">CGE: Content - Bloc Type 1</span>
                    </div>
                    <div className="row">
                        <div className="col-sm-6 col-md-6 col-lg-6">
                            <div className="form-row">
                                <div className="col">
                                    <div className="form-group">
                                        <label>Titre du bloc</label>
                                        <RichText
                                            className="cge-content-bloc-type-1-title"
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
                                    <InnerBlocks template={BLOCKS_TEMPLATE} templateLock={false}/>
                                </div>
                            </div>
                        </div>
                        <div className="col-sm-6 col-md-6 col-lg-6">
                            <MediaUpload
                                onSelect={onSelectImage}
                                allowedTypes="image"
                                value={mediaId}
                                render={({open}) => (
                                    <Button className={mediaId ? 'image-button' : 'button button-large'} onClick={open}>
                                        {!mediaId ? __('Upload Image', 'custom-gutenberg-elements') :
                                            <img className="img-thumbnail" src={mediaUrl}
                                                 alt={mediaAlt}/>}
                                    </Button>
                                )}
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
                mediaId,
                mediaUrl,
                mediaAlt,
                title,
            },
        } = props;

        return (
            <div className={"block block-content__type1 uk-width-1-1 " + className}>
                <div className="block-content uk-grid uk-grid-small">
                    <div className="block-text uk-width-3-5@m">
                        <RichText.Content
                            className="cge-content-bloc-type-1-title title"
                            tagName="h2"
                            value={title}
                        />
                        <div className="summary rte">
                            <InnerBlocks.Content/>
                        </div>
                    </div>
                    <div className="block-image uk-width-2-5@m uk-flex uk-flex-middle">
                        {
                            mediaUrl && (
                                <img width="300" height="64" src={mediaUrl} alt={mediaAlt}/>
                            )
                        }
                    </div>
                </div>
            </div>
        );
    },
});
