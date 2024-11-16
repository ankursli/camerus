<?php

namespace App\Http\Controllers;

use App\Hooks\Product;
use App\Hooks\Salon;
use App\StockSalDot;
use DOMDocument;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rules\In;
use OneLogin\Saml2\Auth;
use OneLogin\Saml2\Error;
use OneLogin\Saml2\Response;
use OneLogin\Saml2\Utils;
use Themosis\Core\Forms\FormHelper;
use Themosis\Core\Validation\ValidatesRequests;
use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\Filter;
use WP_Query;

class ProductController extends BaseController
{
    use FormHelper, ValidatesRequests;

    /**
     * Get post SEO Meta
     *
     * @param $id
     *
     * @return mixed
     */
    protected function getPostMetas($id)
    {
        $metatitle = get_post_meta($id, '_yoast_wpseo_title', true);
        $metadesc = get_post_meta($id, '_yoast_wpseo_metadesc', true);

        if (empty($metatitle)) {
            $metatitle = get_the_title($id);
        }
        if (empty($metadesc)) {
            $metadesc = get_bloginfo('description');
        }
        $args['metatitle'] = $metatitle;
        $args['metadesc'] = $metadesc;

        return $args;
    }

    /**
     * Get Post Thumbnail
     *
     * @param $id
     *
     * @return false|string
     */
    protected function getPostThumbnail($id)
    {
        $post_thumb = get_the_post_thumbnail_url($id);

        if (empty($post_thumb)) {
            return get_stylesheet_directory_uri()."/dist/images/head__logo-resoconfort.png";
        }

        return $post_thumb;
    }

    /**
     * Query get Salon list
     *
     * @return array
     */
    protected function getSalonList()
    {
        $post_args = [
            'numberposts' => 5,
        ];

        $salons = Salon::getSalon($post_args);

        return $salons;
    }

