const {__, setLocaleData} = wp.i18n;
const {
    registerBlockType,
} = wp.blocks;
const {
    InnerBlocks,
    InspectorControls
} = wp.editor;
const {SelectControl} = wp.components;
const {Component} = wp.element;

const BLOCKS_TEMPLATE = [
    // ['custom-gutenberg-elements/cge-content-breadcrumbs', {}],
    // [ 'core/paragraph', { placeholder: 'Image Details' } ],
];

registerBlockType('custom-gutenberg-elements/cge-layout-content', {
    title: __('CGE: Layout - Content N1', 'custom-gutenberg-elements'),
    icon: 'index-card',
    category: 'layout',
    attributes: {
        type: {
            type: 'string',
        },
    },
    edit: (props) => {
        const {
            className,
            attributes: {
                type
            },
            setAttributes
        } = props;

        const onChangeSelectType = (value) => {
            setAttributes({type: value});
        };

        let options = [
            {value: '3', label: 'col-sm-10'},
            {value: '2', label: 'col-md-8'},
            {value: '1', label: 'col-md-6'},
        ];

        return (
            <div className={"section-left col-sm-12 " + className}>
                <InspectorControls key='inspector'>
                    <SelectControl onChange={onChangeSelectType}
                                   value={type}
                                   label={__('Choisir le type')} options={options}/>
                </InspectorControls>
                <div className="row">
                    <div className="block block-page__text col-md-10">
                        <div className="block-content">
                            <div className="block-body rte">
                                <InnerBlocks templateLock={false}/>
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
                type
            },
        } = props;

        let layout_class = '';

        if (type !== undefined) {
            if (type === '1') {
                layout_class = 'col-md-6 col-sm-8 col-md-offset-2 col-sm-offset-1 ';
            } else if (type === '2') {
                layout_class = 'col-md-8 col-sm-10 col-md-offset-2 col-sm-offset-1 ';
            } else if (type === '3') {
                layout_class = 'col-sm-10 col-sm-offset-1 ';
            }
        }

        return (
            <div className={layout_class + " " + className}>
                <div className="uk-grid-small" data-uk-grid>
                    <InnerBlocks.Content/>
                </div>
            </div>
        );
    }
});
