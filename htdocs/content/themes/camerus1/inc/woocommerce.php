<?php
/**
 * Disable WooCommerce template loader
 */

use App\Hooks\Salon;
use App\Library\Services\RentOrderManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\Filter;

/**
 * init session if not start
 */
Action::add('init', function () {
    if (!session_id()) {
        session_start();
    }
}, 20);

/**
 * Check Salon before add to cart
 */
//Filter::add('woocommerce_add_to_cart_validation', function ($passed, $product_id, $quantity, $variation_id = '', $variations = '') {
//    $is_current_salon = false;
//    $current_salon_slug = getEventSalonSlugInSession();
//    $product_salons = get_field('product_salon', $product_id);
//
//    if (!empty($current_salon_slug) && !empty($product_salons) && is_array($product_salons)) {
//        foreach ($product_salons as $salon) {
//            if ($salon->post_name === $current_salon_slug) {
//                $is_current_salon = true;
//                break;
//            }
//        }
//    }
//
//    if ($is_current_salon) {
//        return $passed;
//    } elseif (empty($current_salon_slug) && !empty($product_salons)) {
//        addEventSalonSlugToSession(SLUG_EVENT_SALON_QUERY, $product_salons[0]->post_name);
//        $msg = __("Merci d'avoir choisie le salon", THEME_TD).': "'.$product_salons[0]->post_title.'"';
//        wc_add_notice($msg, 'success');
//
//        return $passed;
//    } elseif (!empty($current_salon_slug)) {
//        $msg = __("Ce produit n'est pas disponible dans votre salon actuel", THEME_TD).': '.$product_salons[0]->post_title;
//        wc_add_notice($msg, 'error');
//    } else {
//        $msg = __("Ce produit n'a pas de salon, impossible de l'ajouter", THEME_TD);
//        wc_add_notice($msg, 'error');
//    }
//}, 10, 5);

/**
 * Add cart item data : Salon info
 */
Filter::add('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id) {
    global $woocommerce;

    if (isEventSalonSession()) {
        return $cart_item_data;
    }

    $salon_slug = getEventSalonSlugInSession();

    if (!empty($salon_slug)) {
        $new_value = array(SLUG_EVENT_SALON_QUERY => $salon_slug);

        if (empty($cart_item_data)) {
            return $new_value;
        } else {
            return array_merge($cart_item_data, $new_value);
        }
    }
});

/**
 * Add cart item data : Event info
 */
Filter::add('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id) {
    global $woocommerce;

    $slug = getEventSalonCitySlugInSession();

    if (!empty($slug)) {
        $new_value = array('event_type' => $slug);

        if (empty($cart_item_data)) {
            return $new_value;
        } else {
            return array_merge($cart_item_data, $new_value);
        }
    }

    return $cart_item_data;
});

/**
 * Remove Salon info to item cart on delete
 */
Filter::add('woocommerce_before_cart_item_quantity_zero', function ($cart_item_key) {
    global $woocommerce;

    $cart = $woocommerce->cart->get_cart();

    foreach ($cart as $key => $values) {
        if (array_key_exists(SLUG_EVENT_SALON_QUERY, $values) && $values[SLUG_EVENT_SALON_QUERY] == $cart_item_key) {
            unset($woocommerce->cart->cart_contents[$key]);
        }
    }
});

/**
 * Load salon from cart item data session
 */
Filter::add('woocommerce_cart_loaded_from_session', function () {
    global $woocommerce;

    if (!empty($woocommerce->cart->cart_contents) && empty(getEventSalonCitySlugInSession())) {
        foreach ($woocommerce->cart->cart_contents as $key => $item) {
            if (array_key_exists(SLUG_EVENT_SALON_QUERY, $item)) {
                $current_salon = $item[SLUG_EVENT_SALON_QUERY];
                addEventSalonSlugToSession(SLUG_EVENT_SALON_QUERY, $current_salon);
                break;
            }
        }
    }
});

/**
 * Load event_type from cart item data session
 */
Filter::add('woocommerce_cart_loaded_from_session', function () {
    global $woocommerce;

    if (!empty($woocommerce->cart->cart_contents) && empty(getEventSalonCitySlugInSession())) {
        foreach ($woocommerce->cart->cart_contents as $key => $item) {
            if (array_key_exists(SLUG_EVENT_SALON_QUERY, $item) && !empty($item[SLUG_EVENT_SALON_QUERY]) && array_key_exists('event_type', $item)) {
                $event_type = $item['event_type'];
                if (checkEventSalonCitySlugToSession($event_type)) {
                    setEventSalonCitySlugToSession($event_type);
                }
                break;
            }
        }
    }
});

/**
 * check product event_type from cart item data session
 */
Filter::add('woocommerce_before_cart', function () {
    global $woocommerce;

    if (!empty($woocommerce->cart->cart_contents)) {
        $event_type = getEventSalonCitySlugInSession();
        foreach ($woocommerce->cart->cart_contents as $key => $item) {
            $variation_id = $item['variation_id'];
            $pr_event_type = get_post_meta($variation_id, 'attribute_pa_city', true);
            if (!empty($pr_event_type) && $pr_event_type !== $event_type) {
                $woocommerce->cart->remove_cart_item($key);
            }
        }
    }
});

/**
 * Add salon to cart item data
 */
Filter::add('woocommerce_get_cart_item_from_session', function ($item, $values, $key) {
    if (array_key_exists(SLUG_EVENT_SALON_QUERY, $values)) {
        $item[SLUG_EVENT_SALON_QUERY] = $values[SLUG_EVENT_SALON_QUERY];
    }
    return $item;
});

/**
 * Add Salon info to order
 */
Action::add('woocommerce_add_order_item_meta', function ($item_id, $values) {
    global $woocommerce, $wpdb;

    if (isEventSalonSession()) {
        return;
    }

    if (array_key_exists(SLUG_EVENT_SALON_QUERY, $values)) {
        $user_custom_values = $values[SLUG_EVENT_SALON_QUERY];
        if (! empty($user_custom_values)) {
            $salon_id = getPostIdBySlug($user_custom_values);
            if (! empty($salon_id)) {
                $salon = get_post($salon_id);
                wc_add_order_item_meta($item_id, 'Salon', $salon->post_title);
            } else {
                wc_add_order_item_meta($item_id, 'Salon', $user_custom_values);
            }
        }
    }
}, 10, 2);