    public function indexSingle($page, $query)
    {
        global $product, $woocommerce, $sitepress;

        $args = [];

        $page_id = $page->ID;
        $product = wc_get_product($page_id);
        $reed_info = getReedDataInfo();
        $lang = ICL_LANGUAGE_CODE;

        $args['ID'] = $page_id;
        $args['page'] = $page;
        $salon_slug = getEventSalonSlugInSession();
        if (empty($salon_slug)) {
            $salon_slug = session(SLUG_EVENT_SALON_QUERY.'_reed');
            if (empty($salon_slug)) {
                $salon_slug = request()->get(SLUG_EVENT_SALON_QUERY);
            }
        }
        $salon_id = getEventSalonId($salon_slug);
        $salon = getEventTheSalon($salon_id);
        $args['term_salon'] = $salon;

        $args = array_merge($this->getPostMetas($page_id), $args);
        $args['post_thumb'] = $this->getPostThumbnail($page_id);

        $args['product'] = $product;
        $args['woocommerce'] = $woocommerce;
        $args['tags'] = getProductTagData($page_id);

        $args['pdf_file'] = get_permalink($page_id).'?pdf=1';
        $pdf_file = get_field('product_pdf_file', $page_id);
        if (! empty($pdf_file)) {
            $args['pdf_file'] = $pdf_file['url'];
        }
        $args['schema_img'] = get_field('product_schema_img', $page_id);
        $args['three_d_file'] = get_field('product_3d_file', $page_id);
        $args['three_d_link'] = get_field('product_3d_link', $page_id);
        $args['gallery_expo'] = get_field('product_gallery_expo', $page_id);

        $dotation_type = get_field('dotation_type', $page_id);
        $args['dotation_type'] = $dotation_type;
        $args['dotation_surface_min'] = get_field('dotation_surface_min', $page_id);
        $args['dotation_surface_max'] = get_field('dotation_surface_max', $page_id);
        $_dotation_items = get_field('dotation_items', $page_id);
        $_dotation_add_items = get_field('dotation_add_items', $page_id);
        $dotation_add_limit = get_field('dotation_add_limit', $page_id);
        $args['dotation_add_limit'] = ! empty($dotation_add_limit) ? $dotation_add_limit : 1;
        $args['dotation_add_text'] = get_field('dotation_add_text', $page_id);
        $args['dotation_add_items_session'] = getProductToDotation();

        $dotation_items = [];
        if (! empty($_dotation_items) && is_array($_dotation_items)) {
            foreach ($_dotation_items as $dotationItem) {
                $product_item_id = $dotationItem['dotation_item']->ID;
                $product_item_id = wpml_object_id_filter($product_item_id, 'product', true, $reed_info->Langue ?? $lang);
                $dotation_items[] = [
                    'dotation_item' => get_post($product_item_id),
                    'dotation_number' => $dotationItem['dotation_number'],
                ];
            }
        }
        $args['dotation_items'] = $dotation_items;


        $dotation_add_items = [];
        if (! empty($_dotation_add_items) && is_array($_dotation_add_items)) {
            foreach ($_dotation_add_items as $dotationItem) {
                $product_item_id = $dotationItem['dotation_add_item']->ID;
                $product_item_id = wpml_object_id_filter($product_item_id, 'product', true, $reed_info->Langue ?? $lang);
                $dotation_add_items[] = [
                    'dotation_add_item' => get_post($product_item_id),
                ];
            }
        }
        $args['dotation_add_items'] = $dotation_add_items;

        if (! empty($dotation_type)) {
            $args['dotation_stock'] = 0;
            $salon_ref = get_field('salon_id', $salon_id);
            $dotation_ref = $product->get_sku();
            $salon_stock = StockSalDot::where('id_salon', $salon_ref)
                ->where('id_dotation', $dotation_ref)
                ->first();
            if ($salon_stock == null) {
                $stock = 0;
            } else {
                $stock = $salon_stock->stock;
            }
            $args['dotation_stock'] = $stock;
        }

        $single_pdf = request()->get('pdf');
        if (! empty($single_pdf)) {
            return view('shop.single-pdf', $args);
        }

        if ($product->get_type() == 'dotation') {
            $reed_info = getReedDataInfo();
            $sitepress->switch_lang($reed_info->Langue ?? $lang);

            return view('shop.single-dotation', $args);
        }

        return view('shop.single', $args);
    }

    public function indexCart()
    {
        $args = [];
        return view('shop.cart', $args);
    }

    public function indexMyAccount()
    {
        $args = [];
        return view('shop.myaccount', $args);
    }

