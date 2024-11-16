<?php
/**
 * @param $original_id
 * @param $translation_id
 * @param string $lang_code
 * @param string $element_type
 */
function connect_translation_publication($original_id, $translation_id, $element_type = 'product', $lang_code = 'en')
{
    $lang_code = strtolower($lang_code);

    $inserted_post_ids = [
        'original' => $original_id,
        'translation' => $translation_id,
    ];


    if ($inserted_post_ids) {
        // https://wpml.org/wpml-hook/wpml_element_type/
        $wpml_element_type = apply_filters('wpml_element_type', $element_type);

        // get the language info of the original post
        // https://wpml.org/wpml-hook/wpml_element_language_details/
        $get_language_args = array('element_id' => $inserted_post_ids['original'], 'element_type' => $element_type);
        $original_post_language_info = apply_filters('wpml_element_language_details', null, $get_language_args);

        $set_language_args = array(
            'element_id' => $inserted_post_ids['translation'],
            'element_type' => $wpml_element_type,
            'trid' => $original_post_language_info->trid,
            'language_code' => $lang_code,
            'source_language_code' => $original_post_language_info->language_code
        );

        do_action('wpml_set_element_language_details', $set_language_args);
    }
}


/**
 * @param $current_lang
 *
 * @return string
 */
function get_import_product_lang($current_lang)
{
    if (!empty($current_lang)) {
        return trim(strtoupper($current_lang));
    }

    return 'FR';
}

/**
 * @param $refs
 *
 * @param string $lang
 *
 * @return array
 */
function get_products_ids_by_refs($refs, $lang = '')
{
    $color_variations = explode(',', trim($refs));
    $color_product_ids = [];
    foreach ($color_variations as $c_ref) {
        $c_ref = trim($c_ref);
        if (!empty($lang) && $lang != 'FR') {
            $c_ref = $c_ref . '-' . $lang;
        }
        $color_product_ids[] = custom_get_product_id_by_sku(trim($c_ref));
    }
    return $color_product_ids;
}

/**
 * @param $media_file
 *
 * @return string
 */
function get_import_media_name($media_file)
{
    $media_file = explode('.', $media_file);

    return trim(reset($media_file));
}

/**
 * Update import dotation mobilier
 *
 * @param $data
 *
 * @param int $product_id
 *
 * @return WC_Product
 */
function update_dotation_mobilier_import_csv($data, $product_id = null)
{
    $product = null;

    if (!empty($product_id)) {
        $product = new WC_Product_Dotation($product_id);

        /**
         * Insert mobilier complementaire
         */
        $quantity = (int)$data->quantite;
        $import_product_sku = (int)$data->mobiliers;
        $new_dotations = [];
        if (!empty($import_product_sku) && !empty($quantity)) {
            $current_mob = get_field('dotation_items', $product_id);
            if (!empty($current_mob)) {
                foreach ($current_mob as $c_mob) {
                    $dotation_item = $c_mob['dotation_item'];
                    $dotation_quantity = $c_mob['dotation_number'];
                    if (!empty($dotation_item) && $dotation_item instanceof WP_Post) {
                        $dotation_item_id = $dotation_item->ID;
                        $import_product_id = (int)custom_get_product_id_by_sku($import_product_sku);
                        if (!empty($import_product_id) && $dotation_item_id === $import_product_id) {
                            $dotation_quantity = $quantity;
                        }
                        $new_dotations[$dotation_item_id] = [
                            'dotation_item' => $dotation_item_id,
                            'dotation_number' => $dotation_quantity,
                        ];
                    }
                }
            }
            $dotation_item = custom_get_product_id_by_sku($import_product_sku);
            if (!empty($dotation_item)) {
                $new_dotations[$dotation_item] = [
                    'dotation_item' => $dotation_item,
                    'dotation_number' => $quantity,
                ];
            }
            if (!empty($new_dotations)) {
                update_field('dotation_items', $new_dotations, $product_id);
            }
        }
    }

    return $product;
}

/**
 * Create import dotation
 *
 * @param int $product_id
 * @param $data
 *
 * @return WC_Product
 * @throws WC_Data_Exception
 */
