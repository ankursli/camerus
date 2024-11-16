const {__, setLocaleData} = wp.i18n;
const {registerBlockType,} = wp.blocks;
const {RichText} = wp.editor;
const {TextareaControl} = wp.components;

registerBlockType('custom-gutenberg-elements/cge-full-bloc-contact', {
    title: __('CGE: Full - Bloc Contact', 'custom-gutenberg-elements'),
    icon: 'index-card',
    category: 'formatting',
    attributes: {
        title: {
            type: 'string',
        },
        desc: {
            type: 'string',
        },
    },
    edit: (props) => {
        const {
            className,
            attributes: {
                title,
                desc,
            },
            setAttributes,
        } = props;

        const onChangeTitle = (value) => {
            setAttributes({title: value});
        };
        const onChangeDesc = (value) => {
            setAttributes({desc: value});
        };

        return (
            <div className={"alert alert-info " + className} role="alert">
                <h3 className="alert-heading">Contactez-nous</h3>
                <div>
                    <label htmlFor="cge-full-bloc-contact-title">Titre</label>
                    <RichText
                        id="cge-full-bloc-contact-title"
                        className="cge-full-bloc-contact-title"
                        placeholder="Saisir le titre"
                        aria-describedby="cge-full-bloc-contact-title"
                        value={title}
                        onChange={onChangeTitle}
                    />
                </div>
                <div className="mb-0">
                    <TextareaControl
                        label="Ecrire une description"
                        className="textarea mb-0"
                        rows="4"
                        onChange={onChangeDesc}
                        value={desc}
                    />
                </div>
            </div>
        );
    },
    save: (props) => {
        return null
    },
});
