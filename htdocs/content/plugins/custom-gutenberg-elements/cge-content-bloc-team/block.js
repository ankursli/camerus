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


registerBlockType('custom-gutenberg-elements/cge-content-bloc-team', {
    title: __('CGE: Content - Bloc Team', 'custom-gutenberg-elements'),
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
        lastname: {
            type: 'string',
            source: 'html',
            selector: '.cge-content-bloc-team-lastname'
        },
        firstname: {
            type: 'string',
            source: 'html',
            selector: '.cge-content-bloc-team-firstname'
        },
        work: {
            type: 'string',
            source: 'html',
            selector: '.cge-content-bloc-team-work'
        },
    },
    edit: (props) => {
        const {
            className,
            attributes: {
                mediaId,
                mediaUrl,
                mediaAlt,
                lastname,
                firstname,
                work
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
        const onChangeLastName = (value) => {
            setAttributes({lastname: value});
        };
        const onChangeFirstName = (value) => {
            setAttributes({firstname: value});
        };
        const onChangeWork = (value) => {
            setAttributes({work: value});
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
                                    {!mediaId ? __('Upload Photo profil', 'custom-gutenberg-elements') :
                                        <img className="img-thumbnail" src={mediaUrl} alt={mediaAlt}/>}
                                </Button>
                            )}
                        />
                    </div>
                    <div className="col-sm-6 col-md-6 col-lg-6">
                        <div className="form-row">
                            <div className="col">
                                <div className="form-group">
                                    <label>Nom</label>
                                    <RichText
                                        tagName="span"
                                        className="cge-content-bloc-team-lastname form-control"
                                        placeholder="Saisir le nom"
                                        value={lastname}
                                        onChange={onChangeLastName}
                                    />
                                </div>
                                <div className="form-group">
                                    <label>Prénom</label>
                                    <RichText
                                        tagName="strong"
                                        className="cge-content-bloc-team-firstname form-control"
                                        placeholder="Saisir le prénom"
                                        value={firstname}
                                        onChange={onChangeFirstName}
                                    />
                                </div>
                                <div className="form-group">
                                    <label>Poste</label>
                                    <RichText
                                        tagName="strong"
                                        className="cge-content-bloc-team-work form-control"
                                        placeholder="Saisir le titre dans l'emplois"
                                        value={work}
                                        onChange={onChangeWork}
                                    />
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
                lastname,
                firstname,
                work
            },
        } = props;

        return (
            <div className={"block block-team__member uk-width-1-4@l uk-width-1-3@m uk-width-1-2@s " + className}>
                <div className="block-content">
                    <div className="block-body">
                        <figure className="img-container img-middle">
                            {
                                mediaUrl && (
                                    <img width="364" height="328" src={mediaUrl} alt={mediaAlt}/>
                                )
                            }
                        </figure>
                        <RichText.Content
                            className="cge-content-bloc-team-work title"
                            tagName="strong"
                            value={work}
                        />
                    </div>
                    <div className="block-footer">
                        <RichText.Content
                            className="cge-content-bloc-team-firstname firstname"
                            tagName="strong"
                            value={firstname}
                        />
                        <RichText.Content
                            className="cge-content-bloc-team-lastname lastname"
                            tagName="span"
                            value={lastname}
                        />
                    </div>
                </div>
            </div>
        );
    },
});