    public function indexProductSearch()
    {
        Filter::add('wpseo_title', function ($title) {
            $title = __('Résultat des recherches', THEME_TD).' - '.SITE_MAIN_SYS_NAME;
            return $title;
        }, 15);

        $inputs = request()->all() ? request()->all() : [];
        $input_orderby = request()->get('orderby') ?: false;
        $input_paged = request()->get('paged') ?: 1;
        $input_s = request()->get('s') ?: false;
        $view_all = request()->get('view') ?: false;

        if (! empty(request()->get('old-query')) || ! empty($view_all)) {
            $old_inputs = getSearchQueryToSession();
            $inputs = $old_inputs;
            if (empty($input_orderby)) {
                $input_orderby = array_key_exists('orderby', $old_inputs) ? $old_inputs['orderby'] : 'date';
            }
            $input_paged = array_key_exists('paged', $old_inputs) ? $old_inputs['paged'] : '1';
            $input_s = array_key_exists('s', $old_inputs) ? $old_inputs['s'] : false;
        }
        $inputs['orderby'] = $input_orderby;
        $inputs['paged'] = $input_paged;
        $inputs['s'] = $input_s;

        $salon = null;
        $salon_slug = getEventSalonSlugInSession(SLUG_EVENT_SALON_QUERY);
        if (! empty($salon_slug)) {
            $salon_id = getPostIdBySlug($salon_slug);
            $salon = get_post($salon_id);
        }

        $selected = request()->all();
        if (! empty($old_inputs)) {
            $selected = wp_parse_args($old_inputs, $selected);
        }
        $order = '';
        $orderby = '';
        if (! empty($input_orderby)) {
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
        $city = request()->get('event_type');
        if (empty($city)) {
            $city = getEventSalonCitySlugInSession();
        }
        $product_categories = array_key_exists('category', $selected) && ! empty($selected['category']) ? wc_clean($selected['category']) : false;
        $product_colors = array_key_exists('pa_color', $selected) && ! empty($selected['pa_color']) ? wc_clean($selected['pa_color']) : false;
        $product_tags = array_key_exists('product_tag', $selected) && ! empty($selected['product_tag']) ? wc_clean($selected['product_tag']) : false;
        $product_materials = array_key_exists('product_material', $selected) && ! empty($selected['product_material']) ? wc_clean($selected['product_material'])
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
        ];

        $query_product = getCustomProducts($args);

        /**
         * View args
         */
        addSearchQueryToSession($inputs);

        $args['inputs'] = $inputs;
        $args['orderby'] = $input_orderby;
        $args['salon'] = $salon;
        $args['orderby_items'] = product_get_orderby_items();
        $args['search_url'] = product_search_page_url();
        $args['products'] = $query_product['products'];
        $args['paged'] = $input_paged;
        $args['post_count'] = count($query_product['products']);
        $args['found_posts'] = $query_product['total'];
        $args['max_num_pages'] = $query_product['max_num_pages'];
        $args['current_url'] = Request::url();

        return view('pages.product-search', $args);
    }

