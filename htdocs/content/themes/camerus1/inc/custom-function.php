<?php

use App\Hooks\Salon;
use App\StockSalDot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\Filter;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use ZipStream\Exception\FileNotFoundException;
use ZipStream\Exception\FileNotReadableException;
use ZipStream\Option\Archive;

/**
 * Add APP Option page
 */
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Paramètres généraux du thème',
        'menu_title' => __('Options du site', THEME_TD),
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));

}

/**
 * subpage acf option
 */
if (function_exists('acf_add_options_sub_page')) {
    acf_add_options_sub_page(array(
        'page_title' => 'Paramètres Email',
        'menu_title' => __('Emails', THEME_TD),
        'parent_slug' => 'theme-general-settings',
        'menu_slug' => 'theme-general-email-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
    acf_add_options_sub_page(array(
        'page_title' => 'Paramètres Event',
        'menu_title' => __('Events', THEME_TD),
        'parent_slug' => 'theme-general-settings',
        'menu_slug' => 'theme-general-event-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

//if (!defined(ICL_LANGUAGE_CODE)) {
//    define('ICL_LANGUAGE_CODE', 'fr');
//}
//
//if (!function_exists('icl_get_languages')) {
//    function icl_get_languages()
//    {
//        return [];
//    }
//}

Filter::add('wpml_ls_template_paths', function ($paths) {
    if (!empty($paths) && is_array($paths)) {
        $new_paths = [];
        foreach ($paths as $key => $path) {
            if (strpos($path, '/home/httpdocs') !== false) {
                $path = str_replace('/home/httpdocs', WPML_TEMPLATES_PATHS_CUSTOM, $path);
            }
            $new_paths[$key] = $path;
        }
        $paths = $new_paths;
    }

    return $paths;
}, 20, 1);

/**
 * Get Open Graph infos
 *
 * @param int $ID
 *
 * @return array
 */
function get_open_graph($ID)
{
    $open_graph = [];

    //Metatitle
    $metatitle = get_post_meta($ID, '_yoast_wpseo_title', true);

    if (empty($metatitle)) {
        $metatitle = get_the_title($ID);
    }

    $open_graph['metatitle'] = $metatitle;

    //Metadesc
    $metadesc = get_post_meta($ID, '_yoast_wpseo_metadesc', true);

    if (empty($metadesc)) {
        $metadesc = get_field('top_view_desc', $ID);

        if (empty($metadesc)) {
            $metadesc = get_bloginfo('description');
        }
    }

    $open_graph['metadesc'] = $metadesc;

    //Post thumbnail
    $post_thumb = get_the_post_thumbnail_url($ID);

    if (empty($post_thumb)) {
        $post_thumb = get_stylesheet_directory_uri() . '/dist/images/header-logo.svg';
    }

    $open_graph['post_thumb'] = $post_thumb;

    return $open_graph;
}

/**
 * Get product category
 */
function getProductCategory($id)
{
    $terms = get_the_terms($id, 'product_cat');
    if (!empty($terms)) {
        return reset($terms);
    }

    return (object)array('name' => ' ');
}

/**
 * Add WC endpoint page
 */
function addEndpointWoocommerce($name, $slug, $content = '')
{
    Action::add('init', function () use ($name, $slug) {
        add_rewrite_endpoint($slug, EP_ROOT | EP_PAGES);
    });

    Filter::add('woocommerce_account_menu_items', function ($items) use ($name, $slug) {
        $items[$slug] = __($name, THEME_TD);

        return $items;
    }, 10, 1);

    Action::add('woocommerce_account_' . $slug . '_endpoint', function () use ($name, $slug, $content) {
        if (!empty($content)) {
            echo $content->render();
        }
    }, 20);
}

/**
 * Move item in array DOWN
 *
 * @param $a
 * @param $x
 *
 * @return array
 */
function downItemArray($a, $x)
{
    if (count($a) - 1 > $x) {
        $b = array_slice($a, 0, $x, true);
        $b[] = $a[$x + 1];
        $b[] = $a[$x];
        $b += array_slice($a, $x + 2, count($a), true);
        return $b;
    }

    return $a;
}

/**
 * Move item in array UP
 *
 * @param $a
 * @param $x
 *
 * @return array
 */
function upItemArray($a, $x)
{
    if ($x > 0 and $x < count($a)) {
        $b = array_slice($a, 0, ($x - 1), true);
        $b[] = $a[$x];
        $b[] = $a[$x - 1];
        $b += array_slice($a, ($x + 1), count($a), true);
        return $b;
    }

    return $a;
}

/**
 * Move item in array
 *
 * @param $array
 * @param $newPos
 * @param $oldPos
 *
 * @return array
 */
function moveElementArray(&$array, $oldPos, $newPos)
{
    $el = array_splice($array, $oldPos, 1);
    $p1 = array_slice($array, 0, $newPos);
    $p2 = array_slice($array, $newPos, count($array) - 1);

    return array_merge($p1, $el, $p2);
}

/**
 * Get template blade
 *
 * @param $path
 *
 * @return
 */
function getBladeTemplatePath($path)
{
    $datas = [];

    $datas['path'] = $path;
    $view = View::make('shop.rendered-view', $datas)->render();
    return $view->getPath();
}


/**
 * Check if user has salon in favoris
 *
 * @param $salon_id
 *
 * @return bool|mixed
 */
function checkSalonUserFavoris($salon_id)
{
    $current_user_id = get_current_user_id();
    $user_salons = getSalonUserFavoris();

    if (!empty($salon_id) && !empty($current_user_id) && !empty($user_salons) && is_array($user_salons) && array_key_exists($salon_id, $user_salons)) {

        return $user_salons[(string)$salon_id];
    }

    return false;
}

/**
 * Remove from user salon favoris
 *
 * @param $salon_id
 *
 * @return bool
 */
function deleteSalonUserFavoris($salon_id)
{
    $current_user_id = get_current_user_id();
    $user_salons = getSalonUserFavoris();

    if (!empty($salon_id) && !empty($current_user_id) && !empty($user_salons) && is_array($user_salons) && array_key_exists($salon_id, $user_salons)) {
        unset($user_salons[(string)$salon_id]);
        update_user_meta($current_user_id, 'agenda_favoris', serialize($user_salons));

        return true;
    }

    return false;
}

/**
 * Set user favoris
 *
 * @param $salon_id
 *
 * @return bool
 */
function setSalonUserFavoris($salon_id)
{
    $current_user_id = get_current_user_id();
    $user_salons = getSalonUserFavoris();

    if (!empty($salon_id) && !empty($current_user_id)) {
        $user_salons[$salon_id] = [
            'salon_id' => $salon_id,
            'user_id' => $current_user_id,
            'add_date' => time()
        ];

        update_user_meta($current_user_id, 'agenda_favoris', serialize($user_salons));

        return true;
    }

    return false;
}

/**
 * Add Salon to user favoris
 *
 * @param $salon_id
 *
 * @return bool
 */
function addSalonUserFavoris($salon_id)
{
    $current_user_id = get_current_user_id();

    if (!empty($salon_id) && !empty($current_user_id)) {

        $user_salons = getSalonUserFavoris();

        if (!empty($user_salons) && is_array($user_salons)) {
            foreach ($user_salons as $key => $salon) {
                if (array_key_exists($salon_id, $salon)) {
                    return false;
                }
            }
        }

        setSalonUserFavoris($salon_id);

        return true;
    }

    return false;
}

/**
 * Get User favorit salon
 *
 * @return bool|mixed
 */
function getSalonUserFavoris()
{
    $current_user_id = get_current_user_id();

    if (!empty($current_user_id)) {
        $agenda_favoris = get_user_meta($current_user_id, 'agenda_favoris', true);

        if (!empty($agenda_favoris)) {
            return unserialize($agenda_favoris, ['allowed_classes' => false]);
        }
    }

    return array();
}

/**
 * Get Post ID by slug
 *
 * @param $slug
 *
 * @return bool|int
 */
function getPostIdBySlug($slug)
{
    global $sitepress;

    $sitepress->switch_lang(ICL_LANGUAGE_CODE);

    if (!empty($slug)) {
        $args = array(
            'name' => $slug,
            'post_type' => 'salon',
            'post_status' => ['publish', 'private'],
            'posts_per_page' => 1,
            'suppress_filters' => false
        );
        $my_posts = get_posts($args);
        if ($my_posts) {
            return $my_posts[0]->ID;
        }
    }

    return false;
}

/**
 * @param $salon_slug
 *
 * @return bool|string
 */
function getCityBySalonSlug($salon_slug)
{
    if (!empty($salon_slug)) {
        $salon_id = getPostIdBySlug($salon_slug);
        if (!empty($salon_id)) {
            $salon = get_post($salon_id);
            if (!empty($salon)) {
                $city_rate = get_field('salon_city_rate', $salon_id);
                if (!empty($city_rate)) {
                    $salon_city_term = get_term($city_rate, 'pa_city');
                    if (!empty($salon_city_term) && $salon_city_term instanceof WP_Term) {
                        return $salon_city_term->slug;
                    }
                }
            }
        }
    }

    return false;
}

function getSalonRateById($salon_id)
{
    $city_rate = get_field('salon_city_rate', $salon_id);
    if (!empty($city_rate)) {
        $salon_city_term = get_term($city_rate, 'pa_city');
        if (!empty($salon_city_term) && $salon_city_term instanceof WP_Term) {
            $slug = $salon_city_term->slug;
            $term_id = $salon_city_term->term_id;
            $rent_price_code = get_field('app_rent_plus_price_code', 'pa_city_'.$term_id);
            if(!empty($rent_price_code)) {
                return $rent_price_code;
            }

            if (!empty($slug)) {
                switch ($slug) {
                    case 'paris' :
                        return 'S';
                        break;
                    case 'region' :
                        return 'T';
                        break;
                    case 'event' :
                        return 'U';
                        break;
                    default:
                        return $slug;
                        break;
                }
            }
        }
    }

    return false;
}

/**
 * Get salon ID
 *
 * @param $slug_or_id
 *
 * @return bool|int
 */
function getEventSalonId($slug_or_id)
{
    if (preg_match("/[a-zA-Z]/", $slug_or_id)) {
        return getPostIdBySlug($slug_or_id);
    }

    return (int)$slug_or_id;
}

/**
 * Get salon Object
 *
 * @param $id
 *
 * @return bool|int
 */
function getEventTheSalon($id)
{
    $salon_id = (int)$id;

    if (!empty($salon_id)) {
        $query = Salon::getSalon(['post__in' => [$salon_id], 'numberposts' => 1]);
        if (!empty($query) && is_array($query)) {
            return reset($query);
        }
    }

    return null;
}

/**
 * @param $ref
 *
 * @return int|mixed|WP_Post|null
 */
function getSalonByRef($ref)
{
    $args = array(
        'post_status' => ['publish', 'private'],
        'meta_key' => 'salon_id',
        'meta_value' => $ref,
        'numberposts' => 1
    );
    $query = Salon::getSalon($args);
    if (!empty($query) && is_array($query)) {
        return reset($query);
    }

    return null;
}

/**
 * Add Event Salon to session
 *
 * @param string $slug
 * @param $salon_slug
 */
function addEventSalonSlugToSession($slug = SLUG_EVENT_SALON_QUERY, $salon_slug)
{
    if (!session_id()) {
        session_start();
    }

    $_SESSION[$slug] = $salon_slug;

    addEventSalonCitySlugToSession(SLUG_EVENT_CITY_QUERY);
}

/**
 * Add Event Salon city to session
 *
 * @param string $slug
 * @param string $city_slug
 */
function addEventSalonCitySlugToSession($slug = SLUG_EVENT_CITY_QUERY, $city_slug = '')
{
    if (!session_id()) {
        session_start();
    }

    $salon = getEventSalonObjectInSession();
    if (!empty($salon)) {
        $_SESSION[$slug] = getEventSalonCityRateBySalonID($salon->ID);
    } elseif (!empty($city_slug)) {
        setEventSalonCitySlugToSession($city_slug);
    }
}

function getEventSalonCityRateBySalonID($salon_id)
{
    $rate_city_id = get_field('salon_city_rate', $salon_id);
    if (!empty($rate_city_id)) {
        $rate_city = get_term($rate_city_id, SLUG_PRODUCT_TAX_ATTRIBUT_CITY);
        return $rate_city->slug;
    } else {
        $term = getPrimaryTaxTerm('salon_city', true, $salon->ID);
        if (!empty($term)) {
            $rate_city = $term->slug;
            if (checkEventSalonCitySlugToSession($rate_city)) {
                return $rate_city;
            }
        }
    }
}

/**
 * @param $city_slug
 */
function setEventSalonCitySlugToSession($city_slug)
{
    if (!session_id()) {
        session_start();
    }

    if (!empty($city_slug)) {
//        if (isProCustomer()) {
//            $city_slug = 'event';
//        }
        if (checkEventSalonCitySlugToSession($city_slug)) {
            $_SESSION[SLUG_EVENT_CITY_QUERY] = $city_slug;
            $current_city = getEventSalonCitySlugInSession();
            if ($current_city !== $city_slug) {
                wc()->cart->empty_cart();
            }
        }
    }
}

/**
 * @param $city_slug
 *
 * @return bool
 */
function checkEventSalonCitySlugToSession($city_slug)
{
    if (!empty($city_slug)) {
        $term = get_term_by('slug', $city_slug, 'pa_city');

        if (!empty($term) && $term instanceof WP_Term) {
            return $term->slug;
        }
    }

    return false;
}

/**
 * Get Event Salon in session
 *
 * @param string $slug
 *
 * @return mixed
 */
function getEventSalonSlugInSession($slug = SLUG_EVENT_SALON_QUERY)
{
    if (!session_id()) {
        session_start();
    }

    $event_slug = null;
    if (!empty($_SESSION) && array_key_exists($slug, $_SESSION)) {

        $event_slug = $_SESSION[$slug];
    }

    if (empty($event_slug)) {
        $checkout_event_slug = wc_clean(request()->get('_event-slug'));
        if (!empty($checkout_event_slug)) {
            $event_slug = trim($checkout_event_slug);
        }
    }

    return $event_slug;
}

/**
 * Get Event Salon city rate in session
 *
 * @param string $slug
 *
 * @return mixed
 */
function getEventSalonCitySlugInSession($slug = SLUG_EVENT_CITY_QUERY)
{
    global $woocommerce;

    if (!session_id()) {
        session_start();
    }

    $event_type = null;
    if (!empty($_SESSION) && array_key_exists($slug, $_SESSION)) {
        $event_type = $_SESSION[$slug];
        setEventSalonCitySlugDefaultInSession(false);
    }

    if (empty($event_type) && !empty($woocommerce->cart->cart_contents)) {
        foreach ($woocommerce->cart->cart_contents as $key => $item) {
            if (array_key_exists('event_type', $item)) {
                $event_type = $item['event_type'];
                setEventSalonCitySlugToSession($event_type);
                setEventSalonCitySlugDefaultInSession(false);
                break;
            }
        }
    }

    if (empty($event_type)) {
        $checkout_event_type = wc_clean(request()->get('_event-type'));
        if (!empty($checkout_event_type)) {
            $event_type = trim($checkout_event_type);
            setEventSalonCitySlugDefaultInSession(false);
        }
    }

    if (empty($event_type)) {
        setEventSalonCitySlugDefaultInSession(true);
        $event_default_slug = get_field('app_event_type_default_slug', 'option');
        return $event_default_slug->slug ?? DEFAULT_SLUG_EVENT_TYPE;
    }

    return $event_type;
}

function isEventSalonCitySlugDefaultInSession($slug = SLUG_EVENT_TYPE_DEFAULT)
{
    if (!session_id()) {
        session_start();
    }

    if (!empty($_SESSION) && array_key_exists($slug, $_SESSION)) {
        return $_SESSION[$slug];
    }

    return false;
}

function setEventSalonCitySlugDefaultInSession($value = false)
{
    if (!session_id()) {
        session_start();
    }

    $_SESSION[SLUG_EVENT_TYPE_DEFAULT] = $value;
}

/**
 * Get Event Salon city rate in session
 *
 * @param string $slug
 *
 * @return mixed
 */
function getEventSalonCityDataInSession($slug = SLUG_EVENT_CITY_QUERY)
{
    global $product, $getEventSalonCityDataInSession;

    // Ensure visibility.
    if (empty($product) || !$product->is_visible()) {
        return;
    }

    $datas = [
        'price_html' => '',
        'price' => '',
        'city' => '',
        'city_slug' => '',
    ];
    $product_id = $product->get_id();
    $salon_city = getEventSalonCitySlugInSession();
    $variation_id = 0;

    if (!empty($getEventSalonCityDataInSession) && array_key_exists('product_id', $getEventSalonCityDataInSession)
        && $getEventSalonCityDataInSession['product_id'] == $product_id
    ) {
        $datas = $getEventSalonCityDataInSession['datas'];
    } else {
        $v_product = new WC_Product_Variable($product_id);
        $variations = $v_product->get_available_variations();
        if (!empty($variations) && is_array($variations)) {
            foreach ($variations as $variation) {
                if (array_key_exists('attributes', $variation) && array_key_exists('attribute_pa_city', $variation['attributes'])
                    && $variation['attributes']['attribute_pa_city'] == $salon_city
                ) {
                    $variation_id = $variation['variation_id'];
                    if (!empty($variation_id)) {
                        $variable_product = wc_get_product($variation_id);
                        $datas['price_html'] = $variable_product->get_price_html();
                        $datas['price'] = $variable_product->get_price();
                        $datas['city'] = $variable_product->get_attribute(SLUG_PRODUCT_TAX_ATTRIBUT_CITY);
                        $attributes = $variable_product->get_attributes();
                        if (!empty($attributes) && array_key_exists(SLUG_PRODUCT_TAX_ATTRIBUT_CITY, $attributes)) {
                            $datas['city_slug'] = $attributes[SLUG_PRODUCT_TAX_ATTRIBUT_CITY];
                        }

                        $getEventSalonCityDataInSession = [
                            'product_id' => $product_id,
                            'datas' => $datas
                        ];

                        break;
                    }
                }
            }
        }
    }

    return $datas;
}

/**
 * Remove Event Salon in session
 *
 * @param string $slug
 */
function removeEventSalonSlugInSession($slug = SLUG_EVENT_SALON_QUERY)
{
    if (!session_id()) {
        session_start();
    }

    if (!empty($_SESSION) && array_key_exists($slug, $_SESSION)) {
        unset($_SESSION[$slug]);
    }
    if (!empty($_SESSION) && array_key_exists(SLUG_EVENT_CITY_QUERY, $_SESSION)) {
        unset($_SESSION[SLUG_EVENT_CITY_QUERY]);
    }
}

/**
 * Remove Salon No Type
 *
 * @param string $slug
 */
function removeEventSingleSalonSlugInSession($slug = SLUG_EVENT_SALON_QUERY)
{
    if (!session_id()) {
        session_start();
    }

    if (!empty($_SESSION) && array_key_exists($slug, $_SESSION)) {
        unset($_SESSION[$slug]);
    }
}

/**
 * Get Event Salon Object in session
 */
function getEventSalonObjectInSession()
{
    $slug = getEventSalonSlugInSession();
    if (!empty($slug)) {
        $args = array(
            'name' => $slug,
            'post_type' => 'salon',
            'post_status' => ['publish', 'private'],
            'posts_per_page' => 1
        );
        $my_posts = Salon::getSalon($args);
        if (!empty($my_posts) && is_array($my_posts)) {
            return reset($my_posts);
        }
    }

    return false;
}

/**
 * @param $status
 * @param null $event_name
 */
function setEventProFlagToSession($status, $event_name = null)
{
    if (!session_id()) {
        session_start();
    }

    if (!empty($status)) {
        $_SESSION[SLUG_STATUS_PRO_SESSION_SALON] = $status;
    }

    if (!empty($data)) {
        $_SESSION[SLUG_STATUS_PRO_SESSION_SALON_DATA] = $event_name;
    }
}

/**
 * @param bool $with_data
 * @return mixed|null
 */
function getEventProSlugToSession()
{
    if (!session_id()) {
        session_start();
    }

    if (array_key_exists(SLUG_STATUS_PRO_SESSION_SALON, $_SESSION) && !empty($_SESSION[SLUG_STATUS_PRO_SESSION_SALON])) {

        return $_SESSION[SLUG_STATUS_PRO_SESSION_SALON];
    }

    return false;
}

/**
 * @return mixed|null
 */
function getEventProSlugEventNameToSession()
{
    if (!session_id()) {
        session_start();
    }

    if (array_key_exists(SLUG_STATUS_PRO_SESSION_SALON_DATA, $_SESSION) && !empty($_SESSION[SLUG_STATUS_PRO_SESSION_SALON_DATA])) {

        return $_SESSION[SLUG_STATUS_PRO_SESSION_SALON_DATA];
    }

    return false;
}

/**
 */
function removeEventProSlugToSession()
{
    if (!session_id()) {
        session_start();
    }

    if (isset($_SESSION[SLUG_STATUS_PRO_SESSION_SALON])) {
        unset($_SESSION[SLUG_STATUS_PRO_SESSION_SALON]);
    }
}

/**
 * @param $event_salon
 * @return mixed|null
 */
function getEventSalonTemplate($event_salon = '')
{
    if (empty($event_salon)) {
        $event_salon = getEventSalonSlugInSession();
    }
    $event_template_view = null;
    $salon = null;
    $datas = [];
    if (!empty($event_salon)) {
        $args = array(
            'name' => $event_salon,
            'post_type' => 'salon',
            'post_status' => ['publish', 'private'],
            'posts_per_page' => 1
        );
        $my_posts = get_posts($args);
        if ($my_posts) {
            $salon_id = $my_posts[0]->ID;
            $salon = get_post($salon_id);
        }
    }

    $datas['term_salon'] = $salon;
    $datas['view_link'] = true;

    return View::make('widgets.product-salon', $datas)->render();
}

/**
 * Add Search Query product to session
 *
 * @param $query
 */
function addSearchQueryToSession($query)
{
    if (!session_id()) {
        session_start();
    }
    $_SESSION[SLUG_SEARCH_QUERY_SESSION_PRODUCT] = $query;
}

/**
 * Remove Search Query product to session
 *
 */
function removeSearchQueryToSession()
{
    if (!session_id()) {
        session_start();
    }

    if (!empty($_SESSION) && array_key_exists(SLUG_SEARCH_QUERY_SESSION_PRODUCT, $_SESSION)) {
        unset($_SESSION[SLUG_SEARCH_QUERY_SESSION_PRODUCT]);
    }
}

/**
 * Add Reed Info to session
 *
 * @param $data
 */
function addReedDataInfo($data)
{
    if (!session_id()) {
        session_start();
    }
    $_SESSION[SLUG_REED_DATA_INFO] = $data;
}

/**
 * Remove Reed Info to session
 *
 */
function removeReedDataInfo()
{
    if (!session_id()) {
        session_start();
    }
    if (!empty($_SESSION) && array_key_exists(SLUG_REED_DATA_INFO, $_SESSION)) {
        unset($_SESSION[SLUG_REED_DATA_INFO]);
    }
}

/**
 * Get Event Salon in session
 *
 * @return mixed
 */
function getReedDataInfo()
{
    if (!session_id()) {
        session_start();
    }
    if (!empty($_SESSION) && array_key_exists(SLUG_REED_DATA_INFO, $_SESSION)) {

        return $_SESSION[SLUG_REED_DATA_INFO];
    }

    return null;
}

/**
 * Get Search Query product to session
 *
 */
function getSearchQueryToSession()
{
    if (!session_id()) {
        session_start();
    }
    return array_key_exists(SLUG_SEARCH_QUERY_SESSION_PRODUCT, $_SESSION) ? $_SESSION[SLUG_SEARCH_QUERY_SESSION_PRODUCT] : [];
}

/**
 * Add product dotation to session
 *
 * @param $product_id
 * @param $dotation_id
 * @param $quantity
 */
function addProductToDotation($dotation_id, $product_id, $quantity)
{
    if (!session_id()) {
        session_start();
    }
    $city = getEventSalonSlugInSession();
    $_SESSION[SLUG_REED_PRODUCT_DOTATION][$product_id] = [
        'dotation_id' => $dotation_id,
        'product_id' => $product_id,
        'quantity' => $quantity,
        'city' => $city,
    ];
}

/**
 * Get product dotation in session
 *
 */
function getProductToDotation()
{
    if (!session_id()) {
        session_start();
    }

    if (!empty($_SESSION) && array_key_exists(SLUG_REED_PRODUCT_DOTATION, $_SESSION)) {
        return $_SESSION[SLUG_REED_PRODUCT_DOTATION];
    }

    return null;
}

/**
 * Remove product dotation to session
 *
 * @param $product_id
 */
function removeProductToDotation($product_id)
{
    if (!session_id()) {
        session_start();
    }

    if (!empty($_SESSION) && array_key_exists(SLUG_REED_PRODUCT_DOTATION, $_SESSION)) {
        if (!empty($_SESSION[SLUG_REED_PRODUCT_DOTATION]) && array_key_exists($product_id, $_SESSION[SLUG_REED_PRODUCT_DOTATION])) {
            unset($_SESSION[SLUG_REED_PRODUCT_DOTATION][$product_id]);
        }
    }
}

/**
 * Remove product dotation to session
 *
 */
function resetProductToDotation()
{
    if (!session_id()) {
        session_start();
    }

    if (!empty($_SESSION) && array_key_exists(SLUG_REED_PRODUCT_DOTATION, $_SESSION)) {
        unset($_SESSION[SLUG_REED_PRODUCT_DOTATION]);
    }
}

/**
 * Get primary taxonomy term (YoastSEO).
 *
 * @param mixed $taxonomy Taxonomy to check for.
 * @param boolean $term_as_obj Whether to return an object or the term name.
 * @param int $post_id Post ID.
 *
 * @return mixed                The primary term.
 */
function getPrimaryTaxTerm($taxonomy = 'category', $term_as_obj = true, $post_id = 0)
{
    if (0 === $post_id) {
        $post_id = get_the_ID();
    }
    $terms = get_the_terms($post_id, $taxonomy);
    // Check if post has a tax term assigned.
    if ($terms) {
        if (class_exists('WPSEO_Primary_Term')) {
            // Show the post's 'Primary' term.
            // Check that the feature is available and that a primary term is set.
            $wpseo_primary_term = new WPSEO_Primary_Term($taxonomy, $post_id);
            $wpseo_primary_term = $wpseo_primary_term->get_primary_term();

            // Set the term object.
            $term_obj = get_term($wpseo_primary_term);
            if (is_wp_error($term_obj)) {
                $term_obj = $terms[0];
            }
        } else {
            $term_obj = $terms[0];
        }
        if (!empty($term_obj)) {
            return $term_as_obj ? $term_obj : $term_obj->name;
        }
    }
}

/**
 * Get attachment size
 *
 * @param $attachment_id
 *
 * @return false|string
 */
function getAttachmentSize($attachment_id)
{
    $size = 0;
    $metadata_size = get_attached_file($attachment_id);
    if (file_exists($metadata_size)) {
        $bytes = filesize($metadata_size);
        $size = size_format($bytes);
    }

    return $size;
}

/**
 * Get Woocommerce variation price based on product ID
 *
 * @param $product_id
 * @param $variation_id
 *
 * @return object
 */
function productGetVariationPriceById($product_id, $variation_id)
{
    $currency_symbol = get_woocommerce_currency_symbol();
    $product = new WC_Product_Variable($product_id);
    $variations = $product->get_available_variations();
    $var_data = [];
    foreach ($variations as $variation) {
        if ($variation['variation_id'] == $variation_id) {
            $display_regular_price = $variation['display_regular_price'] . '<span class="currency">' . $currency_symbol . '</span>';
            $display_price = $variation['display_price'] . '<span class="currency">' . $currency_symbol . '</span>';
        }
    }

    //Check if Regular price is equal with Sale price (Display price)
    if ($display_regular_price == $display_price) {
        $display_price = false;
    }

    $priceArray = array(
        'display_regular_price' => $display_regular_price,
        'display_price' => $display_price
    );
    $priceObject = (object)$priceArray;

    return $priceObject;
}

/**
 * Get Woocommerce variation price based on product attribute
 *
 * @param $product_id
 * @param $attribute
 *
 * @return array
 */
function productGetVariationByAttributeCity($product_id, $attribute)
{
    $product = new WC_Product_Variable($product_id);
    $variations = $product->get_available_variations();
    $_variation = null;
    foreach ($variations as $variation) {
        $attributes = $variation['attributes'];

        if (!empty($attributes) && array_key_exists('attribute_pa_city', $attributes) && $attributes['attribute_pa_city'] === $attribute) {
            $_variation = $variation;
            break;
        }
    }

    return $_variation;
}

/**
 * Get product attribut taxonomy
 *
 * @param $product
 *
 * @return null
 */
function getProductAttributTaxonomy($product)
{
    $attributes = $product->get_attributes();
    if (array_key_exists(SLUG_PRODUCT_TAX_ATTRIBUT_COLOR, $attributes)) {
        $pa_color = $attributes[SLUG_PRODUCT_TAX_ATTRIBUT_COLOR];
        if (!empty($pa_color)) {
            return $pa_color->get_terms();
        }
    }

    return null;
}

/**
 * @return mixed|void
 */
function cmsrs_account_fields()
{
    $fields['billing_genre'] = array(
        'label' => __('Civilité', THEME_TD),
        'required' => false,
        'clear' => false,
        'type' => 'text',
        'input_class' => array('form-control'),
        'label_class' => array('label')
    );
    $fields['billing_num_tva'] = array(
        'label' => __('N°TVA', THEME_TD),
        'required' => false,
        'clear' => false,
        'type' => 'text',
        'input_class' => array('form-control'),
        'label_class' => array('label')
    );

    return apply_filters('cmsrs_account_fields', $fields);
}

function find_matching_product_variation_id($product_id, $attributes)
{
    return (new WC_Product_Data_Store_CPT())->find_matching_product_variation(
        new WC_Product($product_id),
        $attributes
    );
}

/**
 * Product order by list
 *
 * @return array
 */
function product_get_orderby_items()
{
    return [
        'date' => __('Défaut', THEME_TD),
        //        'date-desc'  => __('Tri du plus récent au plus ancien', THEME_TD),
        //        'date-asc'   => __('Tri du plus ancien au plus récent', THEME_TD),
        'price' => __('Tri par tarif croissant', THEME_TD),
        'price-desc' => __('Tri par tarif décroissant', THEME_TD),
        'sku' => __('Tri par réference 0-9', THEME_TD),
        'sku-desc' => __('Tri par réference 9-0', THEME_TD),
        'title' => __('Tri par titre (A-Z)', THEME_TD),
        'title-desc' => __('Tri par titre (Z-A)', THEME_TD),
    ];
}

/**
 * Get search URL
 */
function product_search_page_url()
{
    $search_url = home_url('recherche-produit');
    if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE != 'fr') {
        $search_url = home_url('en/search-product');
    }
    return $search_url;
}

/**
 * Product build query args
 *
 * @param $inputs
 *
 * @return array|mixed
 */
function product_build_query_args_by_inputs($inputs = [])
{
    $nb_per_page = 12;
    $input_old_query = array_key_exists('old-query', $inputs) ? $inputs['old-query'] : false;
    $add_to_cart = array_key_exists('add-to-cart', $inputs) ? $inputs['add-to-cart'] : false;
    $input_orderby = array_key_exists('orderby', $inputs) ? $inputs['orderby'] : false;
    $view_all = array_key_exists('page-search-load-more', $inputs);

    if ((!empty($input_old_query) || !empty($add_to_cart)) && !$view_all) {
        $inputs = getSearchQueryToSession();
        if (!empty($input_old_query) && !empty($input_orderby)) {
            $inputs['orderby'] = $input_orderby;
        }
        $input_orderby = array_key_exists('orderby', $inputs) ? $inputs['orderby'] : false;
    }

    $input_paged = array_key_exists('paged', $inputs) ? (int)$inputs['paged'] : 1;
    if (!empty($input_old_query) && !empty($input_orderby)) {
        $input_paged = 1;
    }
    $offset = array_key_exists('offset', $inputs) ? (int)$inputs['offset'] : 0;

    if ($input_paged === -1) {
        $nb_per_page = -1;
    }
    if ($input_paged > 1) {
        $offset = ($nb_per_page * $input_paged) - $nb_per_page;
    }
    if (!empty($offset)) {
        $inputs['offset'] = $offset;
    }
    $inputs['paged'] = $input_paged;
    $city = getEventSalonCitySlugInSession();

    $input_category = array_key_exists('category', $inputs) ? $inputs['category'] : false;
    $input_pa_color = array_key_exists('pa_color', $inputs) ? $inputs['pa_color'] : false;
    $input_product_material = array_key_exists('product_material', $inputs) ? $inputs['product_material'] : false;
    $input_product_tag = array_key_exists('product_tag', $inputs) ? $inputs['product_tag'] : false;
    $input_s = array_key_exists('s', $inputs) ? $inputs['s'] : false;
    $input_search = array_key_exists('search', $inputs) ? $inputs['search'] : false;
    $input_sku = array_key_exists('s_sku', $inputs) ? $inputs['s_sku'] : false;
    $post__in = array_key_exists('post__in', $inputs) ? $inputs['post__in'] : false;
    $fields = array_key_exists('fields', $inputs) ? $inputs['fields'] : false;

    if (empty($input_s) && !empty($input_search)) {
        $inputs['s'] = $input_search;
    }

    addSearchQueryToSession($inputs);

    $product_args = [
        'post_status' => 'publish',
        'posts_per_page' => $nb_per_page,
        'order' => 'DESC',
        'orderby' => 'date',
        'tax_query' => [
            'relation' => 'AND'
        ]
    ];

    if (!empty($offset)) {
        $product_args['offset'] = $offset;
    }
    if (!empty($fields)) {
        $product_args['fields'] = $fields;
    }

    if (!empty($input_s)) {
        $product_args['s'] = wc_clean($input_s);
    }

    if (!empty($input_sku)) {
        $product_args['meta_query'][] = [
            'key' => '_sku',
            'value' => wc_clean($input_sku),
            'compare' => 'LIKE',
        ];
    }

    if (!empty($input_category) && is_array($input_category)) {
        $tax_query_1[] = [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $input_category,
            'operator' => 'IN',
        ];
    }

    if (!empty($input_product_material) && is_array($input_product_material)) {
        $tax_query_1[] = [
            'taxonomy' => 'product_material',
            'field' => 'slug',
            'terms' => $input_product_material,
            'operator' => 'IN',
        ];
    }

    if (!empty($input_pa_color) && is_array($input_pa_color)) {
        $tax_query_1[] = [
            'taxonomy' => SLUG_PRODUCT_TAX_ATTRIBUT_COLOR,
            'field' => 'slug',
            'terms' => $input_pa_color,
            'operator' => 'IN',
        ];
    }

    if (!empty($input_product_tag) && is_array($input_product_tag)) {
        $tax_query_2[] = [
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => $input_product_tag,
            'operator' => 'IN',
        ];
    }

    if (!empty($city)) {
        $tax_query_2[] = [
            'taxonomy' => SLUG_PRODUCT_TAX_ATTRIBUT_CITY,
            'field' => 'slug',
            'terms' => [$city],
            'operator' => 'IN',
        ];
    }

    if (!empty($post__in) && is_array($post__in)) {
        $product_args['post__in'] = $post__in;
    }

    if ((!empty($input_category) || !empty($input_pa_color) || !empty($input_product_material)) && (!empty($tax_query_1) && !empty($tax_query_2))) {
        $product_args['tax_query'][0]['relation'] = 'OR';
        $product_args['tax_query'][1]['relation'] = 'AND';
        $product_args['tax_query'][0][] = $tax_query_1;
        $product_args['tax_query'][1][] = $tax_query_2;
    } elseif (!empty($tax_query_2)) {
        $product_args['tax_query'][] = $tax_query_2;
    }

    if (!empty($input_orderby)) {
        switch ($input_orderby) {
            case 'sku':
                $product_args['orderby'] = 'meta_value_num meta_value';
                $product_args['order'] = 'ASC';
                $product_args['meta_key'] = '_sku';
                break;
            case 'sku-desc':
                $product_args['orderby'] = 'meta_value_num meta_value';
                $product_args['order'] = 'DESC';
                $product_args['meta_key'] = '_sku';
                break;
            case 'price':
                $product_args['orderby'] = 'meta_value_num';
                $product_args['order'] = 'ASC';
                $product_args['meta_key'] = '_price';
                break;
            case 'price-desc':
                $product_args['orderby'] = 'meta_value_num';
                $product_args['order'] = 'DESC';
                $product_args['meta_key'] = '_price';
                break;
            case 'title':
                $product_args['orderby'] = 'name';
                $product_args['order'] = 'ASC';
                break;
            case 'title-desc':
                $product_args['orderby'] = 'name';
                $product_args['order'] = 'DESC';
                break;
            case 'date-asc':
                $product_args['orderby'] = 'date';
                $product_args['order'] = 'ASC';
                break;
            default:
                $product_args['orderby'] = $input_orderby;
        }
    }

    return $product_args;
}

/**
 * @param $product_id
 *
 * @return string
 */
function getProductDimension($product_id)
{
    $product_material = get_field('product_options', $product_id);
    $material = '...';

    if (!empty($product_material) && is_array($product_material)) {
        foreach ($product_material as $item) {
            $array_names = ['dimensions', 'dimension'];
            $title = trim(strtolower($item['product_options_title']));
            if (in_array($title, $array_names)) {
                $material = $item['product_options_desc'];
            }
        }
    }

    return $material;
}

/**
 * @param $product_id
 *
 * @return bool
 */
function cmrs_find_product_in_cart($product_id)
{
    $count = 0; // Initializing

    if (!WC()->cart->is_empty()) {
        // Loop though cart items
        foreach (WC()->cart->get_cart() as $cart_item) {
            // Handling also variable products and their products variations
            $cart_item_ids = array($cart_item['product_id'], $cart_item['variation_id']);

            // Handle a simple product Id (int or string) or an array of product Ids
            if (!is_array($product_id) && in_array($product_id, $cart_item_ids)) {
                $count++; // incrementing items count
            }
        }
        return $count; // returning matched items count
    }
}

/**
 * @param $key_surface
 * @param $surface
 *
 * @return bool
 */
function validateSurfaceDotation($key_surface, $surface)
{
    $surfaces = explode('-', $key_surface);
    $p_max = (int)$surfaces[0];
    $p_min = (int)$surfaces[1];

    if (!empty($p_max) && $surface <= $p_max && $surface >= $p_min) {
        return true;
    }
//    if (!empty($p_max) && $surface >= $p_max) {
//        return true;
//    }
//    if (empty($p_max) && !empty($p_min) && $p_min == $surface) {
//        return true;
//    }

    return false;
}

function getDotationSingleProductUrl($id)
{
    $event_slug = request()->get(SLUG_EVENT_SALON_QUERY);
    return get_permalink($id) . '?' . SLUG_EVENT_SALON_QUERY . '=' . $event_slug;
}

/**
 * @param $products
 * @param $salon_ref
 *
 * @param bool $reverse
 *
 * @return mixed
 */
function getDotationPerSurface($products, $salon_ref, $reverse = false)
{
    $dotation_with_stock = [];
    if (!empty($products) && is_array($products)) {
        foreach ($products as $product) {
            $dotation_ref = get_post_meta($product->ID, '_sku', true);
            $stock_nb = 0;
            $stock_obj = StockSalDot::where('id_salon', $salon_ref)
                ->where('id_dotation', $dotation_ref)
                ->first();
            if (!empty($stock_obj)) {
                $stock_nb = (float)$stock_obj->stock;
                $p_max = (int)get_field('dotation_surface_max', $product->ID);
                $p_min = (int)get_field('dotation_surface_min', $product->ID);
                $d_key = '';
                if (!empty($reverse)) {
                    if ($reverse == 'min') {
                        if (!empty($p_min)) {
                            $d_key .= $p_min;
                        }
                    }
                    if ($reverse == 'max') {
                        if (!empty($p_max)) {
                            $d_key .= $p_max;
                        }
                    }
                } else {
                    if (!empty($p_max) && !empty($p_min)) {
                        $d_key .= $p_max . '-' . $p_min;
                    } else {
                        if (!empty($p_max)) {
                            $d_key .= $p_max . '-0';
                        } elseif (!empty($p_min)) {
                            $d_key .= '0-' . $p_min;
                        }
                    }
                }
                if (!empty($d_key)) {
                    $d_key = trim($d_key);
                    $dotation_with_stock[$d_key][] = $product;
                }
            }
        }
    }

    if (!empty($dotation_with_stock) && is_array($dotation_with_stock)) {
        krsort($dotation_with_stock, SORT_NUMERIC);
    }

    return $dotation_with_stock;
}

/**
 * Save salon stock quantity
 *
 * @param $inputs
 */
function save_salon_stock($inputs)
{
//
//     //Test
//    $inputs = [
//        'ssm_salon_ref'        => 'SALSIMI',
//        'ssm_dotations'        => [
//            'business:dotattion-1',
//            'business:dotattion-2',
//        ],
//        'business:dotattion-1' => 8,
//        'business:dotattion-2' => 28,
//    ];

    if (array_key_exists('ssm_salon_ref', $inputs)) {
        $ssm_salon_ref = $inputs['ssm_salon_ref'];
    } else {
        $ssm_salon_ref = get_field('salon_id', get_the_ID());
    }
    if (array_key_exists('ssm_dotations', $inputs) && !empty($inputs['ssm_dotations']) && is_array($inputs['ssm_dotations']) && !empty($ssm_salon_ref)) {
        $dotation_keys = $inputs['ssm_dotations'];
        foreach ($dotation_keys as $dotation_key) {
            if (array_key_exists($dotation_key, $inputs)) {
                $key_ref = explode(':', $dotation_key);
                $slug_type = $key_ref[0];
                $dotation_ref = $key_ref[1];
                $dotation_id = (int)$key_ref[2];
                $dotation_quantity = $inputs[$dotation_key];
                $title_dotation = get_the_title($dotation_id);

                $stock = StockSalDot::where('id_salon', $ssm_salon_ref)
                    ->where('id_dotation', $dotation_ref)
                    ->first();

                if ($stock == null) {
                    $stock = new StockSalDot();
                    $stock->id_salon = $ssm_salon_ref;
                    $stock->id_dotation = $dotation_ref;
                    $stock->stock = $dotation_quantity;
                    $stock->title_dotation = $title_dotation;
                    $stock->slug_type = $slug_type;
                    $stock->save();
                } else {
                    StockSalDot::updateStock($ssm_salon_ref, $dotation_ref, $dotation_quantity, $title_dotation);
                }
            }
        }
    }
}

/**
 * Send email
 *
 * @param mixed|string $to
 * @param $subject
 * @param $data
 *
 * @return bool
 */
function sendEmailType($to, $subject, $data)
{
    $headers[] = 'From: ' . SITE_MAIN_SYS_NAME . ' <noreply@lign-e.com>';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';

    $data['subject'] = $subject;
    $body = View::make('components.email.layout-default', $data)->render();

    if (wp_mail($to, $subject, $body, $headers)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Send email custom Template
 *
 * @param mixed|string $to
 * @param $subject
 * @param $data
 *
 * @return bool
 */
function sendEmailCustomType($to, $subject, $data)
{
    $headers[] = 'From: ' . SITE_MAIN_SYS_NAME . ' <noreply@camerus.fr>';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';

    $data['subject'] = $subject;
    $body = View::make('shop.emails.admin-new-order-custom', $data)->render();

    if (wp_mail($to, $subject, $body, $headers)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Send stock notification email
 *
 * @param $product
 * @param $salon
 * @param $stock
 *
 * @return bool
 */
function sendNotificationDotationStock($product, $salon, $stock)
{
    if ($product->is_type('dotation')) {
        $to = APP_EMAIL_NOTIFICATION_STOCK;
        $subject = __('Notification de stock', THEME_TD);
        $data = [];
        $data['email_type'] = 'dotation_stock';
        $data['product'] = $product;
        $data['salon'] = $salon;
        $data['stock'] = $stock;
        $slug_type = '';
        $dotation_type = get_field('dotation_type', $product->get_id());
        if (!empty($dotation_type) && is_array($dotation_type)) {
            $dotation_type = reset($dotation_type);
            $slug_type = $dotation_type->slug;
        }
        $data['dotation_type'] = $slug_type;
        $data['dotation_ref'] = $product->get_sku();
        $data['salon_ref'] = get_field('salon_id', $salon->ID);

        return sendEmailType($to, $subject, $data);
    }

    return false;
}

/**
 * Check Salon limit date Reed
 *
 * @param $salon_id
 *
 * @return bool
 */
function isOverSalonLimitDate($salon_id)
{
    $salon_start_date = get_field('salon_start_date', $salon_id);
    $salon_limit = (int)get_field('salon_time_limit', $salon_id);

    if (!empty($salon_limit) && !empty($salon_start_date)) {
        $now = Carbon::now();
        $salon_start_date = Carbon::createFromFormat('Y-m-d', $salon_start_date);
        $limite_date = $salon_start_date->subDays($salon_limit);
        $limite_date->hour = 0;
        $limite_date->minute = 0;
        $limite_date->second = 1;

        if ($now->greaterThan($limite_date)) {
            return true;
        }
    }

    return false;
}

/**
 * Check Salon start date
 *
 * @param $salon_id
 *
 * @return bool
 */
function isOverSalonStartDate($salon_id)
{
    $salon_start_date = get_field('salon_start_date', $salon_id);

    if (!empty($salon_start_date)) {
        $now = Carbon::now();
        $salon_start_date = Carbon::createFromFormat('Y-m-d', $salon_start_date);
        $salon_start_date->hour = 0;
        $salon_start_date->minute = 0;
        $salon_start_date->second = 1;

        if ($now->greaterThan($salon_start_date)) {
            return true;
        }
    }

    return false;
}

/**
 * @param $product_id
 *
 * @return array
 */
function getRelatedCustomProduct($product_id)
{
    $all_related = [];
    $related_products = [];
    $custom_related_products = get_field('product_other_suggest', $product_id);
    if (!empty($custom_related_products)) {
        foreach ($custom_related_products as $custom_related_product) {
            $all_related[] = wc_get_product($custom_related_product->ID);
        }
        $related_products = $all_related;
    } else {
        $related_ids = wc_get_related_products($product_id, 4);
        if (!empty($related_ids)) {
            foreach ($related_ids as $r_id) {
                $all_related[] = wc_get_product($r_id);
            }
            $related_products = $all_related;
        }
    }

    return $related_products;
}

/**
 * @param $product_id
 *
 * @return array
 */
function getProductTagData($product_id)
{
    $tags = [];
    $_tags = wp_get_post_terms($product_id, 'product_tag');

    if (!empty($_tags) && is_array($_tags)) {
        foreach ($_tags as $key => $tag) {
            $tag_icon = get_field('tag_icon', 'product_tag_' . $tag->term_id);
            if (!empty($tag_icon)) {
                $tag->tag_icon = $tag_icon;
                $tags[] = $tag;
            }
        }
    }

    return $tags;
}

/**
 * @param $product_id
 *
 * @return array
 */
function getProductColors($product_id)
{
    $colors = [];
    $terms = wc_get_product_terms(
        $product_id,
        'pa_color',
        array(
            'fields' => 'all',
        )
    );
    $current_color = [];
    if (!empty($terms)) {
        $current_color = [$product_id => $terms[0]];
        $color_products = get_field('product_colors', $product_id);
        if (!empty($color_products) && is_array($color_products)) {
            foreach ($color_products as $color_product) {
                $pr = wc_get_product($color_product->ID);
                $attributes = $pr->get_attributes();
                if (array_key_exists('pa_color', $attributes) && !empty($attributes['pa_color'])) {
                    $c_option = $attributes['pa_color']->get_options();
                    if (!empty($c_option)) {
                        $colors[$color_product->ID] = get_term($c_option[0], 'pa_color');
                    }
                }
            }
        }
    }

    return $current_color + $colors;
}

function generateReedPdf($order_id)
{
    $args = [];

    $order = wc_get_order($order_id);
    $order_items = $order->get_items();
    foreach ($order_items as $item) {
        $product = $item->get_product();
        if ($product->is_type('dotation')) {
            $args['dotation'] = $product;
        }
    }
    $customer_id = $order->get_customer_id();
    $customer = get_user_by('id', $customer_id);
    $salon_slug = getEventSalonSlugInSession();
    if (!empty($salon_slug)) {
        $salon_id = getEventSalonId($salon_slug);
        if (!empty($salon_id)) {
            $salon = getEventTheSalon($salon_id);
            $args['salon'] = $salon;
        }
    }

    $args['order'] = $order;
    $args['customer'] = $customer;
    $reed_info = getReedDataInfo();
    if (!empty($reed_info)) {
        $args['TypeStand'] = $reed_info->TypeStand;
        $args['NumStand'] = $reed_info->NumStand;
        $args['SurfaceStand'] = $reed_info->SurfaceStand;
        $args['RaisonSociale'] = $reed_info->RaisonSociale;
    }

    return generateHtmlToPdf($args, 'Reed-PDF', 'reedpdf', 'pdf.reed-pdf');
}

function generateHtmlToPdf($args, $pdf_name = '', $path_name = '', $view_tmp = '')
{
    $file_name = $pdf_name . '-' . date('Y-m', time()) . '.pdf';
    $pdf_file_path = wp_get_upload_dir()['basedir'] . '/' . $path_name . '/' . $file_name;
    $pdf_file_uri = wp_get_upload_dir()['baseurl'] . '/' . $path_name . '/' . $file_name;

    try {
        ob_start();
        print_r(View::make($view_tmp, $args)->render());
        $content = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8');
        $html2pdf->setTestIsImage(false);
        $html2pdf->setFallbackImage(get_template_directory() . '/dist/images/Logo_Cameru-450px.png');
        $html2pdf->addFont('Overpass_light', '', get_template_directory() . '/dist/fonts/Overpass_light.php');
        $html2pdf->addFont('Overpass', '', get_template_directory() . '/dist/fonts/Overpass_regular.php');
        $html2pdf->addFont('Overpass_thin', '', get_template_directory() . '/dist/fonts/Overpass_thin.php');
        $html2pdf->setTestTdInOnePage(false);
        $html2pdf->writeHTML($content);
        $html2pdf->output($pdf_file_path, 'F');


        return $pdf_file_uri;

    } catch (Html2PdfException $e) {
        $html2pdf->clean();

        $formatter = new ExceptionFormatter($e);
        echo $formatter->getHtmlMessage();
    }
}

/**
 * @return bool
 */
function checkRecaptchaV3()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

        // Build POST request:
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = '6LfQa9wUAAAAAK4jnL5G97At9ySLeiBo4vIiMX0J';
        $recaptcha_response = $_POST['recaptcha_response'];

        // Make and decode POST request:
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);

        // Take action based on the score returned:
        if (!empty($recaptcha)) {
            if (property_exists($recaptcha, 'score') && $recaptcha->score >= 0.5) {
                return $recaptcha;
            }
        }

        return false;
    }
}

/**
 * @return string
 */
function getShopManagerEmails()
{
    $users = [];
    $users_id = getUsersByRole(['manager_shop']);
    if (!empty($users_id)) {
        foreach ($users_id as $user_id) {
            $users[] = get_user_by('id', $user_id);
        }
    }
    $pro_emails = '';
    if (!empty($users) && is_array($users)) {
        foreach ($users as $user) {
            $pro_emails .= $user->user_email . ',';
        }

        return rtrim($pro_emails, ',');
    }
}

/**
 * @param $roles
 *
 * @return array
 */
function getUsersByRole($roles)
{
    global $wpdb;

    if (!is_array($roles)) {
        $roles = explode(",", $roles);
        array_walk($roles, 'trim');
    }

    $sql = '
SELECT ID, display_name
FROM ' . $wpdb->users . ' INNER JOIN ' . $wpdb->usermeta . '
ON ' . $wpdb->users . '.ID = ' . $wpdb->usermeta . '.user_id
WHERE ' . $wpdb->usermeta . '.meta_key = \'' . $wpdb->prefix . 'capabilities\'
AND (
';

    $i = 1;
    foreach ($roles as $role) {
        $sql .= ' ' . $wpdb->usermeta . '.meta_value LIKE \'%"' . $role . '"%\' ';
        if ($i < count($roles)) {
            $sql .= ' OR ';
        }
        $i++;
    }
    $sql .= ' ) ';
    $sql .= ' ORDER BY display_name ';
    $userIDs = $wpdb->get_col($sql);
    return $userIDs;
}

function getReduceCreditAmountFee()
{
    global $woocommerce;

    $cart_items = $woocommerce->cart->cart_contents;
    $cart_fee = $woocommerce->cart->get_fees();
    $fee_amount = 0;
    if (array_key_exists('assurance', $cart_fee) && !empty($cart_fee['assurance'])) {
        $fee_amount = (float)$cart_fee['assurance']->amount;
    }

    if (!$woocommerce->cart->is_empty() && !empty($cart_items)) {
        foreach ($cart_items as $item_id => $value) {
            if (array_key_exists('reduce_credit_amount', $value)) {
                $cart_total = (float)$woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total + $fee_amount;
                $surcharge = '-' . $value['reduce_credit_amount'];
                if ($cart_total > $surcharge) {
                    return $surcharge;
                }
            }
        }
    }

    return 0;
}

/**
 * Zip Stream for download
 *
 * @param $file_name
 * @param $files
 *
 * @return ZipStream\ZipStream
 * @throws \ZipStream\Exception\OverflowException
 */
function custom_create_zip_file_download($file_name, $files)
{
    $response = new StreamedResponse(function () use ($file_name, $files) {
        // Define suitable options for ZipStream Archive.
        $options = new Archive();
        $options->setContentType('application/octet-stream');
        // this is needed to prevent issues with truncated zip files
        $options->setZeroHeader(true);
        $options->setEnableZip64(false);
        $options->setComment(SITE_MAIN_SYS_NAME . ' 3d-zip file.');

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header('Content-Type: application/zip, application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file_name);
        header("Content-Type: application/force-download");
        header('Content-Description: ' . SITE_MAIN_SYS_NAME . ' ZIP File Download');
        header('Content-Transfer-Encoding: binary');

        //initialise zipstream with output zip filename and options.
        $zip = new ZipStream\ZipStream($file_name, $options);

        //loop keys - useful for multiple files
        foreach ($files as $file) {
            $filetype = wp_check_filetype($file['path']);
            $ext = $filetype['ext'];
            $file_name = $file['name'] . '.' . $ext;
            if ($streamRead = fopen($file['path'], 'r')) {
                $zip->addFileFromStream($file_name, $streamRead);
            } else {
                die('Could not open stream for reading');
            }
        }

        $zip->finish();

    });

    $response->send();
}

/**
 * Set Date Time Local Format
 *
 * @param string $lang
 */
function setDateTimeLocalFormat($lang = '')
{
    if (empty($lang)) {
        $lang = icl_get_current_language();
    }
    if ($lang == 'fr' || $lang == 'fr_FR' || $lang == 'fr-FR') {
        setlocale(LC_TIME, 'fr_FR.UTF-8', 'French_France', 'French');
    } else {
        setlocale(LC_TIME, 'en_utf8.UTF-8');
    }
}

/**
 * Get Salon hidden product
 *
 * @param int $salon_id
 *
 * @return array|mixed
 */
function getSalonHiddenProduct($salon_id = 0)
{
    $salon_hide_product = [];
    $salon_session = getEventSalonSlugInSession();
    if (!empty($salon_session)) {
        if (empty($salon_id)) {
            $salon_id = getPostIdBySlug($salon_session);
        }
        if (!empty($salon_id)) {
            $salon_hide_product = get_field('salon_hide_product', $salon_id);
        }
    }

    return $salon_hide_product;
}

/**
 * Get Salon hidden product
 *
 * @param int $salon_id
 *
 * @return array|mixed
 */
function getSalonHiddenCat($salon_id = 0)
{
    $salon_hide_cat = [];
    $salon_session = getEventSalonSlugInSession();
    if (!empty($salon_session)) {
        if (empty($salon_id)) {
            $salon_id = getPostIdBySlug($salon_session);
        }
        if (!empty($salon_id)) {
            $salon_hide_cat = get_field('salon_hide_cat', $salon_id);
        }
    }

    return $salon_hide_cat;
}

/**
 * Check if is ordered reed token
 *
 * @param $token
 *
 * @return bool
 */
function isUsedTokenReed($token)
{
    global $wpdb;

    $token = wc_clean($token);
    $query = "
            SELECT *
            FROM {$wpdb->prefix}posts
            INNER JOIN {$wpdb->prefix}postmeta m1
              ON ( {$wpdb->prefix}posts.ID = m1.post_id )
            INNER JOIN {$wpdb->prefix}postmeta m2
              ON ( {$wpdb->prefix}posts.ID = m2.post_id )
            WHERE
            {$wpdb->prefix}posts.post_type = 'shop_order'
            AND ( m1.meta_key = 'reed_token' AND m1.meta_value = '%s' )
            GROUP BY {$wpdb->prefix}posts.ID
            ORDER BY {$wpdb->prefix}posts.post_date
            DESC;
    ";
    $sql = $wpdb->prepare($query, $token);
    $results = $wpdb->get_results($sql);

    if (!empty($results)) {
        return true;
    }

    return false;
}

/**
 * Is commanded from reed canal
 */
function isOrderFromReed()
{
    $reed_info = getReedDataInfo();

    if (!empty($reed_info) && !isUsedTokenReed($reed_info->token)) {
        return true;
    }

    return false;
}

/**
 * Get Insurance check
 *
 * @param $product_id
 *
 * @return bool
 */
function isAddInInsuranceCalc($product_id)
{
    $terms = get_the_terms($product_id, 'product_cat');
    if (!empty($terms)) {
        foreach ($terms as $term) {
            $category_insurance = get_field('option_not_in_insurance', 'product_cat_' . $term->term_id);
            if (!empty($category_insurance)) {
                break;
            }
        }
    }
    if (!empty($category_insurance)) {
        return false;
    }

    $product_insurance = get_field('option_not_in_insurance', $product_id);
    if (!empty($product_insurance)) {
        return false;
    }

    return true;
}

function getCustomProducts($args)
{
    global $wpdb;

    $current_lang = $args['clang'] ?? ICL_LANGUAGE_CODE;
    do_action('wpml_switch_language', $current_lang);
    $prefix = $wpdb->prefix;
    $paged = array_key_exists('paged', $args) ? (int)$args['paged'] : 1;
    $posts_per_page = array_key_exists('posts_per_page', $args) ? (int)$args['posts_per_page'] : 9;
    $offset = ($paged * $posts_per_page) - $posts_per_page;

    $order = 'ASC';
    if (array_key_exists('order', $args) && !empty($args['order'])) {
        $order = esc_sql($args['order']);
    }
    $orderby = '';
    $sku_order = "";
    if (array_key_exists('orderby', $args)) {
        if ($args['orderby'] === 'title') {
            $orderby = 'ORDER BY title ' . $order;
        }
        if ($args['orderby'] === 'date') {
            $orderby = 'ORDER BY TIMESTAMP(p.post_date) ' . $order;
        }
        if ($args['orderby'] === 'price') {
            $orderby = 'ORDER BY CAST(price AS int) ' . $order;
        }
        if ($args['orderby'] === 'sku') {
            $orderby = 'ORDER BY CAST(sku AS int) ' . $order;
            $sku_order = "AND ( pm1.meta_key = '_sku' AND pm1.meta_value not in('') )";
        }
    }
    $category = "";
    $category_join = "";
    if (array_key_exists('category', $args) && !empty($args['category'])) {
        $category_join = "INNER JOIN {$prefix}term_relationships AS tr1 ON p.ID = tr1.object_id
                   INNER JOIN {$prefix}term_taxonomy AS tt1 ON tr1.term_taxonomy_id = tt1.term_taxonomy_id
                   INNER JOIN {$prefix}terms AS t1 ON tt1.term_id = t1.term_id";
        $category = sprintf("AND tt1.taxonomy = 'product_cat' AND t1.slug IN (%s)", dbArrayToQuote($args['category']));
    }
    if (Route::currentRouteName() == 'search-product') {
        $args['category__not_in'] = ['non-classe', 'ensembles', 'uncategorized-en', 'ensembles'];
    }
    $category__not_in = "";
    $category__not_in_join = "";
    if (array_key_exists('category__not_in', $args) && !empty($args['category__not_in'])) {
        $category__not_in_join = "INNER JOIN {$prefix}term_relationships AS tr5 ON p.ID = tr5.object_id
                   INNER JOIN {$prefix}term_taxonomy AS tt5 ON tr5.term_taxonomy_id = tt5.term_taxonomy_id
                   INNER JOIN {$prefix}terms AS t5 ON tt5.term_id = t5.term_id";
        $category__not_in = sprintf("AND tt5.taxonomy = 'product_cat' AND t5.slug NOT IN (%s)", dbArrayToQuote($args['category__not_in']));
    }
    $tag = "";
    $tag_join = "";
    if (array_key_exists('tag', $args) && !empty($args['tag'])) {
        $tag_join = "INNER JOIN {$prefix}term_relationships AS tr2 ON p.ID = tr2.object_id
                   INNER JOIN {$prefix}term_taxonomy AS tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id
                   INNER JOIN {$prefix}terms AS t2 ON tt2.term_id = t2.term_id";
        $tag = sprintf("AND tt2.taxonomy = 'product_tag' AND t2.slug IN (%s)", dbArrayToQuote($args['tag']));
    }
    $color = "";
    $color_join = "";
    if (array_key_exists('color', $args) && !empty($args['color'])) {
        $color_join = "INNER JOIN {$prefix}term_relationships AS tr3 ON p.ID = tr3.object_id
                   INNER JOIN {$prefix}term_taxonomy AS tt3 ON tr3.term_taxonomy_id = tt3.term_taxonomy_id
                   INNER JOIN {$prefix}terms AS t3 ON tt3.term_id = t3.term_id";
        $color = sprintf("AND tt3.taxonomy = 'pa_color' AND t3.slug IN (%s)", dbArrayToQuote($args['color']));
    }
    $material = "";
    $material_join = "";
    if (array_key_exists('material', $args) && !empty($args['material'])) {
        $material_join = "INNER JOIN {$prefix}term_relationships AS tr4 ON p.ID = tr4.object_id
                   INNER JOIN {$prefix}term_taxonomy AS tt4 ON tr4.term_taxonomy_id = tt4.term_taxonomy_id
                   INNER JOIN {$prefix}terms AS t4 ON tt4.term_id = t4.term_id";
        $material = sprintf("AND tt4.taxonomy = 'product_material' AND t4.slug IN (%s)", dbArrayToQuote($args['material']));
    }
    $in_product = "";
    if (array_key_exists('product_salon', $args) && !empty($args['product_salon'])) {
        $in_product = sprintf("AND p.ID IN (%s)", dbArrayToQuoteInt($args['product_salon']));
    }
    $hide_product = "";
    if (array_key_exists('product_hide_salon', $args) && !empty($args['product_hide_salon'])) {
        $hide_product = sprintf("AND p.ID NOT IN (%s)", dbArrayToQuoteInt($args['product_hide_salon']));
    }
    $city = 'paris';
    if (array_key_exists('city', $args) && !empty($args['city'])) {
        $city = esc_sql($args['city']);
    }
    $lang = "AND t.language_code = '" . $current_lang . "'";

    $search_where = "";
    if (array_key_exists('s', $args) && !empty($args['s'])) {
        $search_where = "AND (p.post_title LIKE '%" . sprintf('%s', wc_clean($args['s'])) . "%' OR pm1.meta_value LIKE '%" . sprintf('%s', wc_clean($args['s']))
            . "%' OR p.post_excerpt LIKE '%" . sprintf('%s', wc_clean($args['s'])) . "%')";
    }

    $sql = "
   SELECT p.ID, p.post_title as title, p.post_date, pv.price as price,
      (CASE WHEN pm1.meta_key = '_sku' then pm1.meta_value ELSE NULL END) as sku
          FROM {$prefix}posts p
          JOIN {$prefix}icl_translations t ON p.ID = t.element_id AND t.element_type = CONCAT('post_', p.post_type)
          LEFT JOIN {$prefix}postmeta AS pm1 ON pm1.post_id = p.ID
                   $category_join

                   $tag_join

                   $color_join
                   
                   $material_join
                   
                   $category__not_in_join

                   INNER JOIN (SELECT p.ID, p.post_parent,
                        (CASE WHEN m1.meta_key = 'attribute_pa_city' then m1.meta_value ELSE NULL END) as city,
                        (CASE WHEN m3.meta_key = '_price' then m3.meta_value ELSE NULL END) as price
                        FROM {$prefix}posts p
                        INNER JOIN {$prefix}postmeta AS m1 ON m1.post_id = p.ID
                        INNER JOIN {$prefix}postmeta AS m3 ON m3.post_id = p.ID
                        WHERE p.post_status = 'publish' AND p.post_type = 'product_variation'
                        AND ( m1.meta_key = 'attribute_pa_city' AND m1.meta_value in('$city') )
                        AND ( m3.meta_key = '_price' AND m3.meta_value IS NOT NULL )
                        GROUP BY p.ID) AS pv ON p.ID = pv.post_parent

          WHERE p.post_type = 'product' AND p.post_status = 'publish' 
            $lang
            $sku_order
            $category
            $tag
            $color
            $material
            $category__not_in
            $in_product
            $hide_product
            $search_where
            
          GROUP BY p.ID
          $orderby
    ";

    $query = $wpdb->get_results($sql);
    $total_posts = $wpdb->num_rows;
    $total_page = ceil($total_posts / $posts_per_page);
    $result = array_slice($query, $offset, $posts_per_page);
    $products = [];
    if (!empty($result) && is_array($result)) {
        foreach ($result as $item) {
            $products[] = wc_get_product($item->ID);
        }
    }
    $wpdb->flush();

    $data = [
        'products' => $products,
        'total' => $total_posts,
        'max_num_pages' => $total_page,
    ];

    return $data;
}

function dbArrayToQuote($array)
{
    if (empty($array)) {
        return '';
    }

    $result = '';
    foreach ($array as $item) {
        $result .= "'" . $item . "',";
    }

    return rtrim($result, ',');
}

function dbArrayToQuoteInt($array)
{
    if (empty($array)) {
        return '';
    }

    $result = '';
    foreach ($array as $item) {
        $result .= "" . (int)$item . ",";
    }

    return rtrim($result, ',');
}

function getPostTranslatedID($id, $lang_code, $post_type = 'post')
{
    return apply_filters('wpml_object_id', $id, $post_type, false, $lang_code);
}

function getPageIDByTemplateName($template, $is_single = false)
{
    $args = [
        'post_type' => 'page',
        'fields' => 'ids',
        'nopaging' => true,
        'meta_key' => '_wp_page_template',
        'meta_value' => $template,
        'posts_per_page' => 1
    ];
    $pages = get_posts($args);

    if (!empty($pages) && !empty($is_single)) {
        return reset($pages);
    }
    return $pages;
}

function getAttachmentByName($filename)
{
    $attachment_id = 0;

    $query_args = array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'fields' => 'ids',
        'name' => $filename
    );

    $query = new WP_Query($query_args);

    if ($query->have_posts()) {
        $attachment_ids = $query->posts;
        rsort($attachment_ids);

        return reset($attachment_ids);
    }

    return $attachment_id;
}

function getHomeVideoEmbed($iframe)
{
    preg_match('/src="(.+?)"/', $iframe, $matches);
    $old_src = $matches[1];
    $src = explode('?', $old_src);
    $src = reset($src);
    $attributes = 'id="video" class="videoIframe" ';

    if (strpos($src, 'youtu') !== false) {
        $params = array(
            'enablejsapi' => 1,
            'controls' => 0,
            'rel' => 0,
        );

        $new_src = add_query_arg($params, $src);
        $iframe = str_replace($old_src, $new_src, $iframe);
        $iframe = str_replace('allow="', 'allow="modestbranding; ', $iframe);

        $attributes = 'id="video" class="videoIframe" ';
    }

    if (strpos($src, 'vimeo') !== false) {
        $attributes = 'id="video" class="videoIframe"  webkitallowfullscreen   mozallowfullscreen allowfullscreen ';
    }

    $iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

    return $iframe;
}

function getProductColorVariation($product_id)
{
    $colors = [];
    $all_color = [];
    $color_products = get_field('product_colors', $product_id);

    if (!empty($color_products) && is_array($color_products)) {
        foreach ($color_products as $color_product) {
            if (property_exists($color_product, 'ID')) {
                $c_id = $color_product->ID;
            } elseif (!empty((int)$color_product)) {
                $c_id = $color_product;
            }

            if (!empty($c_id)) {
                $pr = wc_get_product($c_id);
                $attributes = $pr->get_attributes();
                if (array_key_exists('pa_color', $attributes) && !empty($attributes['pa_color'])) {
                    $c_option = $attributes['pa_color']->get_options();
                    if (!empty($c_option)) {
                        $colors[$c_id] = $c_option;
                    }
                }
            }
        }

        if (!empty($colors)) {
            foreach ($colors as $c_id => $color) {
                if (!empty($color)) {
                    foreach ($color as $color_id) {
                        $all_color[$c_id][] = $color_id;
                    }
                }
            }
        }
    }

    return $all_color;
}

function product_get_json_data($product_id)
{
    $all_variations = [];
    $handle = new WC_Product_Variable($product_id);
    $variations = $handle->get_children();
    if (!empty($variations)) {

        foreach ($variations as $variation) {
            $data_variation = [];
            $single_variation = new WC_Product_Variation($variation);

            if (!empty($single_variation)) {
                $data_variation = array_merge($data_variation, $single_variation->get_variation_attributes());
                $data_variation['price'] = $single_variation->get_price();
                $data_variation['product_id'] = $product_id;
                $data_variation['variation_id'] = $variation;
                $all_variations[] = $data_variation;
            }
        }
    }

    return json_encode($all_variations);
}

function showroomGetProductCatagoryUrl($url)
{
    $showroom_url = $url;
    if (eventHaveFilterProductOrCat()) {
        $showroom_url = get_permalink(wc_get_page_id('shop'));
        $urls = parse_url($url);
        if (isset($urls['path']) && !empty($urls['path'])) {
            $salon_session = getEventSalonSlugInSession();
            if (!empty($salon_session)) {
                if (empty($salon_id)) {
                    $salon_id = getPostIdBySlug($salon_session);
                }
                if (!empty($salon_id)) {
                    $showroom_url = rtrim(showroomGetUrl($salon_session), '/') . str_replace('/en/', '/', $urls['path']);
                }
            }
        }
    }

    return $showroom_url;
}

function showroomGetUrl($slug = '', $lang = '')
{
    global $sitepress;

    $home_url = home_url();
    if (!empty($lang)) {
        $home_url = $sitepress->convert_url($home_url, $lang);
    }
    return rtrim($home_url, '/') . '/showroom/' . $slug;
}

function eventHaveFilterProductOrCat()
{
    $event_slug = getEventSalonSlugInSession();
    if (!empty($event_slug)) {
        $event_id = getEventSalonId($event_slug);
        $filter_cat = get_field('salon_hide_cat', $event_id);
        $filter_product = get_field('salon_hide_product', $event_id);

        if (!empty($filter_cat) || !empty($filter_product)) {
            return true;
        }
    }

    return false;
}

function cmrs_check_if_slug_exists($post_name, $post_type = 'post')
{
    $args = array(
        'name' => wc_clean($post_name),
        'post_type' => $post_type,
        'post_status' => ['publish', 'private'],
        'numberposts' => 1
    );
    $my_posts = get_posts($args);

    if (!empty($my_posts)) {
        $salon = $my_posts[0];

        if (!empty($salon) && $salon->post_name === $post_name) {
            return true;
        }
    }

    return false;
}

function cmrs_get_all_translated_ids($elment_id, $element_type)
{
    $ids = [];
    $langs = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
    if (!empty($langs)) {
        foreach ($langs as $lang => $value) {
            $ids[$lang] = apply_filters('wpml_object_id', $elment_id, $element_type, false, $lang);
        }
    }

    return $ids;
}

function cmsr_delete_post_wpo_cache($post_id, $post_type, $recurive = true)
{
    if (!empty($post_id)) {
        $ids = cmrs_get_all_translated_ids($post_id, $post_type);
        if (!empty($ids) && class_exists('WPO_Page_Cache')) {
            foreach ($ids as $id) {
                if (!empty($id)) {
                    $url = get_permalink($id);
                    WPO_Page_Cache::delete_cache_by_url($url, $recurive);
                }
            }
        }
    }
}

function cmsr_delete_term_wpo_cache($term_id, $taxonomy, $recurive = true)
{
    if (!empty($term_id)) {
        $ids = cmrs_get_all_translated_ids($term_id, $taxonomy);
        if (!empty($ids) && class_exists('WPO_Page_Cache')) {
            foreach ($ids as $id) {
                if (!empty($id)) {
                    $url = get_term_link($id);
                    WPO_Page_Cache::delete_cache_by_url($url, $recurive);
                }
            }
        }
    }
}

function getShortLangCode($lang)
{
    if (!empty($lang)) {
        $lang = explode('-', $lang);
        return reset($lang);
    } else {
        return 'fr';
    }
}