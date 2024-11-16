const {__, setLocaleData} = wp.i18n;
const {registerBlockType,} = wp.blocks;

registerBlockType('custom-gutenberg-elements/cge-content-breadcrumbs', {
    title: __('CGE: Bloc - Breadcrumbs', 'custom-gutenberg-elements'),
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
                    <span className="breadcrumb-item">Fil d'ariane</span>
                    <span className="breadcrumb-item">Rubrique</span>
                    <span className="breadcrumb-item">Publication</span>
                    <span className="breadcrumb-item active">Page</span>
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