function make_dotation_import_csv($data, $product_id = null)
{
    $product = new WC_Product_Dotation($product_id);
    $product->set_name($data->Nom);
    $product->set_sku($data->Reference);
    $product->set_price(0);
    $product->set_regular_price(0);
    $product->set_stock_status('instock');
    $product->set_manage_stock(false);
    $product->set_status('publish');

    $product->save();

    $product_id = $product->get_id();

    update_post_meta($product_id, '_stock_status', 'instock');

    /*
     * Set Term data
     */
    if (!empty($data->Type_dotation)) {
        $types = explode(',', trim($data->Type_dotation));
        if (!empty($types) && is_array($types)) {
            $types_ids = [];
            foreach ($types as $type_name) {
                $type_name = trim($type_name);
                $types_ids[] = set_post_term_by_name($product_id, $type_name, 'product_dotation_type');
            }
            update_field('dotation_type', $types_ids, $product_id);
        }
    }

    // if attachment has many Uncategorized categorie, remove the default category
    $attachmentCategories = wp_get_object_terms($product_id, 'product_cat');
    if (count($attachmentCategories) > 1) {
        foreach ($attachmentCategories as $key => $category) {
            if ($category->name == 'Uncategorized') {
                wp_remove_object_terms($product_id, [$category->term_id], 'product_cat');
            }
        }
    }

    if (!empty($data->Surface_min)) {
        update_post_meta($product_id, 'dotation_surface_min', (int)$data->Surface_min);
    }
    if (!empty($data->Surface_max)) {
        update_post_meta($product_id, 'dotation_surface_max', (int)$data->Surface_max);
    }
    if (!empty($data->Groupe)) {
        update_post_meta($product_id, 'rent_group', $data->Groupe);
    }
    if ($data->Famille !== '') {
        update_post_meta($product_id, 'rent_family', $data->Famille);
    }

    /**
     * Insert product featured image
     */
    if (!empty($data->Img_Principale)) {
        $product_images = explode(',', trim($data->Img_Principale));
        $product_images_ids = [];
        foreach ($product_images as $p_img) {
            $p_img = trim($p_img);
            $img_name = get_import_media_name($p_img);
            $file_path = wp_get_upload_dir()['basedir'] . '/import_media/' . $p_img;
            $file_name = $product->get_name() . ' - Product-' . $img_name;
            $product_images_ids[] = insert_media_by_file($file_path, $file_name);
//            update_post_meta($product_id, '_product_image_gallery', implode(',', $product_images_ids));
            $single_attachment = reset($product_images_ids);
            if (!empty($single_attachment)) {
                set_post_thumbnail($product_id, $single_attachment);
            }
        }
    }

    if (!empty($data->quantite)) {
        update_field('dotation_add_limit', (int)$data->quantite, $product_id);
    }
    if (!empty($data->quantite)) {
        $text = __("Avec la dotation", THEME_TD) . ' ';
        $text .= $data->Nom;
        $text .= __(", vous avez la possibilité d'ajouter", THEME_TD) . ' ';
        $text .= $data->quantite;
        $text .= ' ' . __("mobiliers complémentaires en plus de ceux déjà fournis par défaut", THEME_TD);

        update_field('dotation_add_text', $text, $product_id);
    }
    /**
     * Insert mobilier complementaire
     */
    if (!empty($data->mobilier_complementaire)) {
        $other_product_ids = get_products_ids_by_refs($data->mobilier_complementaire);
        if (!empty($other_product_ids)) {
            $op_row = [];
            foreach ($other_product_ids as $key => $p_id) {
                if (!empty($p_id)) {
                    $op_row[] = [
                        'dotation_add_item' => $p_id,
                    ];
                }
            }
            update_field('dotation_add_items', $op_row, $product_id);
        }
    }

    return $product;
}

/**
 * Create import product
 *
 * @param int $product_id
 * @param $data
 *
 * @return WC_Product
 * @throws WC_Data_Exception
 */