    public function indexDotationList()
    {
        global $sitepress;

        Filter::add('wpseo_title', function ($title) {
            $title = __('Nos dotations', THEME_TD).' - '.SITE_MAIN_SYS_NAME;
            return $title;
        }, 15);

        $args = [];
        $dotations = [];
        $reed_info = getReedDataInfo();
        $type_stand = $reed_info->TypeStand;
        $sitepress->switch_lang('fr');

        $args['surface'] = 0;
        $args['salon_over_limit'] = false;
        $args['salon_over_start'] = true;
        $args['reed_info'] = $reed_info;
        $args['reed_token_used'] = isUsedTokenReed($reed_info->token);

        $surface = session('dotation_surface') ? session('dotation_surface') : $reed_info->SurfaceStand;
        if (! empty(request()->get('reload'))) {
            $decrypted = null;
            $encryptedValue = request()->get('reload');
            try {
                $decrypted = decrypt($encryptedValue);
            } catch (DecryptException $e) {
            }

            if ($decrypted == date('Y-m-d-H', time()) && ! empty($reed_info)) {
                $surface = (int)$reed_info->SurfaceStand;
            }
        }

        $args['surface'] = $surface;

        if (! empty($surface) && ! empty(request()->get(SLUG_EVENT_SALON_QUERY))) {
            $salon_slug = getEventSalonSlugInSession();
            if (empty($salon_slug)) {
                $salon_slug = request()->get(SLUG_EVENT_SALON_QUERY);
                addEventSalonSlugToSession(SLUG_EVENT_SALON_QUERY, $salon_slug);
                session(SLUG_EVENT_SALON_QUERY.'_reed', $salon_slug);
            }
            $salon_id = getEventSalonId($salon_slug);
            $salon = getEventTheSalon($salon_id);
            $salon_ref = get_field('salon_id', $salon_id);
            $args['term_salon'] = $salon;
            $salon_over_start = isOverSalonStartDate($salon_id);

            if (! $salon_over_start) {
                $args['salon_over_start'] = $salon_over_start;
                $salon_over_limit = isOverSalonLimitDate($salon_id);
                $args['salon_over_limit'] = $salon_over_limit;
            }

            $product_args['tax_query'][] = [
                'taxonomy' => 'product_type',
                'field' => 'slug',
                'terms' => 'dotation',
            ];

            if (! empty($type_stand)) {
                $type_stand = get_term_by('name', $type_stand, 'product_dotation_type');
                if (! empty($type_stand)) {
                    $type_stand_id = (string)$type_stand->term_id;
                    $product_args['meta_query'][] = [
                        'key' => 'dotation_type',
                        'value' => $type_stand_id,
                        'compare' => 'LIKE',
                    ];
                }
            }

            $product_args['posts_per_page'] = 500;
            $query_1 = Product::query($product_args);
            $products = $query_1->posts;
            $sitepress->switch_lang($reed_info->Langue);

            $dotation_max_surface = 0;
            $dotation_min_surface = 0;
            $dotations_per_surface_max = [];
            if (! empty($products) && ! empty($salon_ref)) {
                $dotations_per_surface_max = getDotationPerSurface($products, $salon_ref);
            }
            if (! empty($dotations_per_surface_max) && is_array($dotations_per_surface_max)) {
                $max_surface = array_key_first($dotations_per_surface_max);
                $surface_data_max = explode('-', $max_surface);
                $dotation_max_surface = $surface_data_max[0];

                $min_surface = array_key_last($dotations_per_surface_max);
                $surface_data_min = explode('-', $min_surface);
                $dotation_min_surface = $surface_data_min[1];
            }
            if (! empty($dotation_max_surface) && $dotation_max_surface >= $surface && $dotation_min_surface <= $surface) {
                if (! empty($dotations_per_surface_max) && is_array($dotations_per_surface_max)) {
                    foreach ($dotations_per_surface_max as $key_surface => $list_dotation) {
                        if (validateSurfaceDotation($key_surface, $surface)) {
                            $dotations = array_merge($dotations, $list_dotation);
                        }
                    }
                }
            }
            if (empty($dotations) && ! empty($dotations_per_surface_max) && is_array($dotations_per_surface_max) && $surface > $dotation_max_surface) {
                $dotations = reset($dotations_per_surface_max);
            }
            if (empty($dotations) && ! empty($dotations_per_surface_max) && is_array($dotations_per_surface_max) && $surface < $dotation_min_surface) {
                $dotations_per_surface_min = getDotationPerSurface($products, $salon_ref, 'min');
                $dotations = end($dotations_per_surface_min);
            }
        }

        $args['dotations'] = $dotations;

        if (empty($type_stand)) {
            $args['dotations'] = [];
        }

        /**
         * Remove other dotation from cart
         */
        $cart_items = WC()->cart->get_cart();
        if (! empty($cart_items)) {
            foreach ($cart_items as $key => $cartItem) {
                $item_product = wc_get_product($cartItem['product_id']);
                if ($item_product->is_type('dotation')) {
                    WC()->cart->remove_cart_item($key);
                }
            }
        }

        /**
         * Check existing user
         */
        $args['customer'] = null;
        $user_email = ! empty($reed_info) ? $reed_info->Email : null;
        if (is_user_logged_in() && ! empty($user_email) && ! empty($dotations) && ! $args['salon_over_limit'] && ! $args['salon_over_start']) {
            $current_user = wp_get_current_user();
            if (! empty($current_user) && $current_user->user_email != $user_email) {
                $user = get_user_by('email', $user_email);
                $args['customer'] = $user;
                /**
                 * Add a check reed user if no value #modal-reed-change-user
                 */
//                Action::add('wp_footer', function () {
//                    if (Route::currentRouteName() == 'dotation-list') {
//                        echo '<input type="hidden" name="cmrs-reed-change-user" id="cmrs-reed-change-user" />';
//                    }
//                }, 100);
            }
        }

        setEventProFlagToSession(true, $salon_slug);

        return view('pages.product-dotation-list', $args);
    }

