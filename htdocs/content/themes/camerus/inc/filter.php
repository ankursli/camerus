<?php
/**
 * Allow JSON file upload
 */

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\Filter;

Filter::add('upload_mimes', function ($mime_types) {
    $mime_types['json'] = 'application/json';
    $mime_types['svg'] = 'image/svg+xml';
    $mime_types['zip'] = 'application/zip';
    $mime_types['gz'] = 'application/x-gzip';

    return $mime_types;
});

/**
 * Disable Plugin Update notification
 */
Filter::add('site_transient_update_plugins', function ($value) {
    if (isset($value->response['advanced-custom-fields-pro/acf.php'])) {
        unset($value->response['advanced-custom-fields-pro/acf.php']);
    }
    if (isset($value->response['wp-optimize-premium/wp-optimize.php'])) {
        unset($value->response['wp-optimize-premium/wp-optimize.php']);
    }
    if (isset($value->response['eu-vat-for-woocommerce/eu-vat-for-woocommerce.php'])) {
        unset($value->response['eu-vat-for-woocommerce/eu-vat-for-woocommerce.php']);
    }
    if (isset($value->response['login-with-ajax/login-with-ajax.php'])) {
        unset($value->response['login-with-ajax/login-with-ajax.php']);
    }

    return $value;
});

/**
 * Change post link sync to rewrite
 */
Filter::add('post_link', function ($post_link, $id = 0) {

    $post = get_post($id);

    if (is_object($post) && $post->post_type === 'post') {
        $categories = get_the_category($post->ID);
        if (!empty($categories) && is_array($categories)) {
            $cat_slug = $categories[0]->slug;
            return home_url('/blog/' . $cat_slug . '/' . $post->post_name . '/');
        }

        return home_url('/blog/' . $post->post_name . '/');
    }

    return $post_link;

}, 1, 3);

/* Disable canonical url et auto redirection */
Filter::add('do_redirect_guess_404_permalink', '__return_false');

/**
 * Rewrite rule for post url
 */
Action::add('generate_rewrite_rules', function ($wp_rewrite) {

    $new_rules = array(
        'blog/%category%/(.+?)/?$' => 'index.php?post_type=post&name=' . $wp_rewrite->preg_index(1),
    );

    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
});

/**
 * Custom active menu class
 */
Filter::add('nav_menu_css_class', function ($classes, $item) {
    if (in_array('current-menu-item', $classes, true) || in_array('current-menu-parent', $classes, true)) {
        $classes[] = 'uk-active ';
    }
    return $classes;
}, 10, 2);

/**
 * Filter media category taxonomy
 */
Filter::add('wpmediacategory_taxonomy', function ($taxonomy) {
    $taxonomy = SLUG_TAX_MEDIA_CATEGORY;
    return $taxonomy;
});

/**
 * Replace SKU in GB language
 */
Filter::add('woocommerce_product_get_sku', function ($sku) {

    if (is_admin()) {
        return $sku;
    }

    if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE !== 'fr') {
        if (
            is_ajax() || is_woocommerce() || is_cart() || is_checkout() || is_product_category() || is_wc_endpoint_url() || is_account_page()
            || Route::currentRouteName() == 'cart-template'
            || Route::currentRouteName() == 'checkout-woocommerce-template'
            || Route::currentRouteName() == 'dotation-list'
            || Route::currentRouteName() == 'dotation-list-en'
            || Route::currentRouteName() == 'search-product'
        ) {
            $haystack = '-GB';
            $sku = str_replace($haystack, '', $sku);
        }
    }

    return $sku;
}, 1, 10);

/**
 * Replace SKU in GB language
 */
Filter::add('woocommerce_product_variation_get_sku', function ($sku) {

    if (is_admin()) {
        return $sku;
    }

    $haystack = '-GB';
    $sku = str_replace($haystack, '', $sku);

    return $sku;
}, 1, 10);

/**
 * Replace SKU in GB language
 */
Filter::add('woocommerce_dotation_get_sku', function ($sku) {

    if (is_admin()) {
        return $sku;
    }

    $haystack = '-GB';
    $sku = str_replace($haystack, '', $sku);

    return $sku;
}, 1, 10);

/**
 * Remove cart session after 23h
 */
Filter::add('wc_session_expiring', function ($seconds) {
    return 60 * 60 * 10; // 23 hours
});
Filter::add('wc_session_expiration', function ($seconds) {
    return 60 * 60 * 10; // 23 hours
});

/* Autoptimize css footer */
Filter::add('autoptimize_filter_css_replacetag', function () {
    return array('</body>', 'before');
});


