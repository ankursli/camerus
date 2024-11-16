<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 17/07/2019
 * Time: 15:25
 */

use App\DownloadStats;
use App\Hooks\Product;
use App\Hooks\Salon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Spipu\Html2Pdf\Html2Pdf;
use Themosis\Support\Facades\Ajax;


/**
 * Ajax for Salon filter form
 */
Ajax::listen('salon_filter_form', function () {

    $datas = Request::all();

    if ($datas['_token'] != csrf_token()) {
        wp_send_json_error(['message' => 'Error security',]);
        die();
    }

    $validator = Validator::make($datas, [
        'calendar_date' => 'date_format:Y-m|max:7',
        'calendar_place' => 'string|max:20',
        'source_page' => 'required|string|in:home,other',
    ]);

    if ($validator->fails()) {
        wp_send_json_error(['message' => 'Error validation',]);
        die();
    }

    setDateTimeLocalFormat($datas['lang']);

    $date = $datas['calendar_date'];
    $place = $datas['calendar_place'];
    $source = $datas['source_page'];

    $args = [
        'meta_query' => [
            'relation' => 'AND'
        ]
    ];

    if (!empty($date)) {
        $date = (int)trim(str_replace('-', '', $date));
        $args['meta_query'][] = [
            'key' => 'salon_start_date',
            'value' => $date,
            'compare' => 'LIKE'
        ];
    }

    if (!empty($place)) {
        $place = trim($place);
        $args['tax_query'][] = [
            'taxonomy' => 'salon_city',
            'field' => 'slug',
            'terms' => $place
        ];
    }

    $salons = Salon::getSalon($args);

    if (!empty(request()->get('print'))) {
        $print_salons = array_chunk($salons, 4);
        $pdf_uri = generateHtmlToPdf(['salons' => $print_salons], 'agenda-' . time(), 'agenda-pdf', 'pdf.agenda-print-pdf');

        $message = [
            'message' => 'success filter',
            'pdf_url' => $pdf_uri,
        ];

        return wp_send_json_success($message);
    } else {
        if (!empty($salons) && is_array($salons)) {
            $message = [
                'message' => 'success filter',
                'post_number' => count($salons),
            ];

            $view_args = [
                'salons' => $salons,
                'source' => $source
            ];

            $message['salon_ticket'] = view('components.agenda.ticket-calendar', $view_args)->render();
            $message['salon_switcher'] = '';
            if ($source == 'other') {
                $message['salon_switcher'] = view('components.agenda.block-calendar-list', $view_args)->render();
            }

            return wp_send_json_success($message);
        } else {
            $message = [
                'message' => __('Pas de salon pour votre selection', THEME_TD),
                'post_number' => 0,
            ];

            return wp_send_json_success($message);
        }
    }

    wp_send_json_error();

    die();

});

Ajax::listen('salon_add_favoris', function () {

    $datas = request()->all();
    if (!empty($datas) && array_key_exists('agenda', $datas)) {

        if (!empty($datas['agenda'])) {

            $salon_id = (int)$datas['agenda'];
            $salon = get_post($salon_id);

            if (addSalonUserFavoris($salon_id)) {
                $message = [
                    'message' => '<a href="' . get_permalink(wc_get_page_id('shop')) . '?' . SLUG_EVENT_SALON_QUERY . '=' . $salon->post_name . '" class="add">'
                        . __('Voir les produits', THEME_TD)
                        . ' <i class="icon icon-product-star-2"></i></a>',
                    'datas' => $datas,
                ];

                return wp_send_json_success($message);
            } else {
                $message = [
                    'message' => _("Vous devez vous connecter pour ajouter un salon aux favoris"),
                ];

                wp_send_json_error($message);
            }
        }

        wp_send_json_error();

        die();
    }
});

Ajax::listen('salon_delete_favoris', function () {

    $datas = request()->all();

    if (!empty($datas) && array_key_exists('agenda', $datas)) {

        if (!empty($datas['agenda'])) {

            $salon_id = (int)$datas['agenda'];

            if (deleteSalonUserFavoris($salon_id)) {

                $message = [
                    'message' => _('La suppression aux favoris a été un succès'),
                    'datas' => $datas,
                ];

                return wp_send_json_success($message);
            }
        }

        wp_send_json_error();

        die();
    }
});