/**
 * Custom related product
 */
//Filter::add('woocommerce_product_related_posts_query', function ($query) {
//    global $wpdb;
//
//    $current_salon_slug = getEventSalonSlugInSession();
//    $current_salon = getPostIdBySlug($current_salon_slug);
//
//    $query['join'] .= " INNER JOIN {$wpdb->postmeta} as pm ON p.ID = pm.post_id ";
//    $query['where'] .= " AND pm.meta_key = 'product_salon' AND meta_value LIKE '{$current_salon}' ";
////    dd($query);
//
//    return $query;
//}, 10, 3);

Filter::add('woocommerce_output_related_products_args', function ($args) {
    $current_salon_slug = getEventSalonSlugInSession();
    $current_salon = getPostIdBySlug($current_salon_slug);
    $custom_args = [
        'meta_query' => [
            [
                'key' => 'product_salon',
                'value' => (string)$current_salon,
                'compare' => 'LIKE'
            ]
        ]
    ];
    $args = wp_parse_args($custom_args, $args);
    $args['posts_per_page'] = 10;
//    dd($args);

    return $args;
});

/**
 * Change number of related products output
 */
Filter::add('woocommerce_output_related_products_args', function ($args) {
    $args['posts_per_page'] = 4; // 4 related products

    return $args;
}, 20);

/**
 * @author Rv
 * Add features product in cart
 */

//Filter::add('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id) {
//    $ref_product = rand(1000, 10000);
//    if (get_field('reference', $product_id) != "") {
//        $ref_product = get_field('reference', $product_id);
//    }
//
//    $cart_item_data['reference'] = $ref_product;
//
//    return $cart_item_data;
//});

//Filter::add('woocommerce_get_item_data', function ($item_data, $cart_item) {
//
//    if (empty($cart_item['reference'])) {
//        return $item_data;
//    }
//
//    $item_data[] = array(
//        'key'     => __('reference'),
//        'value'   => wc_clean($cart_item['reference']),
//        'display' => '',
//    );
//    return $item_data;
//});

/**
 * @author Rv
 * To insert Salon data with Order
 */
Action::add('woocommerce_new_order', function ($order_id) {

    if (isset($_POST['_event-slug'])) {
        update_field('slug_evenement', sanitize_text_field($_POST['_event-slug']), $order_id);
    }
    if (isset($_POST['_event-name'])) {
        update_field('nom_evenement', sanitize_text_field($_POST['_event-name']), $order_id);
    }
    if (isset($_POST['_event-place'])) {
        update_field('lieu_evenement', sanitize_text_field($_POST['_event-place']), $order_id);
    }
    if (isset($_POST['_event-date'])) {
        update_field('date_evenement', sanitize_text_field($_POST['_event-date']), $order_id);
    }
    if (isset($_POST['_event-end-date'])) {
        update_field('date_fin_evenement', sanitize_text_field($_POST['_event-end-date']), $order_id);
    }
    if (isset($_POST['_event-city'])) {
        update_field('ville_evenement', sanitize_text_field($_POST['_event-city']), $order_id);
    }
    if (isset($_POST['_event-stand'])) {
        update_field('nom_stand', sanitize_text_field($_POST['_event-stand']), $order_id);
    }
    if (isset($_POST['_event-hall'])) {
        update_field('hall_stand', sanitize_text_field($_POST['_event-hall']), $order_id);
    }
    if (isset($_POST['_event-wing'])) {
        update_field('allee_stand', sanitize_text_field($_POST['_event-wing']), $order_id);
    }
    if (isset($_POST['_event-number'])) {
        update_field('numero_de_stand', sanitize_text_field($_POST['_event-number']), $order_id);
    }
    $event_type = getEventSalonCitySlugInSession();
    if (!empty($event_type)) {
        update_field('event_type', sanitize_text_field($event_type), $order_id);
    } elseif (isset($_POST['_event-type'])) {
        addEventSalonCitySlugToSession(SLUG_EVENT_CITY_QUERY, sanitize_text_field(trim($_POST['_event-type'])));
        update_field('event_type', sanitize_text_field(getEventSalonCitySlugInSession()), $order_id);
    }
});

/**
 * @author Rv
 * save Extra field in DB
 *
 */

Action::add('woocommerce_checkout_update_order_meta', function ($order_id) {
    if (isset($_POST['billing_genre'])) {
        update_post_meta($order_id, 'billing_genre', sanitize_text_field($_POST['billing_genre']));
    }

    if (isset($_POST['billing_accounting_email'])) {
        update_post_meta($order_id, 'billing_accounting_email', sanitize_text_field($_POST['billing_accounting_email']));
    }

    if (isset($_POST['billing_dematerialized_invoice'])) {
        update_post_meta($order_id, 'billing_dematerialized_invoice', sanitize_text_field($_POST['billing_dematerialized_invoice']));
    }
});


/**
 * @author Rv
 * set field billing attr
 */