function make_product_import_csv($data, $product_id = null)
{
    $lang = get_import_product_lang($data->language);
    $parent_lang = '';
    if ($lang != 'FR') {
        $parent_lang = 'FR';
    }

    /* For FR product */
    $new_product = true;
    if (!empty($product_id)) {
        $product = new WC_Product_Variable($product_id);
        $new_product = false;
    } else {
        $product = new WC_Product_Variable();
    }

    /* For other product lang */
    $parent_product_id = 0;
    if (!empty($parent_lang)) {
        $parent_product_id = custom_get_product_id_by_sku($data->Reference, strtolower($parent_lang));
        $ref = $data->Reference . '-' . $lang;
        $product_id = custom_get_product_id_by_sku($ref, strtolower($lang));
        if (!empty($product_id)) {
            $data->Reference = $ref;
        } else {
            $ref = $data->Reference;
            $product_id = custom_get_product_id_by_sku($ref, strtolower($lang));
        }

        if (!empty($product_id)) {
            $data->Reference = $ref;
            $product = new WC_Product_Variable($product_id);
            $new_product = false;
        } else {
            $product = new WC_Product_Variable();
        }
    }

    if (isset($data->Nom) && !empty($data->Nom)) {
        $product->set_name($data->Nom . ' ' . str_replace('-GB', '', $data->Reference));
    }
    if (isset($data->Reference) && !empty($data->Reference)) {
        $product->set_sku($data->Reference);
    }
    if (isset($data->{'Descriptif_' . $lang}) && !empty($data->{'Descriptif_' . $lang})) {
        $product->set_short_description($data->{'Descriptif_' . $lang});
    }
    if (!empty($data->Actif) && $data->Actif == 1) {
        $product->set_status('publish');
    } else {
        $product->set_status('draft');
    }
    $product->set_stock_status('instock');
    $product->set_manage_stock(false);

    $variation_city = [
        'Paris',
        'Region',
        'Paris_2023',
        'Region_2023',
        'Paris_2024',
        'Region_2024',
        'Event',
    ];

    $data->color = $data->{'Coloris_' . $lang};

    if (!empty($data->{'Coloris_' . $lang})) {
        $data->color = $data->{'Coloris_' . $lang};
    } else {
        $attribute_color = $product->get_attribute('pa_color');
        if (!empty($attribute_color)) {
            $the_color = explode(',', $attribute_color);
            $the_color = trim(reset($the_color));
            $data->color = $the_color;
        }
    }

//    if (empty($data->color)) {
//        $message = [
//            'product' => $product,
//            'message' => __("No color import", THEME_TD)
//        ];
//
//        wp_send_json_error($message);
//        die();
//    }

    if (!empty($data->color)) {
        $colors = explode(',', $data->color);
        $data->color = reset($colors);
        $colors_translate = [];
        if (!empty($parent_lang) && !empty($data->{'Coloris_' . $parent_lang})) {
            $colors_translate = explode(',', $data->{'Coloris_' . $parent_lang});
            $data->color_translate = reset($colors);
        }
        $product_attrs[] = prepare_product_attribute($product, 'color', $colors, $colors_translate);
    }
    $product_attrs[] = prepare_product_attribute($product, 'city', $variation_city);

    $product->set_attributes($product_attrs);
    $product->save();

    $product_id = $product->get_id();
    $data->parent_product_id = $product_id;
//
////    attachmentDeleteProductMediaSimilar($product_id);
//
    update_post_meta($product_id, '_stock_status', 'instock');
    wp_set_post_terms($product_id, 'instock', 'product_visibility', true);

    /*
     * Set Term data
     */
    // Category
    if (!empty($data->{'Categorie_Site_' . $lang})) {
        $category_translate = null;
        if (!empty($parent_lang) && !empty($data->{'Categorie_Site_' . $parent_lang})) {
            $category_translate = $data->{'Categorie_Site_' . $parent_lang};
        }
        import_remove_all_post_term($product_id, 'product_cat');
        set_post_term_by_name($product_id, trim($data->{'Categorie_Site_' . $lang}), 'product_cat', $category_translate);
    }
    // Subcategory
    if (!empty($data->{'Sous_Categorie_Site_' . $lang})) {
        $criteria = explode(',', trim($data->{'Sous_Categorie_Site_' . $lang}));
        $cat_criteria_translate = [];
        if (!empty($parent_lang) && !empty($data->{'Sous_Categorie_Site_' . $parent_lang})) {
            $cat_criteria_translate = explode(',', trim($data->{'Sous_Categorie_Site_' . $parent_lang}));
        }
        if (!empty($criteria) && is_array($criteria)) {
            foreach ($criteria as $key => $criterion) {
                $criterion = trim($criterion);
                $cat_criterion_translate = null;
                if (!empty($parent_lang) && !empty($cat_criteria_translate)) {
                    $cat_criterion_translate = $cat_criteria_translate[$key];
                }
                set_post_term_by_name($product_id, $criterion, 'product_cat', $cat_criterion_translate);
            }
        }
    }
    /**
     * Criteria
     */
    if (!empty($data->{'Criteres_' . $lang})) {
        $criteria = explode(',', trim($data->{'Criteres_' . $lang}));
        $criteria_translate = [];
        if (!empty($parent_lang) && !empty($data->{'Criteres_' . $parent_lang})) {
            $criteria_translate = explode(',', trim($data->{'Criteres_' . $parent_lang}));
        }
        if (!empty($criteria) && is_array($criteria)) {
            import_remove_all_post_term($product_id, 'product_tag');
            foreach ($criteria as $key => $criterion) {
                $criterion = trim($criterion);
                $criterion_translate = null;
                if (!empty($parent_lang) && !empty($criteria_translate)) {
                    $criterion_translate = $criteria_translate[$key];
                }
                set_post_term_by_name($product_id, $criterion, 'product_tag', $criterion_translate);
            }
        }
    }
    /*
     * Material
     */
    if (!empty($data->{'Matiere_' . $lang})) {
        $materials = explode(',', trim($data->{'Matiere_' . $lang}));
        $materials_translate = [];
        if (!empty($parent_lang) && !empty($data->{'Matiere_' . $parent_lang})) {
            $materials_translate = explode(',', trim($data->{'Matiere_' . $parent_lang}));
        }
        if (!empty($materials) && is_array($materials)) {
            $material_ids = [];
            import_remove_all_post_term($product_id, 'product_material');
            foreach ($materials as $key => $material_name) {
                $material_name = trim($material_name);
                $material_translate = null;
                if (!empty($parent_lang) && !empty($materials_translate)) {
                    $material_translate = $materials_translate[$key];
                }
                $material_ids[] = set_post_term_by_name($product_id, $material_name, 'product_material', $material_translate);
            }
            update_field('product_material', $material_ids, $product_id);
        }
    }

    // if attachment has many Uncategorized categorie, remove the default category
    $attachmentCategories = wp_get_object_terms($product_id, 'product_cat');
    if (count($attachmentCategories) > 1) {
        foreach ($attachmentCategories as $key => $category) {
            if ($category->name == 'Uncategorized') {
                wp_remove_object_terms($product_id, [$category->term_id], 'product_cat');
            }
        }
    }

    $product_options = [];
    if (!empty($data->{'Dimensions_' . $lang})) {
        $product_options[] = [
            'product_options_title' => __('Dimensions', THEME_TD),
            'product_options_desc' => $data->{'Dimensions_' . $lang},
        ];
    }
    if (!empty($data->Haut_Totale)) {
        $product_options[] = [
            'product_options_title' => __('Haut Totale', THEME_TD),
            'product_options_desc' => $data->Haut_Totale,
        ];
    }
    if (!empty($data->Pantone)) {
        $product_options[] = [
            'product_options_title' => __('Pantone', THEME_TD),
            'product_options_desc' => $data->Pantone,
        ];
    }
    if (!empty($product_options)) {
        update_field('product_options', $product_options, $product_id);
    }

    if (!empty($data->SketchFab)) {
        $sketchfab_link = 'https://sketchfab.com/3d-models/' . $data->Reference . '-' . strtolower($data->Nom) . '-' . $data->SketchFab;
        update_post_meta($product_id, 'product_3d_link', $sketchfab_link);
    }
    if (!empty($data->Groupe_Rent)) {
        update_post_meta($product_id, 'rent_group', $data->Groupe_Rent);
    }
    if ($data->Famille_Rent !== '' && $data->Famille_Rent !== null) {
        update_post_meta($product_id, 'rent_family', $data->Famille_Rent);
    }

    /**
     * Insert product featured image
     */
    if (!empty($data->Img_Principale)) {
        $product_images = explode(',', trim($data->Img_Principale));
        $product_images_ids = [];
        foreach ($product_images as $p_img) {
            $p_img = trim($p_img);
            $img_name = get_import_media_name($p_img);
            $file_path = wp_get_upload_dir()['basedir'] . '/import_media/' . $p_img;
            $file_name = $product->get_name() . ' - Product-' . $img_name;
            $product_images_ids[] = insert_media_by_file($file_path, $file_name);
            update_post_meta($product_id, '_product_image_gallery', implode(',', $product_images_ids));
            $single_attachment = reset($product_images_ids);
            if (!empty($single_attachment)) {
                set_post_thumbnail($product_id, $single_attachment);
            }
        }
    }

    /**
     * Insert product schema image
     */
    if (!empty($data->Img_Schema)) {
        $file_path = wp_get_upload_dir()['basedir'] . '/import_media/' . $data->Img_Schema;
        $img_name = get_import_media_name($data->Img_Schema);
        $file_name = $product->get_name() . ' - Schema-' . $img_name;
        $attachment_id = insert_media_by_file($file_path, $file_name);
        if (!empty($attachment_id)) {
            update_field('product_schema_img', $attachment_id, $product_id);
            update_post_meta($product_id, 'product_schema_img', $attachment_id);
        }
    }

    /**
     * Insert product gallery image
     */
    if (!empty($data->Img_Situation)) {
        $gallery_img = explode(',', trim($data->Img_Situation));
        $gallery_ids = [];
        foreach ($gallery_img as $g_img) {
            $g_img = trim($g_img);
            $file_path = wp_get_upload_dir()['basedir'] . '/import_media/' . $g_img;
            $img_name = get_import_media_name($g_img);
            $file_name = $product->get_name() . ' - Gallery-' . $img_name;
            $gallery_ids[] = insert_media_by_file($file_path, $file_name);
        }
        if (!empty($gallery_ids)) {
            update_field('product_gallery_expo', $gallery_ids, $product_id);
            update_post_meta($product_id, 'product_gallery_expo', $gallery_ids);
        }
    }

    if (!empty($data->Variantes_Coul)) {
        $color_product_ids = get_products_ids_by_refs($data->Variantes_Coul, $lang);
        if (!empty($color_product_ids)) {
            update_field('product_colors', $color_product_ids, $product_id);
        }
    }

    if (!empty($data->{'Produits_Lies+Sim'})) {
        $color_product_ids = get_products_ids_by_refs($data->{'Produits_Lies+Sim'}, $lang);
        if (!empty($color_product_ids)) {
            update_field('product_other_suggest', $color_product_ids, $product_id);
        }
    }


//    $_variations_id = $product->get_children();
//    if (!empty($_variations_id) && is_array($_variations_id)) {
//        foreach ($_variations_id as $variation_id) {
//            wp_delete_post($variation_id, true);
//        }
//    }
    /**
     * Set product variation data
     */
    if (!empty($data->color)) {
        if ($new_product) {
            foreach ($variation_city as $location) {
                $location_key = trim('Prix_' . $location);
                if (!empty($data->{$location_key})) {
                    $data->price = $data->{$location_key};
                    create_product_variation_import($data, $location, $lang);
                }
            }
            $data->product_import = 'New';
        } else {
            foreach ($variation_city as $location) {
                $location_key = trim('Prix_' . $location);
                if (!empty($data->{$location_key})) {
                    $data->price = $data->{$location_key};
                    $data->product = $product;
                    update_product_variation_import($data, $location, $lang);
                }
            }
            $data->product_import = 'Update';
        }
    } else {
        return false;
    }

    if (!empty($parent_lang)) {
        connect_translation_publication($parent_product_id, $product_id, 'product');
    }

    $data->product_link = get_permalink($product->get_id());

    // And finally (optionally if needed)
//    wc_delete_product_transients($product->get_id()); // Clear/refresh the variation cache

    return $data;
}

