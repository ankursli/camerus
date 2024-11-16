const {__, setLocaleData} = wp.i18n;
const {registerBlockType,} = wp.blocks;

registerBlockType('custom-gutenberg-elements/cge-full-footer-social', {
    title: __('CGE: Full - Footer Social', 'custom-gutenberg-elements'),
    icon: 'index-card',
    category: 'formatting',
    attributes: {
        breadcrumbs: {
            type: 'array'
        }
    },
    edit: (props) => {
        const {
            className,
            attributes: {
                breadcrumbs
            },
            setAttributes,
        } = props;


        return (
            <div className={className}>
                <nav className="breadcrumb">
                    <span className="breadcrumb-item">Social Footer</span>
                    <span className="breadcrumb-item">Facebook</span>
                    <span className="breadcrumb-item">Twitter</span>
                    <span className="breadcrumb-item active">Instagram ...</span>
                </nav>
            </div>
        );
    },
    save: (props) => {
        const {
            className,
            attributes: {
                breadcrumbs
            },
        } = props;

        return (
            <div className={className + ' block block-page__breadcrumb col-xs-12'}>
                {breadcrumbs}
            </div>
        );
    },
});