Filter::add('woocommerce_form_field_args', function ($args, $key, $value = null) {

    /*********************************************************************************************/
    /**
     *
     * $defaults = array(
     * 'type'              => 'text',
     * 'label'             => '',
     * 'description'       => '',
     * 'placeholder'       => '',
     * 'maxlength'         => false,
     * 'required'          => false,
     * 'id'                => $key,
     * 'class'             => array(),
     * 'label_class'       => array(),
     * 'input_class'       => array(),
     * 'return'            => false,
     * 'options'           => array(),
     * 'custom_attributes' => array(),
     * 'validate'          => array(),
     * 'default'           => '',
     * );
     * /*********************************************************************************************/

// Start field type switch case

    switch ($args['type']) {

        case "select" :  /* Targets all select input type elements, except the country and state select input types */
            $args['class'][]
                = 'form-group'; // Add a class to the field's html element wrapper - woocommerce input types (fields) are often wrapped within a <p></p> tag
            $args['input_class'] = array('form-control', 'input-lg'); // Add a class to the form input itself
            //$args['custom_attributes']['data-plugin'] = 'select2';
            $args['label_class'] = array('control-label');
            $args['custom_attributes'] = array(
                'data-plugin' => 'select2',
                'data-allow-clear' => 'true',
                'aria-hidden' => 'true',
            ); // Add custom data attributes to the form input itself
            break;

        case 'country' : /* By default WooCommerce will populate a select with the country names - $args defined for this specific input type targets only the country select element */
            $args['class'][] = 'form-group single-country';
            $args['label_class'] = array('control-label');
            $args['input_class'] = array('');
            //$args['priority'] = 110;
            break;

        case "state" : /* By default WooCommerce will populate a select with state names - $args defined for this specific input type targets only the country select element */
            $args['class'][] = 'form-group'; // Add class to the field's html element wrapper
            $args['input_class'] = array('form-control', 'input-lg'); // add class to the form input itself
            //$args['custom_attributes']['data-plugin'] = 'select2';
            $args['label_class'] = array('control-label');
            $args['custom_attributes'] = array('data-plugin' => 'select2', 'data-allow-clear' => 'true', 'aria-hidden' => 'true',);
            break;


        case "password" :
        case "text" :
            if ($args['id'] == 'billing_company') {
                $args['priority'] = 8;
                $args['class'][] = 'form-row-first';
                $args['label'] = __('Nom de la société', THEME_TD);
                $args['required'] = true;
            }
            if ($args['id'] == 'billing_address_1') {
                //$args['priority'] = 20;
                $args['label'] = __('Adresse complète', THEME_TD);
            }
            if ($args['id'] == 'billing_postcode') {
                //$args['priority'] = 30;
                $args['label'] = __('Code Postal', THEME_TD);
            }
            if ($args['id'] == 'billing_genre') {
                $args['priority'] = 9;
                $args['class'][] = 'form-row-first';
                $args['label'] = __('Civilité', THEME_TD);
            }
            if ($args['id'] == 'billing_last_name') {
                //$args['priority'] = 50;
                $args['label'] = __('Nom', THEME_TD);
            }
            if ($args['id'] == 'billing_first_name') {
                //$args['priority'] = 60;
                $args['label'] = __('Prénom', THEME_TD);
            }
//            if ($args['id'] == 'billing_num_tva') {
//                $args['priority'] = 120;
//                $args['label'] = 'N°TVA';
//            }

        case "email" :
            //dd($args);
            if ($args['id'] == 'billing_email') {
                //$args['priority'] = 70;
                $args['label'] = __('Adresse mail', THEME_TD);
            }
        case "tel" :
            //$args['priority'] = 80;
            //$args['label'] = 'Téléphone';
        case "number" :
            $args['class'][] = 'form-group';
            //$args['input_class'][] = 'form-control input-lg'; // will return an array of classes, the same as bellow
            $args['input_class'] = array('form-control', 'input-lg');
            $args['label_class'] = array('control-label');
            break;
        case 'textarea' :
            $args['input_class'] = array('form-control', 'input-lg');
            $args['label_class'] = array('control-label');
            break;

        case 'checkbox' :
            break;

        case 'radio' :
            if ($args['id'] == 'billing_num_tva') {
                //$args['priority'] = 100;
            }
            break;
        default :
            $args['class'][] = 'form-group';
            $args['input_class'] = array('form-control', 'input-lg');
            $args['label_class'] = array('control-label');
            break;
    }

    return $args;
});

/**
 * User approved
 */
Action::add('woocommerce_checkout_order_processed', function ($order_id, $posted_data, $order) {
    if (!$order_id) {
        return;
    }

    $customer_id = $order->get_customer_id();
    if (!empty($customer_id)) {
        update_user_meta($customer_id, 'wp-approve-user', true);
    }
}, 20, 3);

/**
 * Creat XML file and send Rent+ import after order processed
 */
//Action::add('woocommerce_order_status_completed', function ($order_id) {
//    $order = wc_get_order($order_id);
//    if (!empty($order)) {
//        $payment_type = $order->get_payment_method();
//        if ($payment_type == 'systempaystd') {
//            (new RentOrderManager)->triggerXmlOrder($order);
//        }
//    }
//});

Action::add('woocommerce_order_status_on-hold', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!empty($order)) {
        $payment_type = $order->get_payment_method();
        if ($payment_type === 'bacs' || $payment_type === 'cheque' || $payment_type == 'systempaystd') {
            (new RentOrderManager)->triggerXmlOrder($order);
        }
    }
});

Action::add('woocommerce_order_status_processing', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!empty($order)) {
        $have_dotation = false;
        $payment_type = $order->get_payment_method();
        $line_items = $order->get_items();

        foreach ($line_items as $item) {
            $product = $item->get_product();
            if ($product->is_type('dotation')) {
                $have_dotation = true;
            }
        }

        if (empty($payment_type) && $have_dotation) {
            (new RentOrderManager)->triggerXmlOrder($order);
        }
        if (empty($payment_type) && !$have_dotation) {
            (new RentOrderManager)->triggerXmlOrder($order);
        }
    }
});

/**
 * Creat JSON data after order processed
 */