/**
 * @param $data
 * @param $location
 * @param $lang
 */
function create_product_variation_import($data, $location, $lang)
{
    try {
        create_product_variation($data->parent_product_id, array(
            'status' => 'publish',
            'type' => 'product_variation',
            'title' => $data->Nom . ' - ' . $location,
            'content' => '<p>' . $data->{'Descriptif_' . $lang} . '<p>',
            'excerpt' => $data->{'Descriptif_' . $lang},
            'regular_price' => $data->price, // product regular price
            'sale_price' => $data->price, // product sale price (optional)
            'stock_qty' => '', // Set a minimal stock quantity
            'image_id' => '', // optional
            'gallery_ids' => array(), // optional
            'sku' => '', // optional
            'tax_class' => '', // optional
            'weight' => '', // optional
            // For NEW attributes/values use NAMES (not slugs)
            'attributes' => array(
                'color' => trim($data->color),
                'city' => trim($location),
            ),
        ));
    } catch (Exception $e) {
        echo 'Exception reçue : ', $e->getMessage(), "\n";
    }
}

/**
 * Create a product variation for a defined variable product ID.
 *
 * @param int $product_id | Post ID of the product parent variable product.
 * @param array $variation_data | The data to insert in the product.
 *
 * @throws WC_Data_Exception
 * @since 3.0.0
 */