/**
 * Ajax for Salon filter form
 */
Ajax::listen('contact_form_submit', function () {

    $datas = Request::all();
    $origin = $datas['origin'];

    if ($origin == $_SERVER['SERVER_NAME']) {
        //Set states
        $success = array(
            "processed" => true,
            "message" => "Votre message a été envoyé avec succès. Il sera traité dans les plus bref délais.",
            "data" => $datas
        );

        $failure = array(
            "processed" => false,
            "message" => "Erreur lors de la soumission du formulaire, veuillez réessayer plus tard",
            "data" => $datas
        );

        $headers[] = 'From: ' . SITE_MAIN_SYS_NAME . ' <contact@camerus.fr>';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        $to = APP_EMAIL_FORM_CONTACT;
        $subject = _('Formulaire de contact');
        $datas['email_type'] = 'contact';
        $datas['subject'] = $subject;
        $body = View::make('components.email.layout-default', $datas)->render();

        if (wp_mail($to, $subject, $body, $headers)) {
            echo json_encode($success);
        } else {
            echo json_encode($failure);
        }

        die();
    }

    wp_send_json_error();

    die();
});

/**
 * Not used
 */
Ajax::listen('validate_form_checkout', function () {
    $data = request()->all();
    $WC = new WC_Cart();
    $WC_checkout = new WC_Checkout();
    //$WC_checkout->process_checkout();
    $errors = new WP_Error();
    $posted_data = $WC_checkout->get_posted_data();
    //$WC_checkout->validate_checkout( $posted_data, $errors );

    foreach ($errors->get_error_messages() as $message) {
        wc_add_notice($message, 'error');
    }

    //$WC_checkout->send_ajax_failure_response();
});

/**
 * Filter product loop
 */
Ajax::listen('product_filter_form', function () {
    $data = request()->all();
    $current_url = request()->get('current_url');
    $city = getEventSalonCitySlugInSession();

    if (!empty($city)) {
        $posts_per_page = 9;
        $paged = $data['paged'] ? (int)$data['paged'] : 1;
        $paged_load_more = array_key_exists('page-load-more', $data) ? (int)$data['page-load-more'] : false;
        $product_tags = array_key_exists('product_tag', $data) ? wc_clean($data['product_tag']) : null;
        $orderby_value = wc_clean($data['orderby']);
        $orderby = 'title';
        $order = 'ASC';
        if (!empty($orderby_value)) {
            switch ($orderby_value) {
                case 'sku':
                    $orderby = 'sku';
                    $order = 'ASC';
                    break;
                case 'sku-desc':
                    $orderby = 'sku';
                    $order = 'DESC';
                    break;
                case 'price':
                    $orderby = 'price';
                    $order = 'ASC';
                    break;
                case 'price-desc':
                    $orderby = 'price';
                    $order = 'DESC';
                    break;
                case 'title-desc':
                    $orderby = 'title';
                    $order = 'DESC';
                    break;
                default:
                    $orderby = 'title';
                    $order = 'ASC';
            }
        }

        $salon_products = '';
        if (!empty($data['salon-filter'])) {
            $salon_filter = rtrim($data['salon-filter'], ',');
            $salon_products = explode(',', $salon_filter);
        }

        $salon_hide_product = getSalonHiddenProduct();

        $category = [];
        if (!empty($data['category'])) {
            $category[] = $data['category'];
        }

        $color = [];
        if (!empty($data['product_color'])) {
            $color[] = $data['product_color'];
        }

        $args = [
            'paged' => $paged,
            'posts_per_page' => $posts_per_page,
            'city' => $city,
            'order' => $order,
            'orderby' => $orderby,
            'tag' => $product_tags,
            'color' => $color,
            'category' => $category,
            'product_salon' => $salon_products,
            'product_hide_salon' => $salon_hide_product,
            'clang' => getShortLangCode($data['lang']),
        ];

//        dd($args);
        $query_product = getCustomProducts($args);

        $view_data = [
            'template_type' => 'ajax',
            'products_data' => $query_product,
            'products' => $query_product['products'],
            'paged_load_more' => $paged_load_more,
        ];
        $pagination_data = [
            'current_url' => $current_url,
            'paged' => $paged,
            'total' => $query_product['total'],
            'max_num_pages' => $query_product['max_num_pages'],
        ];

        $message = [
            'message' => __("Chargement des produits", THEME_TD),
            'current_page' => $paged,
            'products' => View::make('shop.loop.ajax-container', $view_data)->render(),
            'pagination' => View::make('common.pagination-ajax', $pagination_data)->render(),
        ];

        if ($paged_load_more) {
            if (!empty($query_product['products'])) {
                $message['pagination']
                    = '<div id="product-ajax-load-more" data-load="0" class="block block-produ ct__link product-ajax-load-more uk-width-1-1"><div uk-spinner="" class="uk-icon uk-spinner uk-grid-margin"><svg width="50" height="50" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg" data-svg="spinner"><circle fill="none" stroke="#000" cx="15" cy="15" r="14"></circle></svg></div></div>';
            } else {
                $message['pagination'] = '';
            }
        }

        wp_reset_query();

        wp_send_json_success($message);
    }

    wp_send_json_error();
    die();
});