    public function indexReedUrl($base64 = '')
    {
        if (! empty($base64)) {
            $reed_urls = explode('&', $base64);
            $reed_url = reset($reed_urls);
            if (! empty($reed_url)) {
                $reed_info = base64_decode($reed_url);
                if (! empty($reed_info)) {
                    $reed_info = json_decode($reed_info);
                    if (! empty($reed_info)) {
                        $reed_info->token = $reed_url;
                    }
                }
            }
        } else {
            $all_input = request()->all();
            $reed_info = (object)$all_input;
            $reed_info->token = null;
        }

        if (! empty($reed_info)) {
            if (property_exists($reed_info, 'Salon') && property_exists($reed_info, 'SurfaceStand')) {
                $reed_info->SurfaceStand = (int)$reed_info->SurfaceStand;
                if (! empty($reed_info->Langue)) {
                    $reed_info->Langue = strtolower($reed_info->Langue);
                    if ($reed_info->Langue != 'fr') {
                        $reed_info->Langue = 'en';
                    }
                } else {
                    $reed_info->Langue = 'fr';
                }
                addReedDataInfo($reed_info);
                $surface = $reed_info->SurfaceStand;
                $salon_ref = $reed_info->Salon;
                $salon = getSalonByRef($salon_ref);
                $salon_slug = '';
                if (! empty($salon)) {
                    $salon_slug = $salon->post_name;
                }

//                    if (true === false) {
//                        return redirect()->route('dotation-list-en')->with(['surface' => 12]);
//                    }

                if (! empty($salon_slug)) {
                    addEventSalonSlugToSession(SLUG_EVENT_SALON_QUERY, $salon_slug);
                    session(SLUG_EVENT_SALON_QUERY.'_reed', $salon_slug);
                }

                resetProductToDotation();

                return redirect()->route('dotation-list', [SLUG_EVENT_SALON_QUERY => $salon_slug])->with('dotation_surface', $surface);
            }
        } else {
            return redirect('/');
        }

        return redirect('/');
    }