function create_product_variation($product_id, $variation_data)
{
    // Get the Variable product object (parent)
    $product = wc_get_product($product_id);

    $variation_post = array(
        'post_title' => $variation_data['title'],
        'post_name' => 'product-' . $product_id . '-variation',
        'post_status' => $variation_data['status'],
        'post_parent' => $product_id,
        'post_type' => $variation_data['type'],
        'guid' => $product->get_permalink()
    );

    // Creating the product variation
    $variation_id = wp_insert_post($variation_post);

    // Get an instance of the WC_Product_Variation object
    $variation = new WC_Product_Variation($variation_id);
    $variation->set_parent_id($product_id);

    // Iterating through the variations attributes

    foreach ($variation_data['attributes'] as $attribute_name => $term_name) {
        $taxonomy = 'pa_' . $attribute_name; // The attribute taxonomy
        $term_name = trim($term_name);

        if (!term_exists($term_name, $taxonomy)) {
            wp_insert_term($term_name, $taxonomy);
        } // Create the term
        if ($attribute_name == 'city') {
            $term_slug = get_term_by('slug', $term_name, $taxonomy)->slug;
        } else {
            $term_slug = get_term_by('name', $term_name, $taxonomy)->slug; // Get the term slug
        }
        // Set/save the attribute data in the product variation
        update_post_meta($variation_id, 'attribute_' . $taxonomy, $term_slug);
    }

    // SKU
    if (!empty($variation_data['sku'])) {
        $variation->set_sku($variation_data['sku']);
    }

    // Prices
    if (empty($variation_data['sale_price'])) {
        $variation->set_price($variation_data['regular_price']);
    } else {
        $variation->set_price($variation_data['sale_price']);
        $variation->set_sale_price($variation_data['sale_price']);
    }
    $variation->set_regular_price($variation_data['regular_price']);

    // Stock
    if (!empty($variation_data['stock_qty'])) {
        $variation->set_stock_quantity($variation_data['stock_qty']);
        $variation->set_manage_stock(true);
        $variation->set_stock_status('');
    } else {
        $variation->set_manage_stock(false);
    }

    $variation->set_weight(''); // weight (reseting)

    $variation->save(); // Save the data
}

/**
 * Create a product variation for a defined variable product ID.
 *
 * @param $data
 * @param $location
 * @param $lang
 *
 * @since 3.0.0
 */