Action::add('woocommerce_checkout_order_processed', function ($order_id, $posted_data, $order) {
    if (!$order_id) {
        return;
    }
//    if (isProCustomer()) {
//        return;
//    }

    $reed_data = getReedDataInfo();

    if (!empty($order) && !empty($reed_data)) {
        $datas = [];

        $price = '0,00 €';

        $order_items = $order->get_items();
        $items = [];
        $total_quantity = 0;
        $dotation_items_added = [];

        foreach ($order_items as $item) {
            $product = $item->get_product();

            if ($product->is_type('dotation')) {


                $dotation_items = get_field('dotation_items', $product->get_id());

                if (!empty($dotation_items) && is_array($dotation_items)) {

                    $meta_datas = $item->get_meta_data();
                    if (!empty($meta_datas) && is_array($meta_datas)) {
                        foreach ($meta_datas as $meta_data) {
                            $m_data = $meta_data->get_data();
                            if (array_key_exists('key', $m_data) && $m_data['key'] === SLUG_REED_PRODUCT_DOTATION) {
                                foreach ($m_data['value'] as $pr) {
                                    $dotation_items_added[] = [
                                        'dotation_item' => get_post($pr['product_id']),
                                        'dotation_number' => $pr['quantity'],
                                    ];
                                }
                                break;
                            }
                        }
                    }

                    if (!empty($dotation_items_added)) {
                        $dotation_items = array_merge($dotation_items, $dotation_items_added);
                    }

                    foreach ($dotation_items as $key => $dotationItem) {
                        $d = $dotationItem['dotation_item'];
                        $product_sku = get_post_meta($d->ID, '_sku', true);
                        $quantity = $dotationItem['dotation_number'];
                        $total_quantity = $total_quantity + $quantity;

                        $items[$key]['Nom_Dotation'] = $product->get_title();
                        $items[$key]['Code_Dotation'] = $product->get_sku();
                        $items[$key]['Nom_produit'] = $d->post_title;
                        $items[$key]['Statut_produit'] = __('Commandé', THEME_TD);
                        $items[$key]['Couleur_Produit'] = '';
                        $items[$key]['Dimension_Produit'] = '';
                        $items[$key]['Ref_produit'] = $product_sku;
                        $items[$key]['Options_produit'] = '';
                        $items[$key]['Prix_original_produit'] = $price;
                        $items[$key]['Prix_HT_produit'] = $price;
                        $items[$key]['Quantité_commandée'] = $quantity;
                        $items[$key]['Quantité_facturée'] = 0;
                        $items[$key]['Quantité_livrée'] = 0;
                        $items[$key]['Quantité_annulée'] = 0;
                        $items[$key]['Quantité_remboursée'] = 0;
                        $items[$key]['Total_HT_produit'] = $price;
                        $items[$key]['Taxe_produit'] = $price;
                        $items[$key]['Remise_produit'] = $price;
                        $items[$key]['Total_TTC_produit'] = $price;
                    }
                }

                break;
            }
        }

        $datas['Num_Commande'] = $order_id;
        $datas['Date_Commande'] = strftime('%e %B %Y %H:%M:%S', time());
        $datas['Statut_Commande'] = $order->get_status();
        $datas['Depuis'] = SITE_MAIN_SYS_NAME;
        $datas['TotalTTC_Commande'] = $price;
        $datas['Prix_TTC_commande_devis'] = $price;
        $datas['QteTotal_Commande'] = $total_quantity;
        $datas['IdExposant'] = '';
        $datas['Nom_livraison'] = '';
        $datas['Societe_livraison'] = '';
        $datas['Evenement'] = '';
        $datas['Recap_PDF'] = '';
        $datas['Type_Stand'] = '';
        $datas['Surface_Stand'] = '';
        $datas['items'] = $items;

        /**
         * Generate Reed PDF
         */
        $pdf_file_path = generateReedPdf($order_id);
        if (!empty($pdf_file_path)) {
            $datas['Recap_PDF'] = $pdf_file_path;
        }

        if (!empty($reed_data)) {
            $datas['IdExposant'] = $reed_data->IdExposants ?? '';
            $datas['Type_Stand'] = $reed_data->TypeStand ?? '';
            $datas['Surface_Stand'] = $reed_data->SurfaceStand ?? '';
        }

        $salon_slug = $order->get_meta('slug_evenement');
        if (!empty($salon_slug)) {
            $_salon = Salon::getSalon([
                'name' => $salon_slug,
                'post_status' => 'publish',
                'posts_per_page' => 1
            ]);
            if (!empty($_salon) && is_array($_salon)) {
                $salon = reset($_salon);
                $datas['Evenement'] = $salon->post_title;
                $datas['Societe_livraison'] = get_field('salon_id', $salon->ID);
            }
        }

        $order_data = [
            'orders' => [
                $order_id => $datas
            ]
        ];

        $export_file_name = 'reedexport/export-reed.json';
        $export_data = $order_data;
        if (!empty(Storage::exists($export_file_name))) {
            $export_json = Storage::get($export_file_name);
            $export_data = json_decode($export_json, true);
            $export_data['orders'][$order_id] = $datas;
        }

        $file_name = 'reedexport/export-reed.json';
        $contents = json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        Storage::put($file_name, $contents);

        $file_name = 'reedfiles/reed-order-' . $order->get_id() . '.json';
        $contents = json_encode($order_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        Storage::put($file_name, $contents);

        /**
         * Remove Reed info to session
         */
        removeReedDataInfo();
    }
}, 20, 3);

/**
 * Query product city
 * Pro customer
 */
Action::add('woocommerce_product_query', function ($q) {
//    if (isEventSalonSession()) {
//        $salon_city = 'event';
//        if (checkEventSalonCitySlugToSession($salon_city)) {
//            $tax_query = $q->get('tax_query');
//
//            $tax_query[] = [
//                'taxonomy' => SLUG_PRODUCT_TAX_ATTRIBUT_CITY,
//                'field' => 'slug',
//                'terms' => [$salon_city],
//                'operator' => 'IN'
//            ];
//
//            $q->set('tax_query', $tax_query);
//
//            setEventSalonCitySlugToSession($salon_city);
//        }
//    }
});

/**
 * Query product city
 */
Action::add('woocommerce_product_query', function ($q) {
//    if (isActiveSalonSystem()) {
//
//        $salon_slug = Request::input(SLUG_EVENT_SALON_QUERY) ? Request::input(SLUG_EVENT_SALON_QUERY) : getEventSalonSlugInSession(SLUG_EVENT_SALON_QUERY);
//        $salon_city = getEventSalonCitySlugInSession();
////        $reset_salon_slug = Request::input('reset_salon_slug') ? Request::input('reset_salon_slug') : '';
//
//        if (!empty($salon_slug)) {
//            $city_slug = getCityBySalonSlug($salon_slug);
//            if (!empty($city_slug)) {
//                $salon_city = $city_slug;
//            }
//        }
//
////        if (!empty($reset_salon_slug) && $reset_salon_slug === "1") {
////            removeEventSalonSlugInSession();
////        }
//
//        if (isset($salon_city)) {
//            if (checkEventSalonCitySlugToSession($salon_city)) {
//                $tax_query = $q->get('tax_query');
//
//                $tax_query[] = [
//                    'taxonomy' => SLUG_PRODUCT_TAX_ATTRIBUT_CITY,
//                    'field' => 'slug',
//                    'terms' => [$salon_city],
//                    'operator' => 'IN'
//                ];
//
//                $q->set('tax_query', $tax_query);
//
////                setEventSalonCitySlugToSession($salon_city);
//            }
//        }
//    }
//
//    return $q;
});

/**
 * Filter product related single product
 */
Filter::add('woocommerce_output_related_products_args', function ($args) {
    if (isActiveSalonSystem()) {
        $salon_city = getEventSalonCitySlugInSession();

        if (!empty($salon_city)) {
            if (checkEventSalonCitySlugToSession($salon_city)) {
                $args['tax_query'] = [
                    'taxonomy' => SLUG_PRODUCT_TAX_ATTRIBUT_CITY,
                    'field' => 'slug',
                    'terms' => [$salon_city],
                    'operator' => 'IN'
                ];
            }
        }
    }

    return $args;
}, 20);

/**
 * Filter product list in Archive product
 */
Action::add('pre_get_posts', function ($query) {

    // do not modify queries in the admin
    if (is_admin()) {
        return $query;
    }

//    // only modify queries for 'product' post type
//    if (((is_product_category() || is_product_tag()) && ($query->is_tax('product_cat') || $query->is_tax('product_tag')))
//        || (isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'product' && is_shop())
//    ) {
//        $salon_city = Request::input(SLUG_EVENT_CITY_QUERY) ? Request::input(SLUG_EVENT_CITY_QUERY) : getEventSalonSlugInSession(SLUG_EVENT_CITY_QUERY);
//
//        /**
//         * Filter by city product
//         */
//        if (isActiveSalonSystem()) {
//            if (isset($salon_city)) {
//                $tax_query = $query->get('tax_query');
//                if (checkEventSalonCitySlugToSession($salon_city) && is_array($tax_query)) {
//                    $tax_query[] = [
//                        'taxonomy' => SLUG_PRODUCT_TAX_ATTRIBUT_CITY,
//                        'field' => 'slug',
//                        'terms' => [$salon_city],
//                        'operator' => 'IN'
//                    ];
//
//                    $query->set('tax_query', $tax_query);
//                }
//            }
//        }

    /**
     * Filter by salon product selected
     */
//        $salon_event = request()->get('salon-filter');
//        if (!empty($salon_event)) {
//            $product_ids = [];
//            $salon_id = getEventSalonId($salon_event);
//            $salon_products = get_field('salon_products', $salon_id);
//
//            if (!empty($salon_products) && is_array($salon_products)) {
//                foreach ($salon_products as $salon_product) {
//                    $product_ids[] = $salon_product->ID;
//                }
//            }
//            if (!empty($product_ids)) {
//                $query->set('post__in', $product_ids);
//            }
//        }
//    }

    return $query;
});

/**
 * View all product in listing
 */
Action::add('pre_get_posts', function ($query) {

    // do not modify queries in the admin
    if (is_admin()) {
        return $query;
    }

//    // only modify queries for 'product' post type
//    if (((is_product_category() || is_product_tag()) && ($query->is_tax('product_cat') || $query->is_tax('product_tag')))
//        || (isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'product' && is_shop())
//    ) {
//        $view = Request::input('view');
//
//        /**
//         * Filter by city product
//         */
//        if (!empty($view) && $view = 'all') {
////            $query->set('posts_per_page', -1);
//        }
//    }

    return $query;
});

/**
 * Hide product in listing
 */
Action::add('pre_get_posts', function ($query) {

    // do not modify queries in the admin
    if (is_admin()) {
        return $query;
    }

    // only modify queries for 'product' post type
//    if (((is_product_category() || is_product_tag()) && ($query->is_tax('product_cat') || $query->is_tax('product_tag')))
//        || (isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'product' && is_shop())
//    ) {
//        $salon_hide_product = getSalonHiddenProduct();
//        if (!empty($salon_hide_product)) {
//            $query->set('post__not_in', $salon_hide_product);
//        }
//    }

    return $query;
});

/**
 * Add custom fee insurance in cart
 */
Action::add('woocommerce_cart_calculate_fees', function () {
    global $woocommerce;

    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    $surcharge = 0;
    $cart_total = 0;
    $cart_products = $woocommerce->cart->get_cart_contents();

    if (!empty($cart_products) && is_array($cart_products)) {
        foreach ($cart_products as $cartProduct) {
            $product_id = $cartProduct['product_id'];
            if (isAddInInsuranceCalc($product_id)) {
                $cart_total += $cartProduct['line_subtotal'];
            }
        }
    }

    $app_config = get_field('app_insurance_config', 'option');
    if (!empty($app_config) && is_array($app_config)) {
        foreach ($app_config as $item) {
            $the_price = (float)$item['app_insurance_config_price'];
            if ($the_price < $cart_total) {
                $percent = $item['app_insurance_config_percent'];
                $surcharge = (float)$item['app_insurance_config_value'];
                if (!empty($percent)) {
                    $surcharge = ($cart_total * $surcharge) / 100;
                }
                break;
            }
        }
        $app_config_min = (float)get_field('app_insurance_config_min', 'option');
        if (!empty($app_config_min) && 0 < $app_config_min && 0 === $surcharge && 0 < $cart_total) {
            $surcharge = $app_config_min;
        }
    } else {
        if (7000 < $cart_total) {
            $percentage = 0.06;
            $surcharge = $cart_total * $percentage;
        } elseif (5300 < $cart_total) {
            $surcharge = 460;
        } elseif (3800 < $cart_total) {
            $surcharge = 360;
        } elseif (2200 < $cart_total) {
            $surcharge = 255;
        } elseif (1000 < $cart_total) {
            $surcharge = 138;
        } elseif (500 < $cart_total) {
            $surcharge = 69;
        } elseif (220 < $cart_total) {
            $surcharge = 49;
        } elseif (75 < $cart_total) {
            $surcharge = 24;
        } elseif (0 < $cart_total) {
            $surcharge = 12;
        }
    }

    $woocommerce->cart->add_fee(__('Assurance:', THEME_TD), $surcharge, true, '');
});

/**
 * Add custom management fee in cart
 */
Action::add('woocommerce_cart_calculate_fees', function () {
    global $woocommerce;

    if (is_admin() && ! defined('DOING_AJAX')) {
        return;
    }

    $fee_already_added = false;
    foreach ($woocommerce->cart->get_fees() as $fee) {
        if ($fee->name === __('Frais de gestion:', THEME_TD)) {
            $fee_already_added = true;
            break;
        }
    }

    if (! $fee_already_added) {
        $cart_subtotal = $woocommerce->cart->subtotal;
        if (! empty($cart_subtotal) && $cart_subtotal > 0) {
            $surcharge = (float)get_field('app_management_fee_config', 'option', true);
            if (! empty($surcharge)) {
                $woocommerce->cart->add_fee(__('Frais de gestion:', THEME_TD), $surcharge, true, '');
            }
        }
    }
});

Action::add('woocommerce_before_checkout_process', function () {
    $fee_already_added = false;
    foreach (WC()->cart->get_fees() as $fee) {
        if ($fee->name === __('Frais de gestion:', THEME_TD)) {
            $fee_already_added = true;
            break;
        }
    }

    if (! $fee_already_added) {
        $cart_subtotal = WC()->cart->subtotal;
        if (! empty($cart_subtotal) && $cart_subtotal > 0) {
            $surcharge = (float)get_field('app_management_fee_config', 'option', true);
            if (! empty($surcharge)) {
                WC()->cart->add_fee(__('Frais de gestion:', THEME_TD), $surcharge, true, '');
            }
        }
    }
});

/**
 * Add custom fee credit immo in cart
 */
Filter::add('woocommerce_cart_totals_coupon_label', function ($sprintf, $coupon) {
    if ($coupon->get_code() == 'creditimmo') {
        return __('Crédit mobilier', THEME_TD);
    }
    return $sprintf;
});
Filter::add('woocommerce_coupon_get_amount', function ($data, $coupon) {
    if (!is_admin() && $coupon->get_code() == 'creditimmo') {
        return abs(getReduceCreditAmountFee());
    }
    return $data;
});
Action::add('woocommerce_before_cart', function () {
    $coupon_code = 'creditimmo';
    $coupon_amount = abs(getReduceCreditAmountFee());
    if (WC()->cart->has_discount($coupon_code) && !empty($coupon_amount)) {
        return;
    }

    if ($coupon_amount) {
        WC()->cart->apply_coupon($coupon_code);
        wc_print_notices();
    } else {
        WC()->cart->remove_coupon($coupon_code);
    }
});
Filter::add('woocommerce_coupon_message', function ($msg, $msg_code, $coupon) {
    if ($coupon->get_code() == 'creditimmo') {
        if ($msg_code == $coupon::WC_COUPON_SUCCESS) {
            return __('Crédit mobilier appliqué avec succès.', THEME_TD);
        }
        if ($msg_code == $coupon::WC_COUPON_REMOVED) {
            return __('Crédit mobilier supprimé avec succès.', THEME_TD);
        }
    }
    return $msg;
});
Filter::add('woocommerce_cart_totals_coupon_html', function ($coupon_html, $coupon, $discount_amount_html) {
    if ($coupon->get_code() == 'creditimmo') {
        $coupon_html = str_replace('woocommerce-remove-coupon', 'woocommerce-remove-coupon hide', $coupon_html);
    }
    return $coupon_html;
});

/**
 * Redirect after login wp
 */
Filter::add('login_redirect', function ($redirect_to, $request, $user) {
    if ($GLOBALS['pagenow'] !== 'wp-login.php') {
        if (isset($user->roles) && is_array($user->roles)) {
            if (in_array('administrator', $user->roles)) {

                return $redirect_to;
            } else {
                if (WC()->cart->get_cart_contents_count() > 0) {
                    return wc_get_page_permalink('cart');
                }

                return wc_get_page_permalink('shop');
            }
        }
    }

    return $redirect_to;
});

/**
 * Redirect after login wc
 */
Filter::add('woocommerce_login_redirect', function ($redirect, $user) {
//    $redirect_page_id = url_to_postid($redirect);
//    $checkout_page_id = wc_get_page_id('checkout');

    if (WC()->cart->get_cart_contents_count() > 0) {
        return wc_get_page_permalink('cart');
    }

    return wc_get_page_permalink('shop');
});

/**
 * Redirect after registration user wc
 */
Filter::add('woocommerce_registration_redirect', function ($redirect) {
    if (WC()->cart->get_cart_contents_count() > 0) {
        return wc_get_page_permalink('cart');
    }

    return wc_get_page_permalink('shop');
});

/**
 * Order product listing args
 */
Filter::add('woocommerce_get_catalog_ordering_args', function ($args) {
    $orderby_value = isset($_GET['orderby']) ? wc_clean($_GET['orderby'])
        : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));
    if ('sku' == $orderby_value) {
        $args['orderby'] = 'meta_value';
        $args['order'] = 'asc';
        $args['meta_key'] = '_sku';
    } elseif ('sku-desc' == $orderby_value) {
        $args['orderby'] = 'meta_value';
        $args['order'] = 'desc';
        $args['meta_key'] = '_sku';
    }

    return $args;
});