Filter::add('wpo_can_cache_page', function ($status) {
    if (is_product()) {
        $id = get_queried_object_id();
        if ($id) {
            $product = wc_get_product($id);
            $type = $product->get_type();
            if ($type == 'dotation') {
                return false;
            }
        }
    }

    if (is_singular(array('salon'))) {
        $id = get_queried_object_id();
        $salon = get_post($id);
        $salon_status = $salon->post_status;

        if ($salon_status == 'private') {
            return false;
        }
    }

    if (!empty(request()->get('elementor-preview')) ||
        is_cart() || is_checkout() || is_account_page() || is_wc_endpoint_url() ||
        Route::currentRouteName() == 'wp-cron-exec' ||
        Route::currentRouteName() == 'load-3d-files' ||
        Route::currentRouteName() == 'load-3d-files-en' ||
        Route::currentRouteName() == 'agenda-pdf' ||
        Route::currentRouteName() == 'agenda-pdf-en' ||
        Route::currentRouteName() == 'dotation-list' ||
        Route::currentRouteName() == 'dotation-list-en' ||
        Route::currentRouteName() == 'search-product' ||
        Route::currentRouteName() == 'reed-json' ||
        Route::currentRouteName() == 'reed-json-export') {
        return false;
    }

    return $status;
});

/**
 * Fix deprecated wp function
 */
Action::add( 'wp_head', function (){
    Action::remove('wp_head', 'wc_page_noindex', 10);

    wp_robots_no_robots([]);
}, 9 );


Filter::add('saswp_modify_product_schema_output', function ($input1) {
    global $post;

    $lowPrice = '';
    $highPrice = '';
    $product = new WC_Product_Variable($post->ID);
    $variations = $product->get_available_variations();

    if (!empty($variations)) {
        foreach ($variations as $variation) {
            if (isset($variation['attributes']['attribute_pa_city']) && $variation['attributes']['attribute_pa_city'] == 'paris') {
                $lowPrice = $variation['display_price'];
                continue;
            }
            if (isset($variation['attributes']['attribute_pa_city']) && $variation['attributes']['attribute_pa_city'] == 'region') {
                $highPrice = $variation['display_price'];
                continue;
            }
        }
    }

    if (isset($input1['offers']['lowPrice'])) {
        $input1['offers']['lowPrice'] = $lowPrice;
    }
    if (isset($input1['offers']['highPrice'])) {
        $input1['offers']['highPrice'] = $highPrice;
    }

    return $input1;
});

Filter::add('login_message', function ($message) {
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    $errors = new WP_Error();

    if (isset($_GET['key'])) {
        $action = 'resetpass';
    }

    if (isset($_GET['checkemail'])) {
        $action = 'checkemail';
    }

    switch ($action):
        case 'lostpassword':
        case 'checkemail':
            $message = '<p class="message">' . sprintf(
                    __( 'Check your email for the confirmation link, then visit the <a href="%s">login page</a>.' ),
                    wp_login_url()
                ) . '</p>';
            break;
    endswitch;

    return $message;
});

Filter::add('login_errors', function ($error) {
    global $errors;
    $err_codes = $errors->get_error_codes();

    // Invalid username.
    // Default: '<strong>ERROR</strong>: Invalid username. <a href="%s">Lost your password</a>?'
    if (in_array('invalid_username', $err_codes)) {
        $error = '<strong>ERROR</strong>: ' . __('Invalid credential', THEME_TD) . '.';
    }

    // Invalid email.
    if (in_array('invalid_email', $err_codes)) {
        $error = '<strong>ERROR</strong>: ' . __('Invalid credential', THEME_TD) . '.';
    }

    // Incorrect password.
    if (in_array('incorrect_password', $err_codes)) {
        $error = '<strong>ERROR</strong>: ' . __('Invalid credential', THEME_TD) . '.';
    }

   if (in_array('registered', $err_codes)) {
        $error = sprintf(
            __( 'Check your email for the confirmation link, then visit the <a href="%s">login page</a>.' ),
            wp_login_url()
        );
    }

   if (in_array('confirm', $err_codes)) {
       $error = sprintf(
           __( 'Check your email for the confirmation link, then visit the <a href="%s">login page</a>.' ),
           wp_login_url()
       );
    }

    return $error;
});

/**
 * Add custom tinymce toolbar
 */
Filter::add('tiny_mce_before_init', function ($init) {
    // Ajouter des options de taille de police
    $init['fontsize_formats'] = "8px 10px 12px 14px 16px 18px 20px 24px 36px 48px";

    // Ajouter le sélecteur de police à la barre d'outils
    $init['font_formats'] = 'Arial=arial,helvetica,sans-serif;'
        . 'Comic Sans MS=comic sans ms,sans-serif;'
        . 'Courier New=courier new,courier,monospace;'
        . 'Georgia=georgia,palatino;'
        . 'Tahoma=tahoma,arial,helvetica,sans-serif;'
        . 'Times New Roman=times new roman,times;'
        . 'Verdana=verdana,geneva;';

    // Configurer la barre d'outils
    $init['toolbar1'] .= ',fontsizeselect,fontselect';

    return $init;
});