function update_product_variation_import($data, $location, $lang)
{
    try {
        $product = $data->product;
        $product_id = $product->get_id();
        $product = wc_get_product($product_id);
        $variations = $product->get_available_variations();
        $variations_id = $product->get_children();
        $color = trim(strtolower($data->color));
        $location = trim(strtolower($location));
        $price = trim($data->price);

        if (!empty($variations_id) && is_array($variations_id)) {
            foreach ($variations_id as $variation_id) {
                $variation_city = get_post_meta($variation_id, 'attribute_pa_city', true);
                if ($variation_city == $location) {
                    wp_delete_post($variation_id, true);
                }
            }
        }

        $variations_id = [];

        if (!empty($variations_id) && is_array($variations_id)) {
            $have_variation_attr = false;
            foreach ($variations_id as $variation_id) {
                $variation_city = get_post_meta($variation_id, 'attribute_pa_city', true);
                if ($variation_city == $location) {
                    update_post_meta($variation_id, '_price', $price);
                    update_post_meta($variation_id, '_sale_price', $price);
                    update_post_meta($variation_id, '_regular_price', $price);
                    if (!empty($color)) {
                        update_post_meta($variation_id, 'attribute_pa_color', $color);
                    }
                    $have_variation_attr = true;

                    break;
                }
            }
            if ($have_variation_attr === false) {
                $empty_variation_attr = true;
                foreach ($variations_id as $variation_id) {
                    $variation_city = get_post_meta($variation_id, 'attribute_pa_city', true);
                    if (empty($variation_city)) {
                        update_post_meta($variation_id, 'attribute_pa_city', $location);
                        update_post_meta($variation_id, '_price', $price);
                        update_post_meta($variation_id, '_sale_price', $price);
                        update_post_meta($variation_id, '_regular_price', $price);
                        if (!empty($color)) {
                            update_post_meta($variation_id, 'attribute_pa_color', $color);
                        }
                        $empty_variation_attr = false;

                        break;
                    }
                }
                // create new variation with attribute
                if (!empty($empty_variation_attr)) {
                    create_product_variation_import($data, $location, $lang);
                }
            }
        } else {
            try {
                create_product_variation($data->parent_product_id, array(
                    'status' => 'publish',
                    'type' => 'product_variation',
                    'title' => $data->Nom . ' - ' . $location,
                    'content' => '<p>' . $data->{'Descriptif_' . $lang} . '<p>',
                    'excerpt' => $data->{'Descriptif_' . $lang},
                    'regular_price' => $data->price, // product regular price
                    'sale_price' => $data->price, // product sale price (optional)
                    'stock_qty' => '', // Set a minimal stock quantity
                    'image_id' => '', // optional
                    'gallery_ids' => array(), // optional
                    'sku' => '', // optional
                    'tax_class' => '', // optional
                    'weight' => '', // optional
                    // For NEW attributes/values use NAMES (not slugs)
                    'attributes' => array(
                        'color' => $data->color,
                        'city' => $location,
                    ),
                ));
            } catch (Exception $e) {
                echo 'Exception reçue : ', $e->getMessage(), "\n";
            }
        }
    } catch (Exception $e) {
        echo 'Exception reçue : ', $e->getMessage(), "\n";
    }
}

/**
 * @param $product
 * @param $attribute_name
 * @param $term_names
 *
 * @param array $term_translates
 *
 * @return WC_Product_Attribute
 */
function prepare_product_attribute($product, $attribute_name, $term_names, $term_translates = [])
{
    // Iterating through the variations attributes
    $taxonomy = 'pa_' . $attribute_name; // The attribute taxonomy
    $attribute_name = trim($attribute_name);

    // If taxonomy doesn't exists we create it (Thanks to Carl F. Corneil)
    if (!taxonomy_exists($taxonomy)) {
        register_taxonomy(
            $taxonomy,
            'product_variation',
            array(
                'hierarchical' => false,
                'label' => ucfirst($attribute_name),
                'query_var' => true,
                'rewrite' => array('slug' => sanitize_title($attribute_name)), // The base slug
            )
        );
    }

    // Check if the Term name exist and if not we create it.
    foreach ($term_names as $key => $term_name) {
        $term_name = trim($term_name);
        if (!empty($term_translates) && $attribute_name != 'city') {
            $translation_name = $term_translates[$key];
            if (!empty($translation_name) && !term_exists($translation_name, $taxonomy)) {
                wp_insert_term($translation_name, $taxonomy);
            }
        }
        if (!term_exists($term_name, $taxonomy)) {
            wp_insert_term($term_name, $taxonomy);
        }
        $term = get_term_by('name', $term_name, $taxonomy);

        if (!empty($term_translates) && !empty($translation_name) && $attribute_name != 'city') {
            $parent_translation = get_term_by('name', $translation_name, $taxonomy);
//            connect_translation_publication($parent_translation->term_id, $term->term_id, $taxonomy);
        }
        // Get the post Terms names from the parent variable product.
        $post_term_names = wp_get_post_terms($product->get_id(), $taxonomy, array('fields' => 'names'));

        // Check if the post term exist and if not we set it in the parent variable product.
        if (!in_array($term_name, $post_term_names)) {
            wp_set_post_terms($product->get_id(), $term_name, $taxonomy, true);
        }
    }

    // Add attribute to array, but don't set values.
    $attribute_object = new WC_Product_Attribute();
    $attribute_object->set_id(mt_rand(0, 999999));
    $attribute_object->set_name($taxonomy);
    $attribute_object->set_options($term_names);
    $attribute_object->set_position(1);
    $attribute_object->set_visible(1);
    $attribute_object->set_variation(1);

    return $attribute_object;
}