    public function indexSsoReedUrl(Request $request)
    {
        $exhibitor = $this->authSsoReed($request);
        $exhibitor_dotation = $this->getExhibitorData($exhibitor);

        $reed_info = [];
//        $reed_info = [
//            'Langue' => '',
//            'Salon' => '',
//            'SurfaceStand' => '',
//            'NumStand' => '',
//            'TypeStand' => '',
//            'Email' => '',
//            'RaisonSociale' => '',
//            'Prenom' => '',
//            'Nom' => '',
//            'token' => null,
//        ];

        if (! empty($exhibitor) && ! empty($exhibitor_dotation)) {
            if (property_exists($exhibitor_dotation, 'eventEditionId') && ! empty($exhibitor_dotation->eventEditionId)) {
                $reed_info['Salon'] = $exhibitor_dotation->eventEditionId;
            }
            if (property_exists($exhibitor_dotation, 'id') && ! empty($exhibitor_dotation->id)) {
                $reed_info['token'] = $exhibitor_dotation->id.'-'.$exhibitor['organisation_id'].'-'.$exhibitor['id'];
            }
            if (property_exists($exhibitor_dotation, 'standInfo') && ! empty($exhibitor_dotation->standInfo)) {
                $standInfo = reset($exhibitor_dotation->standInfo);
                $reed_info['SurfaceStand'] = $standInfo->size;
                $reed_info['NumStand'] = $standInfo->name;
            }
            if (property_exists($exhibitor_dotation, 'entitlements') && ! empty($exhibitor_dotation->entitlements)) {
                foreach ($exhibitor_dotation->entitlements as $entitlement) {
                    if (property_exists($entitlement, 'productCode') && property_exists($entitlement, 'totalValue') && ! empty($entitlement->totalValue) && ! empty($entitlement->productCode)) {
                        $pack_list = [
                            'Pack Easy',
                            'Pack Identity',
                            'Pack Business',
                        ];
                        foreach ($pack_list as $pack) {
                            if (str_contains($entitlement->productCode, $pack)) {
                                $reed_info['SurfaceStand'] = $entitlement->totalValue;
                                switch ($pack) {
                                    case 'Pack Easy' :
                                        $reed_info['TypeStand'] = 'easy';
                                        break;
                                    case 'Pack Identity' :
                                        $reed_info['TypeStand'] = 'identity';
                                        break;
                                    case 'Pack Business' :
                                        $reed_info['TypeStand'] = 'business';
                                        break;
//                                case 'Pack Essentiels' :
//                                    $reed_info['TypeStand'] = 'essentiels';
//                                    break;
//                                case 'Pack Contact' :
//                                    $reed_info['TypeStand'] = 'contact';
//                                    break;
//                                case 'Pack Lounge' :
//                                    $reed_info['TypeStand'] = 'lounge';
//                                    break;
//                                case 'Pack Presence' :
//                                    $reed_info['TypeStand'] = 'presence';
//                                    break;
//                                case 'Pack Showroom' :
//                                    $reed_info['TypeStand'] = 'showroom';
//                                    break;
                                }
                            }
                        }
                    }
                }
            }
            if (property_exists($exhibitor_dotation, 'administrators') && ! empty($exhibitor_dotation->administrators)) {
                $administrators = reset($exhibitor_dotation->administrators);
                $reed_info['Email'] = $administrators->email;
                $reed_info['Prenom'] = $administrators->firstName;
                $reed_info['Nom'] = $administrators->lastName;
            }
            if (property_exists($exhibitor_dotation, 'companyName') && ! empty($exhibitor_dotation->companyName)) {
                $reed_info['RaisonSociale'] = $exhibitor_dotation->companyName;
            }
            if (property_exists($exhibitor_dotation, 'multilingual') && ! empty($exhibitor_dotation->multilingual)) {
                $e_lang = 'fr';
                if (is_array($exhibitor_dotation->multilingual)) {
                    $the_lang = reset($exhibitor_dotation->multilingual);
                    $e_lang = $the_lang->locale;
                }
                if ($e_lang === 'fr-fr') {
                    $reed_info['Langue'] = 'fr';
                } else {
                    $reed_info['Langue'] = 'en';
                }
            }
        }

        if (! empty($reed_info)) {
            $reed_info = (object)$reed_info;
            if (property_exists($reed_info, 'Salon') && property_exists($reed_info, 'SurfaceStand')) {
                $reed_info->SurfaceStand = (int)$reed_info->SurfaceStand;
                if (! empty($reed_info->Langue)) {
                    $reed_info->Langue = strtolower($reed_info->Langue);
                    if ($reed_info->Langue != 'fr') {
                        $reed_info->Langue = 'en';
                    }
                } else {
                    $reed_info->Langue = 'fr';
                }

                addReedDataInfo($reed_info);
                $surface = $reed_info->SurfaceStand;
                $salon_ref = $reed_info->Salon;
                $salon = getSalonByRef($salon_ref);
                $salon_slug = '';
                if (! empty($salon)) {
                    $salon_slug = $salon->post_name;
                }

                if (! empty($salon_slug)) {
                    addEventSalonSlugToSession(SLUG_EVENT_SALON_QUERY, $salon_slug);
                    session(SLUG_EVENT_SALON_QUERY.'_reed', $salon_slug);
                }

                resetProductToDotation();

                if (! property_exists($reed_info, 'TypeStand') || empty($reed_info->TypeStand)) {
                    if (! empty($salon)) {
                        return redirect(get_permalink($salon->ID));
                    }

                    return redirect('/');
                }

                return redirect()->route('dotation-list', [SLUG_EVENT_SALON_QUERY => $salon_slug])->with('dotation_surface', $surface);
            }
        } else {
            return redirect('/');
        }

        return redirect('/');
    }