/**
 * Filter product search loop
 */
Ajax::listen('product_search_filter_form', function () {
    $data = request()->all();
    $current_url = request()->get('current_url');

    if (!empty($data)) {
        $inputs = request()->all() ? request()->all() : [];
        $input_orderby = request()->get('orderby') ?: false;
        $input_paged = request()->get('paged') ?: 1;
        $input_s = request()->get('s') ?: false;
        $paged_load_more = $inputs['page-search-load-more'] ? (int)$inputs['page-search-load-more'] : false;
        $inputs['old-query'] = '';

//        if (!empty(request()->get('old-query'))) {
//            $old_inputs = getSearchQueryToSession();
//            if (empty($input_orderby)) {
//                $input_orderby = array_key_exists('orderby', $old_inputs) ? $old_inputs['orderby'] : 'date';
//            }
//            $input_paged = array_key_exists('paged', $old_inputs) ? $old_inputs['paged'] : '1';
//            $input_s = array_key_exists('s', $old_inputs) ? $old_inputs['s'] : false;
//        }

        $product_ids = null;
        if (!empty($input_s)) {
            $inputs_1 = $inputs;
            $inputs_1['fields'] = 'ids';

            $inputs_2 = $inputs;
            $inputs_2['s'] = false;
            $inputs_2['s_sku'] = $input_s;
            $inputs_2['fields'] = 'ids';
        }

        if (!empty($product_ids)) {
            $inputs['s'] = false;
            $inputs['search'] = $input_s;
            $inputs['post__in'] = $product_ids;
        }

        $salon = null;
        $salon_slug = getEventSalonSlugInSession(SLUG_EVENT_SALON_QUERY);
        if (!empty($salon_slug)) {
            $salon_id = getPostIdBySlug($salon_slug);
            $salon = get_post($salon_id);
        }

        $selected = request()->all();
        if (!empty($old_inputs)) {
            $selected = wp_parse_args($old_inputs, $selected);
        }
        $order = '';
        $orderby = '';
        if (!empty($input_orderby)) {
            switch ($input_orderby) {
                case 'sku':
                    $order = 'ASC';
                    $orderby = 'sku';
                    break;
                case 'sku-desc':
                    $order = 'DESC';
                    $orderby = 'sku';
                    break;
                case 'price':
                    $order = 'ASC';
                    $orderby = 'price';
                    break;
                case 'price-desc':
                    $order = 'DESC';
                    $orderby = 'price';
                    break;
                case 'title':
                    $order = 'ASC';
                    $orderby = 'title';
                    break;
                case 'title-desc':
                    $order = 'DESC';
                    $orderby = 'title';
                    break;
                case 'date':
                    $order = 'ASC';
                    $orderby = 'date';
                    break;
                case 'date-desc':
                    $order = 'DESC';
                    $orderby = 'date';
                    break;
                default:
                    $order = 'ASC';
                    $orderby = 'title';
            }
        }

        $posts_per_page = 12;
        $city = getEventSalonCitySlugInSession();
        $product_categories = array_key_exists('category', $selected) && !empty($selected['category']) ? wc_clean($selected['category']) : false;
        $product_colors = array_key_exists('pa_color', $selected) && !empty($selected['pa_color']) ? wc_clean($selected['pa_color']) : false;
        $product_tags = array_key_exists('product_tag', $selected) && !empty($selected['product_tag']) ? wc_clean($selected['product_tag']) : false;
        $product_materials = array_key_exists('product_material', $selected) && !empty($selected['product_material']) ? wc_clean($selected['product_material'])
            : false;
        $salon_hide_product = getSalonHiddenProduct();

        $args = [
            'paged' => $input_paged,
            'posts_per_page' => $posts_per_page,
            'city' => $city,
            'order' => $order,
            'orderby' => $orderby,
            'tag' => $product_tags,
            'color' => $product_colors,
            'category' => $product_categories,
            'material' => $product_materials,
            'product_hide_salon' => $salon_hide_product,
            's' => $input_s,
            'clang' => getShortLangCode($data['lang']),
        ];

        $query_product = getCustomProducts($args);

        addSearchQueryToSession($inputs);

        $view_data = [
            'products_data' => $query_product,
            'products' => $query_product['products'],
        ];
        $pagination_data = [
            'current_url' => $current_url,
            'paged' => $input_paged,
            'total' => $query_product['total'],
            'max_num_pages' => $query_product['max_num_pages'],
        ];

        $message = [
            'message' => __("Chargement des produits", THEME_TD),
            'current_page' => $input_paged,
            'count' => count($query_product['products']),
            'products' => View::make('pages.product-search-loop', $view_data)->render(),
            'pagination' => View::make('common.pagination-ajax', $pagination_data)->render(),
        ];

        if ($paged_load_more && !empty($query_product['products'])) {
            $message['pagination']
                = '<div id="product-ajax-load-more" data-load="0" class="block block-produ ct__link product-ajax-load-more uk-width-1-1"><div uk-spinner="" class="uk-icon uk-spinner uk-grid-margin"><svg width="50" height="50" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg" data-svg="spinner"><circle fill="none" stroke="#000" cx="15" cy="15" r="14"></circle></svg></div></div>';
        }

        wp_reset_query();

        wp_send_json_success($message);
    }

    wp_send_json_error();
    die();
});