/**
 * Order product listing
 */
Filter::add('woocommerce_catalog_orderby', function ($catalog_orderby_options) {
    unset($catalog_orderby_options['popularity']);
    unset($catalog_orderby_options['date']);

    $catalog_orderby_options['menu_order'] = __('Défaut', THEME_TD);
    $catalog_orderby_options['price'] = __('Tarif croissant', THEME_TD);
    $catalog_orderby_options['price-desc'] = __('Tarif décroissant', THEME_TD);
    $catalog_orderby_options['sku'] = __('Réference 0-9', THEME_TD);
    $catalog_orderby_options['sku-desc'] = __('Réference 9-0', THEME_TD);
    $catalog_orderby_options['title'] = __('Titre (A-Z)', THEME_TD);
    $catalog_orderby_options['title-desc'] = __('Titre (Z-A)', THEME_TD);

    return $catalog_orderby_options;
});

/**
 * Add a check salon if no value #modal-event-warning
 */
Action::add('wp_footer', function () {
//    if (is_checkout_pay_page()) {
//        return '';
//    }
//    if (is_cart() || is_checkout() || is_product() || is_shop() || is_product_category() || is_product_tag()
//        || Route::currentRouteName() == 'showroom-template'
//        || Route::currentRouteName() == 'search-product') {
//        echo '<input type="hidden" name="cmrs-no-salon" id="cmrs-no-salon" />';
//    }
}, 100);

