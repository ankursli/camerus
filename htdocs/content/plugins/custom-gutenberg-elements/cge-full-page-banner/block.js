const {__, setLocaleData} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {
    RichText,
    MediaUpload,
} = wp.editor;
const {Button} = wp.components;

registerBlockType('custom-gutenberg-elements/cge-full-page-banner', {
    title: __('CGE: Full - Banner page', 'custom-gutenberg-elements'),
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
    },
    edit: (props) => {
        const {
            className,
            attributes: {
                mediaId,
                mediaUrl,
                mediaAlt,
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

        return (
            <div className={className + ' cge-bloc-container'}>
                <div className="alert alert-info alert-pink">
                    <div className="cge-bloc-title">
                        <span className="title">CGE: Full - Page Banner</span>
                    </div>
                    <div className="col-sm-12 col-md-12 col-lg-12">
                        <MediaUpload
                            onSelect={onSelectImage}
                            allowedTypes="image"
                            value={mediaId}
                            render={({open}) => (
                                <Button className={mediaId ? 'image-button' : 'button button-large'} onClick={open}>
                                    {!mediaId ? __('Upload banner', 'custom-gutenberg-elements') :
                                        <img className="img-thumbnail" src={mediaUrl} alt={mediaAlt} data-uk-parallax="y:+1200"/>}
                                </Button>
                            )}
                        />
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
            },
        } = props;

        return (
            <div id="banner" className={"section container-fluid " + className}>
                <div className="section-header"/>
                <div className="section-body">
                    <div className="row">
                        <div className="col-md-12">
                            <div className="block block-banner__featured">
                                <div className="block-content">
                                    <div className="block-body">
                                        {
                                            mediaUrl && (
                                                <img width="1200" height="354" src={mediaUrl} alt={mediaAlt} data-uk-parallax="y:+1200"/>
                                            )
                                        }
                                    </div>
                                    <div className="block-footer"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="section-footer"/>
            </div>
        );
    },
});