/**
 * Reed
 * Get Dotation product item
 */
Ajax::listen('get_reed_dotation_item', function () {
    $data = request()->all();
    $product_id = (int)request()->get('product');
    $city = getEventSalonSlugInSession();

    if (!empty($product_id) && !empty($city)) {
        $view_data = [];
        $product = wc_get_product($product_id);
        $view_data['product_id'] = $product_id;
        $view_data['product'] = $product;
        $view_data['colors'] = getProductColors($product_id);
        $view_data['metas'] = get_field('product_options', $product_id);
        $view_data['attachment_ids'] = $product->get_gallery_image_ids();
        $view_data['post_thumbnail_id'] = $product->get_image_id();
        $view_data['schema_img'] = get_field('product_schema_img', $product_id);
        $color_terms = wc_get_product_terms(
            $product_id,
            'pa_color',
            array(
                'fields' => 'all',
            )
        );
        if (!empty($color_terms)) {
            $color = $color_terms[0];
            $color_picker = get_field('pa_color_picker', 'pa_color_' . $color->term_id);
            $view_data['color'] = [
                'name' => $color->name,
                'value' => $color_picker
            ];
        }

        $message = [
            'message' => __("Chargement des produits", THEME_TD),
            'product' => View::make('components.popup.modal-product-content', $view_data)->render(),
        ];

        wp_send_json_success($message);
    }

    wp_send_json_error();
    die();
});

/**
 * Reed
 * Get Dotation product item
 */
Ajax::listen('add_reed_dotation_item', function () {
    $product_id = (int)request()->get('product');
    $dotation_id = (int)request()->get('dotation');
    $quantity = (int)request()->get('quantity');

    if (!empty($product_id) && !empty($quantity)) {

        addProductToDotation($dotation_id, $product_id, $quantity);

        $message = [
            'message' => __("Ajout des produits terminer", THEME_TD),
        ];

        wp_send_json_success($message);
    }

    wp_send_json_error();
    die();
});