/**
 * Disable unused product type
 */
Filter::add('product_type_selector', function ($types) {
    unset($types['simple']);
    unset($types['grouped']);
    unset($types['external']);

    return $types;
});


/**
 *  Filter other product color
 */
Filter::add('acf/fields/relationship/query/key=field_5ddbc3bbfbd06', function ($args, $field, $post_id) {
    $args['tax_query'][] = [
        'taxonomy' => 'product_type',
        'field' => 'slug',
        'terms' => 'variable',
    ];

    return $args;
}, 10, 3);

/**
 *  Filter other product color
 */
Filter::add('acf/fields/relationship/query/key=field_5daefb22e1a08', function ($args, $field, $post_id) {
    $args['tax_query'][] = [
        'taxonomy' => 'product_type',
        'field' => 'slug',
        'terms' => 'variable',
    ];

    return $args;
}, 10, 3);

/**
 *  Filter other product suggest
 */
Filter::add('acf/fields/relationship/query/key=field_5ddbc1ed34f38', function ($args, $field, $post_id) {
    $args['tax_query'][] = [
        'taxonomy' => 'product_type',
        'field' => 'slug',
        'terms' => 'variable',
    ];

    return $args;
}, 10, 3);

/**
 * Disable payment for Pro customer
 */
Filter::add('woocommerce_cart_needs_payment', function ($needs_payment, $cart) {
    if (isEventSalonSession()) {
        return false;
    }

    return $needs_payment;
});