    public
    function requestDotationAPI($exhibitor, $start = 0, $take = 100)
    {
        $id_expo = (isset($_GET['eventEdition'])) ? sanitize_text_field($_GET['eventEdition']) : null;
        if (empty($id_expo)) {
            $id_expo = get_field('id_expo_reed', 'option');
        }
        $exhibitor_data = [];
        $url = 'https://api.reedexpo.fr:3443/api/Dotation?start='.$start.'&take='.$take.'&idwinexpo='.$id_expo;
        $user_name = config('reedapi.user');
        $pwd = config('reedapi.password');
        $args = array(
            'timeout' => 60,
            'headers' => array(
                'Authorization' => 'Basic '.base64_encode($user_name.':'.$pwd)
            )
        );
        $data = wp_remote_request($url, $args);
        if (! is_wp_error($data)) {
            if (isset($data['body']) && ! empty($data['body'])) {
                $exhibitor_data = json_decode($data['body']);
            }
        } else {
            dump($data->get_error_message());
            die('API server error to get response');
        }

        return $exhibitor_data;
    }

    public
    function checkDotationOrgExh($data, $exhibitor)
    {
        $exhibitor_info = [];
        $totalCount = 0;
        if (! empty($data) && isset($data->data) && isset($data->data->exhibitingOrganisationsList)) {
            $exhibitingOrganisationsList = $data->data->exhibitingOrganisationsList;
            $totalCount = $exhibitingOrganisationsList->totalCount;
            if (! empty($exhibitingOrganisationsList->exhibitingOrganisations)) {
                foreach ($exhibitingOrganisationsList->exhibitingOrganisations as $exhOrg) {
                    if ($exhOrg->id === $exhibitor['organisation_id']) {
//                    if ($exhOrg->id === 'exh-00499015-c039-45de-a71e-2bd17e7131ce') {
                        $exhibitor_info = $exhOrg;
                        break;
                    }
                }
            }
        }

        return ['exhibitor_info' => $exhibitor_info, 'totalCount' => $totalCount];
    }

    public
    function getExhibitorData($exhibitor)
    {
        $exhibitor_info = [];
        $take = 100;
        $exhibitor_data = $this->requestDotationAPI($exhibitor);
        $exhibitor_dotation = $this->checkDotationOrgExh($exhibitor_data, $exhibitor);
        $totalCount = (int)$exhibitor_dotation['totalCount'];
        if (! empty($exhibitor_dotation['exhibitor_info'])) {
            $exhibitor_info = $exhibitor_dotation['exhibitor_info'];
        } else if (empty($exhibitor_dotation['exhibitor_info']) && ! empty($totalCount) && $totalCount > $take) {
            for ($i = $take; $i < $totalCount; $i += 100) {
                $exhibitor_data = $this->requestDotationAPI($exhibitor, $i, $take);
                $exhibitor_dotation = $this->checkDotationOrgExh($exhibitor_data, $exhibitor);
                if (! empty($exhibitor_dotation['exhibitor_info'])) {
                    $exhibitor_info = $exhibitor_dotation['exhibitor_info'];
                    break;
                }
            }
        }

        return $exhibitor_info;
    }

    public
    function GetRemotePublicKey()
    {
        $filename = 'onelogin/certs/remote/RX-PublicKey-3.cer';
        if ($der_data = @file_get_contents(Storage::path($filename))) {
            $pem = chunk_split(base64_encode($der_data), 64, "\n");
            $pem = "-----BEGIN CERTIFICATE-----\n".$pem."-----END CERTIFICATE-----\n";
            return $pem;
        } else {
            die ('Fatal Error: Remote Public Key not found at '.$filename);
        }
    }

    public
    function GetLocalPrivateKey()
    {
        $filename = 'onelogin/certs/local/camerus.pem';
        if ($der_data = @file_get_contents(Storage::path($filename))) {
            return $der_data;
        } else {
            die ('Fatal Error: Local Private Key not found at '.$filename);
        }
    }