/**
 * Reed
 * Get Dotation product item
 */
Ajax::listen('remove_reed_dotation_item', function () {
    $product_id = (int)request()->get('product');

    if (!empty($product_id)) {

        removeProductToDotation($product_id);

        $message = [
            'message' => __("Suppression des produits terminer", THEME_TD),
        ];

        wp_send_json_success($message);
    }

    wp_send_json_error();
    die();
});

/**
 * Products
 * Load file import product csv
 */
Ajax::listen('import_products_camerus', function () {
    if (request()->hasFile('camerus_form_file')) {
        $uploadFile = request()->file('camerus_form_file');
        $fileExtension = $uploadFile->getClientOriginalExtension();

        if ($fileExtension === 'csv') {
            $fileName = 'import_product_camerus.' . $fileExtension;
            $destinationPath = wp_upload_dir()['basedir'] . '/import_products/';
            $destinationUrl = wp_upload_dir()['baseurl'] . '/import_products/';
            $uploadFile->move($destinationPath, $fileName);
            $filePath = $destinationPath . $fileName;
            $fileUrl = $destinationUrl . $fileName;

            if (!empty($filePath)) {
                $message = [
                    'message' => __("Chargement du fichier import terminer", THEME_TD),
                    'file_url' => $fileUrl
                ];

                wp_send_json_success($message);
            }
        }
    }

    wp_send_json_error();
    die();
});

/**
 * Products
 * Load file import product csv
 */
Ajax::listen('import_products_data_camerus', function () {
    $product_data = request()->get('product');
    $import_type = request()->get('import_type');
    $product = null;

    if (!empty($product_data)) {
        $product_data = json_decode($product_data);

        if ($import_type == 'attachment_delete_duplicate') {
            $deleted_images = attachmentDeleteProductMediaSimilar((int)$product_data->Reference);

            $message = [
                'message' => __("No Attachment duplicated", THEME_TD)
            ];

            if (!empty($deleted_images)) {
                $message = [
                    'message' => __("Delete Attachment duplicated", THEME_TD),
                    'deleted' => json_encode($deleted_images)
                ];
            }
            wp_send_json_success($message);

        } else {
            if (!empty($product_data->Reference)) {
                $product_id = wc_get_product_id_by_sku($product_data->Reference);

                switch ($import_type) {
                    case 'product_mobilier':
                        if (!empty($product_id)) {
                            /**
                             * Update product data
                             */
                            $product = wc_get_product($product_id);
                            if (!empty($product)) {
                                $product = make_product_import_csv($product_data, $product_id);
                            }
                        } else {
                            /**
                             * Create new product by import data
                             */
                            $product = make_product_import_csv($product_data);
                        }
                        break;
                    case 'product_dotation':
                        if (!empty($product_id)) {
                            /**
                             * Update product data
                             */
                            $product = wc_get_product($product_id);
                            if (!empty($product)) {
                                $product = make_dotation_import_csv($product_data, $product_id);
                            }
                        } else {
                            /**
                             * Create new dotation by import data
                             */
                            $product = make_dotation_import_csv($product_data);
                        }
                        break;
                    case 'product_dotation_mobilier':
                        if (!empty($product_id)) {
                            /**
                             * Update dotation data
                             */
                            $product = wc_get_product($product_id);
                            if (!empty($product)) {
                                $product = update_dotation_mobilier_import_csv($product_data, $product_id);
                            }
                        }
                        break;
                    default:
                        $message = [
                            'product' => $product,
                            'message' => __("Importation non reconnu", THEME_TD)
                        ];

                        wp_send_json_error($message);
                        break;
                }

                $message = [
                    'product' => $product,
                    'message' => __("Importation du produit terminer", THEME_TD)
                ];

                wp_send_json_success($message);
            } else {
                $message = [
                    'product' => $product,
                    'message' => __("Pas de référence produit", THEME_TD)
                ];

                wp_send_json_error($message);
            }
        }
    }

    wp_send_json_error();
    die();
});