function import_remove_all_post_term($product_id, $taxonomy)
{
    $post_term_names = wp_get_post_terms($product_id, $taxonomy, array('fields' => 'ids'));
    if (!empty($post_term_names) && is_array($post_term_names)) {
        foreach ($post_term_names as $_term) {
            wp_remove_object_terms($product_id, $_term, $taxonomy);
        }
    }
}

/**
 * @param $product_id
 * @param $term_name
 * @param $taxonomy
 *
 * @param null $translation_name
 *
 * @return int
 */
function set_post_term_by_name($product_id, $term_name, $taxonomy, $translation_name = null)
{
    if (!empty($translation_name) && !term_exists($translation_name, $taxonomy)) {
        wp_insert_term($translation_name, $taxonomy);
    }
    if (!term_exists($term_name, $taxonomy)) {
        wp_insert_term($term_name, $taxonomy);
    }
    $term = get_term_by('name', $term_name, $taxonomy);

    if (!empty($translation_name)) {
        $parent_translation = get_term_by('name', $translation_name, $taxonomy);
//        connect_translation_publication($parent_translation->term_id, $term->term_id, $taxonomy);
    }

    // Get the post Terms names from the parent variable product.
    $post_term_names = wp_get_post_terms($product_id, $taxonomy, array('fields' => 'names'));

    // Check if the post term exist and if not we set it in the parent variable product.
    if (!in_array($term_name, $post_term_names) && !empty($term)) {
        wp_set_post_terms($product_id, [$term->term_id], $taxonomy, true);

        return $term->term_id;
    }
}

/**
 * @param $file
 *
 * @return int
 */
function get_import_attachment_id($file)
{

    $attachment_id = 0;

    $query_args = array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'fields' => 'ids',
        'meta_query' => [
            [
                'key' => '_wp_attached_file',
                'value' => $file,
                'compare' => 'LIKE',
            ]
        ]
    );

    $query = new WP_Query($query_args);

    if ($query->have_posts()) {
        $attachment_ids = $query->posts;
        sort($attachment_ids);

        return reset($attachment_ids);
    }

    return $attachment_id;
}

/**
 * @param $filename
 * @param $filepath
 * @param $parent_post_id
 *
 * @return bool|int|WP_Error
 */
function insert_media_by_file($filepath, $filename = '', $parent_post_id = 0)
{
    if (empty($filename)) {
        $filename = basename($filepath);
    }

    $img_name = basename($filepath);
    $img_name = get_import_media_name($img_name);
    $attachment_id = get_import_attachment_id($img_name);

    if (file_exists($filepath) && empty($attachment_id)) {
        $upload_file = wp_upload_bits(basename($filepath), null, file_get_contents($filepath));
        if (!$upload_file['error']) {
            $wp_filetype = wp_check_filetype($upload_file['file'], null);
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_parent' => $parent_post_id,
                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                'post_excerpt' => SITE_MAIN_SYS_NAME . ' Mobilier ' . preg_replace('/\.[^.]+$/', '', $filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $parent_post_id);
            if (!is_wp_error($attachment_id)) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
                $attachment_data['alt'] = get_the_title($parent_post_id) . ' ' . preg_replace('/\.[^.]+$/', '', $filename);
                wp_update_attachment_metadata($attachment_id, $attachment_data);

                return $attachment_id;
            }
        }
    } else {
        if (!empty($attachment_id)) {
            return $attachment_id;
        }
    }

    return false;
}


function attachmentGetSimilar($title)
{
    global $wpdb;

    $title = wc_clean($title);

    $table_name = $wpdb->prefix . 'posts';
    $query = "SELECT *  FROM `$table_name` WHERE `post_title` = '%s'";
    $sql = $wpdb->prepare($query, $title);
    $results = $wpdb->get_col($sql);

    if (!empty($results)) {
        return $results;
    }

    return false;
}

function attachmentGetProductImages($product_id)
{
    $all_images = [];
    $thumbnail = get_post_meta($product_id, '_thumbnail_id', true);
    $schema = get_post_meta($product_id, 'product_schema_img', true);
    $gallery = get_post_meta($product_id, '_product_image_gallery', true);

    if (!empty($thumbnail)) {
        $all_images[] = $thumbnail;
    }
    if (!empty($schema)) {
        $all_images[] = $schema;
    }
    if (!empty($gallery)) {
        $gallery = explode(',', $gallery);
        $all_images = array_merge($gallery, $all_images);
    }

    return $all_images;
}

