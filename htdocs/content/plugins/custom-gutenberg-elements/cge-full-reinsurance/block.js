const {__, setLocaleData} = wp.i18n;
const {registerBlockType,} = wp.blocks;

registerBlockType('custom-gutenberg-elements/cge-full-reinsurance', {
    title: __('CGE: Full - Réassurance', 'custom-gutenberg-elements'),
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
                    <span className="breadcrumb-item">Bloc Réassurance</span>
                    <span className="breadcrumb-item">UNE LOGISTIQUE PREMIUM</span>
                    <span className="breadcrumb-item">PAIEMENT 100% SÉCURISÉ</span>
                    <span className="breadcrumb-item active">SERVICE CLIENT A VOTRE ÉCOUTE</span>
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