/**
 * Cart
 * Reduce credit
 */
Ajax::listen('pro_user_quotation', function () {
    global $woocommerce;

    $user_id = get_current_user_id();

    if (!empty($user_id) && !$woocommerce->cart->is_empty()) {
        $data = [];
        $products = [];
        $user = get_user_by('id', $user_id);
        $items = $woocommerce->cart->get_cart();

        $data['cart'] = $woocommerce->cart;

        foreach ($items as $item => $values) {
            $_products = wc_get_product($values['data']->get_id());
            $_products->quantity = $values['quantity'];
            $_products->total = $values['line_total'];
            $_products->color = '';
            if (array_key_exists('attribute_pa_color', $values['variation'])) {
                $_products->color = $values['variation']['attribute_pa_color'];
            }
            $products[] = $_products;
        }

        $data['cart_subtotal'] = $woocommerce->cart->get_subtotal();
        $data['cart_total_tax'] = $woocommerce->cart->get_total_tax();
        $data['cart_total_fee'] = $woocommerce->cart->get_fee_total();
        $data['cart_total'] = $woocommerce->cart->get_total('raw');
        $data['cart_customer'] = $woocommerce->cart->get_customer();

        $data['email_type'] = 'procustomer-order';
        $data['user'] = $user;
        $data['products'] = $products;

        $to = getShopManagerEmails();
        $subject = __('Demande de devis - Utilisateur PRO', THEME_TD);
        if (!empty($to) && sendEmailCustomType($to, $subject, $data)) {
            wc()->cart->empty_cart();

            $message = [
                'message' => __("Demande envoyée", THEME_TD),
                'redirect_link' => get_permalink(wc_get_page_id('shop')),
            ];

            wp_send_json_success($message);
        }
    }

    wp_send_json_error();
    die();
});

/**
 * Download
 * Get category zip files
 */
Ajax::listen('cart_reduce_credit', function () {
    global $woocommerce;

    $amount = request()->get('amount');
    $delete = request()->get('delete_action');

    if (!empty($delete)) {
        $items = $woocommerce->cart->get_cart();

        foreach ($items as $item => $values) {
            unset(WC()->cart->cart_contents[$item]['reduce_credit_amount']);
        }

        WC()->cart->set_session();

        $message = [
            'message' => __("Panier mise à jour", THEME_TD),
            'redirect_link' => get_permalink(wc_get_page_id('cart')),
        ];

        wp_send_json_success($message);
    } elseif (!$woocommerce->cart->is_empty() && !empty($amount)) {
        $items = $woocommerce->cart->get_cart();

        foreach ($items as $item => $values) {
            WC()->cart->cart_contents[$item]['reduce_credit_amount'] = $amount;
        }

        WC()->cart->set_session();

        $message = [
            'message' => __("Panier mise à jour", THEME_TD),
            'redirect_link' => get_permalink(wc_get_page_id('cart')),
        ];

        wp_send_json_success($message);
    }

    wp_send_json_error();
    die();
});
/**
 * Cart
 * Send Pro User Order & Quotation
 */
Ajax::listen('get_category_zip_files', function () {

    $cat_slug = request()->get('cat_slug');

    if (!empty($cat_slug)) {

        $cat_slug = wc_clean($cat_slug);
        $zip_data_file = getCategoryZipPath($cat_slug);

        $view_args = [
            'zip_data' => $zip_data_file
        ];
        $message = [
            'message' => __("Téléchargement mise à jour", THEME_TD),
            'zip_files' => $zip_data_file,
            'zip_view_item' => View::make('components.popup.modal-category-zip-download-content', $view_args)->render(),
        ];

        wp_send_json_success($message);
    }

    wp_send_json_error();
    die();
});

/**
 * Download
 * Save stat download
 */