/**
 *  Filter salon_hide_product product
 */
Filter::add('acf/fields/relationship/query/key=field_5f046f6f2419c', function ($args, $field, $post_id) {
    $args['tax_query'][] = [
        'taxonomy' => 'product_type',
        'field' => 'slug',
        'terms' => 'variable',
    ];

    return $args;
}, 10, 3);

/**
 *  Filter product salon_hide_product
 */
Filter::add('acf/fields/relationship/result/name=salon_hide_product', function ($title, $post, $field, $post_id) {

    if ($post->post_type === 'product') {
        $product = wc_get_product($post->ID);
        $sku = $product->get_sku();
        $title = $title . ' #' . $sku;
    }

    return $title;
}, 10, 4);

/**
 *  Filter product product_colors
 */
Filter::add('acf/fields/relationship/result/name=product_colors', function ($title, $post, $field, $post_id) {

    if ($post->post_type === 'product') {
        $product = wc_get_product($post->ID);
        $sku = $product->get_sku();
        $title = $title . ' #' . $sku;
    }

    return $title;
}, 10, 4);

/**
 *  Filter product product_other_suggest
 */
Filter::add('acf/fields/relationship/result/name=product_other_suggest', function ($title, $post, $field, $post_id) {

    if ($post->post_type === 'product') {
        $product = wc_get_product($post->ID);
        $sku = $product->get_sku();
        $title = $title . ' #' . $sku;
    }

    return $title;
}, 10, 4);

/**
 * Trim zeros in price decimals
 **/
Filter::add('woocommerce_price_trim_zeros', '__return_true');

/**
 * Redirect hidden product category page on salon
 */
Action::add('template_redirect', function () {
    $queried_object = get_queried_object();
    if (!empty($queried_object) && $queried_object instanceof WP_Term) {
        $term_id = $queried_object->term_id;
        $salon_hide_cat = getSalonHiddenCat();
        if (!empty($salon_hide_cat) && in_array($term_id, $salon_hide_cat)) {
            $url = get_permalink(wc_get_page_id('shop'));
            wp_safe_redirect($url, 302);
            exit();
        }
    }
});

/**
 * Empty cart after checkout
 */
Action::add('woocommerce_checkout_order_processed', function ($order_id) {
    if (isOrderFromReed()) {
        WC()->cart->empty_cart();
    }
}, 100, 1);

Action::add('woocommerce_after_calculate_totals', function () {
    $subtotal = (float)WC()->cart->get_subtotal();
    $fee_totals = (float)WC()->cart->get_fee_total();
    $total = $subtotal + $fee_totals;
    $items = WC()->cart->get_cart();
    $amount = 0;
    foreach ($items as $item => $values) {
        if (array_key_exists('reduce_credit_amount', WC()->cart->cart_contents[$item])) {
            $amount = (float)WC()->cart->cart_contents[$item]['reduce_credit_amount'];
        }
    }

    if (!empty($amount) && $amount > $subtotal) {
        WC()->cart->set_discount_total($amount);
        WC()->cart->set_coupon_discount_totals(['creditimmo' => $amount]);
        $vat = (float)WC()->cart->get_total_tax();
        $up_total_taxed = ($total - $amount) + $vat;
        $up_total = $total - $amount;
        $rate = 0;
        $rates = WC_Tax::get_rates();
        if (!empty($rates)) {
            $rates = reset($rates);
            $rate = array_key_exists('rate', $rates) ? $rates['rate'] : 1;
        }
        if ($up_total < 0) {
            WC()->cart->set_total(0);
            WC()->cart->set_fee_tax(0);
            WC()->cart->set_fee_taxes([]);
            WC()->cart->set_total_tax(0);
        } else {
            if (!empty($rate)) {
                $tax_fee = ($up_total * $rate) / 100;
                $total_cart = $up_total + $tax_fee;
                WC()->cart->set_total($total_cart);
                WC()->cart->set_fee_taxes(['1' => $tax_fee]);
                WC()->cart->set_fee_tax($tax_fee);
                WC()->cart->set_total_tax($tax_fee);
            } else {
                WC()->cart->set_total($up_total);
                WC()->cart->set_fee_taxes([]);
                WC()->cart->set_fee_tax(0);
                WC()->cart->set_total_tax(0);
            }
        }
    }
}, 50);