function attachmentDeleteProductMediaSimilar($product_id)
{
    $post_en_id = wpml_object_id_filter($product_id, 'product', false, 'en');
    $all_images = attachmentGetProductImages($product_id);
    $all_images_en = attachmentGetProductImages($post_en_id);
    $all_images = array_merge($all_images, $all_images_en);
    $deleted_images = [];

    if (!empty($all_images)) {
        $file_path = get_template_directory() . '/logs/' . date('Y-m-d') . '-attahcment-deleted-ids.txt';
        if (!is_file($file_path)) {
            $contents = 'Log : ' . date('Y-m-d') . "\n";
            file_put_contents($file_path, $contents);
        }

        if (is_file($file_path)) {
            $file = fopen($file_path, 'a');
            foreach ($all_images as $img) {
                $thumbnail_post = get_post($img);
                $similar_list = attachmentGetSimilar($thumbnail_post->post_title);
                if (!empty($similar_list)) {
                    foreach ($similar_list as $item) {
                        if (!in_array($item, $all_images)) {
                            $deleted_images[] = $item;
                            wp_delete_attachment($item, true);

                            $log_title = 'pID : ' . $product_id . ' - Media : ' . $item . ' Date : ' . date('Y-m-d H:i:s', time());
                            fwrite($file, $log_title . "\n");
                        }
                    }
                }
            }
            fclose($file);
        }
    }

    return $deleted_images;
}

function attachmentDeleteDuplicate()
{
    $post_args = [
        'posts_per_page' => 20,
        'post_type' => 'product',
        'order' => 'ASC',
        'orderby' => 'name',
        'offset' => 1
    ];

    $posts = get_posts($post_args);

    $product_images = [];
    if (!empty($posts) && is_array($posts)) {
        foreach ($posts as $post) {
            $product_images [] = attachmentDeleteProductMediaSimilar($post->ID);
        }
    }

    return $product_images;
}


function importCopyAttachementProduct($reference, $to_lang = 'GB')
{
    $product_id = custom_get_product_id_by_sku($reference);
    $product_id_en = custom_get_product_id_by_sku($reference . '-' . $to_lang);

    if (!empty($product_id) && !empty($product_id_en)) {
        $thumbnail = get_post_meta($product_id, '_thumbnail_id', true);
        $thumbnail_en = get_post_meta($product_id_en, '_thumbnail_id', true);

        $thumbnails = get_post_meta($product_id, '_product_image_gallery', true);
        if (!empty($thumbnails)) {
            update_post_meta($product_id_en, '_product_image_gallery', implode(',', $thumbnails));
        }
        if (!empty($thumbnail)) {
            set_post_thumbnail($product_id_en, $thumbnail);
        }

        $gallery_ids = get_post_meta($product_id, 'product_gallery_expo', true);
        if (!empty($gallery_ids)) {
            update_field('product_gallery_expo', $gallery_ids, $product_id_en);
            update_post_meta($product_id_en, 'product_gallery_expo', $gallery_ids);
        }

        $schema = get_post_meta($product_id, 'product_schema_img', true);
        if (!empty($schema)) {
            update_field('product_schema_img', $schema, $product_id_en);
            update_post_meta($product_id_en, 'product_schema_img', $schema);
        }
    }
}

function importAddSuffixSkuProduct($lang = 'en')
{
    global $sitepress;

    $sitepress->switch_lang($lang);

    $products = wc_get_products([
        'posts_per_page' => '-1'
    ]);

    $array_ids = [];
    foreach ($products as $product) {
        $sku = $product->get_sku();
        if (strpos($sku, '-GB') !== false) {
            continue;
        } else {
            $new_sku = $sku . '-GB';
            $the_product = custom_get_product_id_by_sku($new_sku);
            if (empty($the_product)) {
                update_post_meta($product->get_id(), '_sku', $new_sku);
                $array_ids[] = $product->get_id();
            }
        }
    }

    return $array_ids;
}

function importRegenerateProductUrl($slug, $lang = 'fr')
{
    global $sitepress;

    $sitepress->switch_lang($lang);

    $sku = trim($slug);

    if (!empty($sku)) {
        $the_product_id = custom_get_product_id_by_sku($sku);
        $en_sku = $sku . '-GB';
        $the_product_id_en = custom_get_product_id_by_sku($en_sku);

        if (!empty($the_product_id) && !empty($the_product_id_en)) {
            $array_ids[] = $the_product_id;

            $slug_en = get_the_title($the_product_id_en) . '-' . $en_sku;
            wp_update_post([
                'ID' => $the_product_id_en,
                'post_name' => strtolower(wp_unique_post_slug($slug_en, $the_product_id_en, 'publish', 'product', 0)),
            ]);

            $slug_fr = get_the_title($the_product_id) . '-' . $sku;
            wp_update_post([
                'ID' => $the_product_id,
                'post_name' => strtolower(wp_unique_post_slug($slug_fr, $the_product_id, 'publish', 'product', 0)),
            ]);
        }
    }

    return $sku;
}

function custom_get_product_by_sku($sku, $lang = 'fr')
{
    global $wpdb;

    $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));

    if (!empty($product_id) && $lang != 'fr') {
        $product_id = getPostTranslatedID($product_id, $lang, 'product');
    }

    if ($product_id) return new WC_Product($product_id);

    return null;
}

function custom_get_product_id_by_sku($sku, $lang = 'fr')
{
    if ($lang == 'gb') {
        $lang = 'en';
    }
    $product = custom_get_product_by_sku($sku, $lang);
    if (!empty($product)) {
        return $product->get_id();
    }
    return false;
}