Ajax::listen('set_download_stat', function () {

    $name = request()->get('name');
    $link = request()->get('link');

    if (!empty($name) && !empty($link)) {

        $slug = Str::slug($name);
        $date = date('Y-m-d', time());

        $stat = DownloadStats::where('slug', $slug)
            ->where('date', $date)
            ->first();

        if ($stat != null) {
            $count = (int)$stat->count + 1;
            DownloadStats::updateStat($slug, $date, $count);
        } else {
            $new_stat = new DownloadStats();
            $new_stat->slug = $slug;
            $new_stat->date = $date;
            $new_stat->name = $name;
            $new_stat->link = $link;
            $new_stat->count = 1;
            $new_stat->save();
        }

        $message = [
            'message' => __("Stat updated", THEME_TD),
        ];

        wp_send_json_success($message);
    }

    wp_send_json_error();
    die();
});

/**
 * Refresh csrf token ajax
 */
Ajax::listen('refresh_csrf_token', function () {
    $token = request()->get('token');

    if (!empty($token)) {
        if ($token === csrf_token()) {
            $message = [
                'token' => false
            ];

            wp_send_json_success($message);
        } else {
            $message = [
                'token' => csrf_token()
            ];

            wp_send_json_success($message);
        }
    }
    die();
});

/**
 * Refresh csrf token ajax
 */
Ajax::listen('cmrs_dynamic_content_header_panel', function () {
    $message = [];
    $renew_event = getEventProSlugToSession();
    $event_type = getEventSalonCitySlugInSession();
    if ($renew_event) {
        $message['renew_event'] = $renew_event;
        $message['event_type'] = $event_type;
        $message['event_salon'] = getEventSalonSlugInSession();
        $message['event_pro'] = isProCustomer();
        $message['event_time'] = time() * 1000;
        $message['event_template_view'] = getEventSalonTemplate(getEventProSlugEventNameToSession());

        wc()->cart->empty_cart();
        removeEventProSlugToSession();
        removeEventSingleSalonSlugInSession();
    }

    if ($event_type == 'event' && !isProCustomer()) {
        $message['renew_event'] = true;
        $message['event_type'] = '0';
        $message['event_salon'] = '0';
        $message['event_pro'] = false;
        $message['event_time'] = '0';
        $message['event_template_view'] = '0';

        wc()->cart->empty_cart();
        removeEventSalonSlugInSession();
    }

    ob_start();
    woocommerce_mini_cart();
    $cart = ob_get_contents();
    ob_end_clean();

    $message['account_panel'] = do_shortcode('[lwa]');
    $message['cart'] = $cart;

    wp_send_json_success($message);

    die();
});

/**
 * event_data_sending
 */
Ajax::listen('event_data_sending', function () {
    $event_salon = !empty(request()->get('event_salon')) ? request()->get('event_salon') : null;
    $event_type = !empty(request()->get('event_type')) ? request()->get('event_type') : null;
    $event_salon_id = getEventSalonId($event_salon);
    $product_id = !empty(request()->get('product_id')) ? request()->get('product_id') : null;
    $p_message = [];
    $save_event = true;

    wc()->cart->empty_cart();

    if (!empty($event_salon_id)) {
        $event_type = getEventSalonCityRateBySalonID($event_salon_id);
    }

    if (!empty($product_id)) {
        $quatity = !empty(request()->get('quantity')) ? request()->get('quantity') : null;
        $p_message = cmrs_add_to_cart_product_ajax_message($product_id, $event_type, $quatity);
    }

    if (isset($p_message['success_add_to_cart']) && $p_message['success_add_to_cart'] == false) {
        $save_event = false;
    }

    if ($save_event) {
        if (!empty($event_salon_id)) {
            addEventSalonSlugToSession(SLUG_EVENT_SALON_QUERY, $event_salon);
        } else {
            removeEventSalonSlugInSession();
            addEventSalonCitySlugToSession(SLUG_EVENT_CITY_QUERY, $event_type);
        }
    }

    $event_template_view = getEventSalonTemplate($event_salon);
    $message = [
        'event_salon' => getEventSalonSlugInSession(),
        'event_type' => getEventSalonCitySlugInSession(),
        'event_pro' => isProCustomer(),
        'event_time' => time() * 1000,
        'event_template_view' => $event_template_view
    ];

    $message = $message + $p_message;

    wp_send_json_success($message);

    die();
});
/**
 * get event_modal_warning_template
 */