Filter::add('woocommerce_get_order_item_totals', function ($total_rows) {
    $rows = moveElementArray($total_rows, 2, 1);

    if (!empty($rows)) {
        foreach ($rows as $key => $row) {
            if ($key == 'cart_subtotal') {
                $rows['cart_subtotal']['label'] = __('Mobilier :', THEME_TD);
            }
            if ($key == 'discount') {
                $rows['discount']['label'] = __('Crédit Mobilier :', THEME_TD);
            }
            if ($key == 'fee_943') {
                $rows['fee_943']['label'] = __('Assurance :', THEME_TD);
            }
        }
    }

    return $rows;
});

/**
 * Event check on checkout
 */
Action::add('woocommerce_before_checkout_process', function ($array) {
    if (empty($_POST['_event-name']) || empty($_POST['_event-place']) || empty($_POST['_event-date']) || empty($_POST['_event-city']) || empty($_POST['_event-stand'])) {
        $message = __('Veuillez remplir correctement les Informations sur l\'évènement', THEME_TD);
        $message .= ' <a href="#" class="wc-error-event btn btn-bgc_2 btn-tt_u btn-c_w btn-fz_12 nav-process" data-process="3">' . __('Retour à l\'évènement',
                THEME_TD) . '</a>';
        wc_add_notice($message, 'error');
    }
}, 10, 1);

/**
 * Recipient for Pro user Email
 */
Filter::add('woocommerce_email_recipient_custom_new_order', function ($recipient, $order) {
    if (isEventSalonSession()) {
        $recipient .= getShopManagerEmails();
    }

    return $recipient;
}, 10, 2);

Action::add('cmrs_empty_session_data', function () {
    $rest = new WC_REST_System_Status_Tools_V2_Controller();
    $rest->execute_tool('clear_sessions');
});

/**
 * Event check on checkout
 */
Action::add('woocommerce_before_checkout_process', function ($array) {
    $the_message = __('Veuillez remplir les champs : ', THEME_TD);
    $message = [];

//    if (isset($_POST['password_custom']) && empty($_POST['username_custom'])) {
//        $message[] = __('E-mail', THEME_TD);
//    }
//    if (isset($_POST['password_custom']) && empty($_POST['password_custom'])) {
//        $message[] = __('Mot de passe', THEME_TD);
//    }

    if (empty($_POST['billing_first_name'])) {
        $message[] = __('Prénom', THEME_TD);
    }

    if (empty($_POST['billing_last_name'])) {
        $message[] = __('Nom', THEME_TD);
    }

    if (empty($_POST['billing_company'])) {
        $message[] = __('Nom de Société', THEME_TD);
    }

    if (empty($_POST['billing_address_1'])) {
        $message[] = __('Adresse', THEME_TD);
    }
    if (empty($_POST['billing_postcode'])) {
        $message[] = __('Code postal', THEME_TD);
    }
    if (empty($_POST['billing_city'])) {
        $message[] = __('Ville', THEME_TD);
    }
    if (empty($_POST['billing_phone'])) {
        $message[] = __('Téléphone', THEME_TD);
    }
    if (empty($_POST['billing_email'])) {
        $message[] = __('Email', THEME_TD);
    }

    if (!empty($message)) {
        $message[] = '  <a href="#" class="wc-error-event btn btn-bgc_2 btn-tt_u btn-c_w btn-fz_12 nav-process" data-process="2">' . __('Retour',
                THEME_TD) . '</a>';
        wc_add_notice('<strong>' . $the_message . '</strong>' . implode(" , ", $message), 'error');
    }
}, 10, 1);


/* Exempt VAT calc */
Filter::add('alg_wc_eu_vat_maybe_exclude_vat', function ($vat) {

    $selected_country = !empty($_REQUEST['billing_country']) ? sanitize_text_field($_REQUEST['billing_country']) : 'FR';
    if (empty($selected_country)) {
        if (empty($selected_country) && !empty($_REQUEST['post_data'])) {
            parse_str($_REQUEST['post_data'], $post_data_args);
            if (!empty($post_data_args['billing_country'])) {
                $selected_country = sanitize_text_field($post_data_args['billing_country']);
            }
        }
        if (empty($selected_country) && !empty($_REQUEST['country'])) {
            $selected_country = sanitize_text_field($_REQUEST['country']);
        }
        if (empty($selected_country)) {
            $selected_country = WC()->customer->get_billing_country();
        }
        if (empty($selected_country)) {
            $selected_country = WC()->checkout->get_value('billing_country');
        }
    }

    if (empty($selected_country)) {
        return $vat;
    }

    $selected_country = strtoupper($selected_country);

    $locations = ['FR'];

    if (in_array($selected_country, $locations)) {
        return false;
    } else {
        return $vat;
    }
});

Action::add('woocommerce_order_actions', function ($actions) {
    if (isset($actions['send_order_details_admin'])) {
        unset($actions['send_order_details_admin']);
    }
    if (isset($actions['send_order_details'])) {
        unset($actions['send_order_details']);
    }
    $actions['send_custom_order_customer_email'] = __('Envoyer E-mail de commande Client');
    $actions['send_custom_order_admin_email'] = __('Envoyer E-mail de commande Admin');

    return $actions;
});

Action::add('woocommerce_order_action_send_custom_order_customer_email', function ($order) {
    WC()->mailer()->emails['WC_Custom_Email_New_Customer_Order']->trigger($order->get_id(), $order);
});

Action::add('woocommerce_order_action_send_custom_order_admin_email', function ($order) {
    WC()->mailer()->emails['WC_Custom_Email_New_Order']->trigger($order->get_id(), $order);
});

//Filter::add('alg_wc_eu_vat_maybe_exclude_vat', function () {
//    return true;
//});

/**
 * Exclude products from a particular category on the shop page
 */
Action::add('woocommerce_product_query', function ($q) {

    $tax_query = (array)$q->get('tax_query');

    $tax_query[] = array(
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => array('non-classe', 'uncategorized', 'uncategorized-en', 'uncategorized-es'), // Don't display products in the clothing category on the shop page.
        'operator' => 'NOT IN'
    );

    $q->set('tax_query', $tax_query);
});

Action::add('init', function ($q) {
    add_post_type_support('page', 'excerpt');
});