    public
    function authSsoReed(Request $request)
    {
        if (isset($_POST['SAMLResponse']) && ! empty($_POST['SAMLResponse'])) {
            define('ONELOGIN_CUSTOMPATH', Storage::path('onelogin/'));
            try {
                $settings = config('samlToolkits');
                $settings['sp']['privateKey'] = $this->GetLocalPrivateKey();
                $settings['idp']['x509cert'] = $this->GetRemotePublicKey();
                $auth = new Auth($settings);
                $settings = $auth->getSettings();
                $response = new Response($settings, $_POST['SAMLResponse']);
                $errors = $response->getError();
                if (empty($errors)) {
                    $attributes = $response->getAttributes();
                    $exhibitor['admin_email'] = isset($attributes['urn:rx:digital:exhibitor:admin_email']) ? reset($attributes['urn:rx:digital:exhibitor:admin_email']) : '';
                    $exhibitor['id'] = isset($attributes['urn:rx:digital:exhibitor:id']) ? reset($attributes['urn:rx:digital:exhibitor:id']) : '';
                    $exhibitor['user_id'] = isset($attributes['urn:rx:digital:userId']) ? reset($attributes['urn:rx:digital:userId']) : '';
                    $exhibitor['organisation_id'] = isset($attributes['urn:rx:digital:exhibitingOrganisation:id']) ? reset($attributes['urn:rx:digital:exhibitingOrganisation:id']) : '';

                    return $exhibitor;
                }

                throw new Error(
                    'Invalid SP metadata: '.implode(', ', $errors),
                    Error::METADATA_SP_INVALID
                );
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public
    function indexReedExport()
    {
        $crypt = request()->get('key');
        if (! empty($crypt)) {
            $decrypt = base64_decode($crypt);
//            $pass = Hash::check(REED_EXPORT_CODE, $decrypt);
            $pass = ! empty($decrypt) && $decrypt === REED_EXPORT_CODE ? true : false;

            if (! empty($decrypt) && $pass) {
                $export_file_name = 'reedexport/export-reed.json';
                if (! empty(Storage::exists($export_file_name))) {
                    $export_json = Storage::get($export_file_name);

                    echo $export_json;
                    return '';
                }
            }
        }

        return redirect('/');
    }

    public
    function indexLoad3DFiles($slug = '')
    {
        $upl = wp_upload_dir();
        $allFiles = File::allfiles($upl['basedir'].'/3d-product-files/');

        if (! empty($allFiles) && is_array($allFiles)) {
            foreach ($allFiles as $file) {
                $file_name = $file->getFilename();
                $file_title = $file->getBasename('.zip');
                $attachment_name = '3D-'.$file_title;
                $attachment_id = getAttachmentByName($attachment_name);

                if (empty($attachment_id)) {
                    $target = $upl['basedir'].'/3d-product-files/'.$file_name;
                    $filetype = wp_check_filetype($target);

                    $attachment = array(
                        'post_mime_type' => $filetype['type'],
                        'post_title' => $attachment_name,
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    $attachment_id = wp_insert_attachment($attachment, $target);
                }

                if (! empty($attachment_id)) {
                    $model_3d_term_id = 43;
                    wp_set_post_terms($attachment_id, [$model_3d_term_id], SLUG_TAX_MEDIA_CATEGORY, true);

                    $sku = $file_title;
                    $product_id = wc_get_product_id_by_sku($sku);
                    if (! empty($product_id)) {
                        update_field('product_3d_file', $attachment_id, $product_id);
                    }

                    $sku_gb = '';
                    if (ICL_LANGUAGE_CODE !== 'fr') {
                        $sku_gb = $sku.'-GB';
                    }
                    $product_gb_id = wc_get_product_id_by_sku($sku_gb);
                    if (! empty($product_gb_id)) {
                        update_field('product_3d_file', $attachment_id, $product_gb_id);
                    }
                }
            }

            echo 'Upload end ...';
        }

        return redirect('/');
    }
}