Ajax::listen('event_modal_warning_template', function () {
    $datas = [];
    $datas['page_type'] = request()->get('page_type');

    $event_modal_warning_template = View::make('components.popup.modal-event-warning-content', $datas)->render();
    $message = [
        'event_modal_time' => time() * 1000,
        'event_modal_warning_template' => $event_modal_warning_template
    ];

    wp_send_json_success($message);

    die();
});

/**
 * Add to cart product
 */
Ajax::listen('cmrs_product_add_to_cart', function () {
    $lang = getShortLangCode(request()->get('clang'));
    $product_id = request()->get('product_id');
    $quantity = empty(request()->get('quantity')) ? 1 : wc_stock_amount(request()->get('quantity'));
    $variation_id = absint(request()->get('variation_id'));
    $passed_validation = apply_filters('cmrs_woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);
    if ($passed_validation && 'publish' === $product_status) {
        do_action('cmrs_woocommerce_ajax_added_to_cart', $product_id, $lang);

        $add_to_cart = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
        if ($add_to_cart != false) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }

        $all_notices = WC()->session->get('wc_notices', array());
        $notices_html = [];

        if (isset($all_notices['success']) && !empty($all_notices['success'])) {
            ob_start();
            wc_get_template("notices/success.php", array(
                'notices' => array_filter($all_notices['success']),
            ));
            $notices_html[] = ob_get_clean();
        }

        if (isset($all_notices['error']) && !empty($all_notices['error'])) {
            ob_start();
            wc_get_template("notices/success.php", array(
                'notices' => array_filter($all_notices['error']),
            ));
            $notices_html[] = ob_get_clean();
        }

        if (isset($all_notices['notice']) && !empty($all_notices['notice'])) {
            ob_start();
            wc_get_template("notices/success.php", array(
                'notices' => array_filter($all_notices['notice']),
            ));
            $notices_html[] = ob_get_clean();
        }

        wc_clear_notices();

        ob_start();
        woocommerce_mini_cart();
        $object = ob_get_contents();
        ob_end_clean();

        $message = [
            'cart' => $object,
            'notices_html' => $notices_html
        ];

        wp_send_json_success($message);
    } else {
        $message = [
            'message' => __('Veuillez reessayer plus tard', THEME_TD),
            'product_url' => apply_filters('cmrs_woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
        ];

        wp_send_json_error($message);

        die();
    }

    wp_die();
});

/**
 * Refresh city data
 */
Ajax::listen('product_get_city', function () {
    $event = getEventSalonCitySlugInSession();

    $message = [
        'city' => $event
    ];

    wp_send_json_success($message);

    die();
});

/**
 * Refresh home btn event
 */
Ajax::listen('product_get_home_event_btn', function () {
    $home_btn = false;
    if (isProCustomer() && !isEventSalonSession()) {
        $home_btn = '<a href="' . get_permalink(wc_get_page_id('shop')) . '?reset_salon_slug=1&event_city=event"
       title="' . __('Accéder aux tarifs Event', THEME_TD) . '"
       class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u mx-auto">' . __('Accéder aux tarifs Event', THEME_TD) . '</a>';
    }
    $message = [
        'home_btn' => $home_btn
    ];

    wp_send_json_success($message);

    die();
});
/**
 * Refresh city data and salon
 */
Ajax::listen('product_get_city_salon', function () {
    $event = getEventSalonCitySlugInSession();
    $event_city = getEventSalonSlugInSession();
    $salon_id = 0;

    if (!empty($event_city)) {
        $args = array(
            'name' => $event_city,
            'post_type' => 'salon',
            'post_status' => ['publish', 'private'],
            'posts_per_page' => 1
        );
        $my_posts = get_posts($args);
        if ($my_posts) {
            $salon_id = $my_posts[0]->ID;
        }
    }

    $salon = get_post($salon_id);
    $view = '';
    if (!empty($salon)) {
        $datas['term_salon'] = $salon;
        $datas['view_link'] = true;
        $view = View::make('widgets.product-salon', $datas)->render();
    }

    $message = [
        'city' => $event,
        'salon' => $view,
    ];

    wp_send_json_success($message);

    die();
});