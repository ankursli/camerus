const {__, setLocaleData} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {
    RichText,
    URLInput,
    URLInputButton,
    MediaUpload,
} = wp.editor;
const {Button} = wp.components;
const {withState} = wp.compose;

const BLOCKS_TEMPLATE = [
    // ['custom-gutenberg-elements/cge-content-breadcrumbs', {}],
    // [ 'core/paragraph', { placeholder: 'Image Details' } ],
];


registerBlockType('custom-gutenberg-elements/cge-content-page-brands-images', {
    title: __('CGE: Content - Brands Images', 'custom-gutenberg-elements'),
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
        btnUrl: {
            type: 'string',
            selector: '.cge-content-page-brands-images-url',
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
        const onChangeBtn = (url, post) => {
            setAttributes({btnUrl: url});
        };
        const onChangeBtnText = (value) => {
            setAttributes({btnText: value});
        };

        return (
            <div className={className + ' container-fluid'}>
                <div className="row">
                    <div className="col-sm-6 col-md-6 col-lg-6">
                        <MediaUpload
                            onSelect={onSelectImage}
                            allowedTypes="image"
                            value={mediaId}
                            render={({open}) => (
                                <Button className={mediaId ? 'image-button' : 'button button-large'} onClick={open}>
                                    {!mediaId ? __('Upload Logo', 'custom-gutenberg-elements') :
                                        <img className="img-thumbnail" src={mediaUrl} alt={mediaAlt}/>}
                                </Button>
                            )}
                        />
                    </div>
                    <div className="col-sm-6 col-md-6 col-lg-6">
                        <div className="form-row">
                            <div className="col">
                                <div className="form-group d-none">
                                    <label>Texte dans le logo</label>
                                    <RichText
                                        tagName="h5"
                                        className="form-control"
                                        placeholder="Saisir le titre dans le logo"
                                        value={btnText}
                                        onChange={onChangeBtnText}
                                    />
                                </div>
                                <URLInput className="cge-content-page-brands-images-url" placeholder="Lien du logo"
                                          value={btnUrl} onChange={onChangeBtn}/>
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
                btnUrl,
                btnText
            },
        } = props;

        return (
            <li className={className}>
                <a href={btnUrl} title={btnText} target="_blank" rel="noopener noreferrer">
                    {
                        mediaUrl && (
                            <img width="110" height="72" src={mediaUrl} alt={mediaAlt}/>
                        )
                    }
                </a>
            </li>
        );
    },
});
