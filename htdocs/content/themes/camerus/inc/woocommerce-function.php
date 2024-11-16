<?php

use Illuminate\Support\Facades\Input;
use Themosis\Support\Facades\Action;

function cmrs_add_to_cart_product_ajax_message($product_id, $event_type, $quantity)
{
    $lang = getShortLangCode(request()->get('clang'));
    $message = [];
    $quantity = wc_stock_amount($quantity);
    $passed_validation = apply_filters('cmrs_woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);
    if ($passed_validation && 'publish' === $product_status) {
        do_action('cmrs_woocommerce_ajax_added_to_cart', $product_id, $lang);
//        $event_type = getEventSalonCitySlugInSession();

        if (! empty($event_type)) {
            $product = new WC_Product_Variable($product_id);
            $variations = $product->get_available_variations();
            $have_variation_price_rate_id = 0;

            foreach ($variations as $variation) {
                if (isset($variation['attributes']) && ! empty($variation['attributes'])) {
                    foreach ($variation['attributes'] as $attr_key => $attr) {
                        if ($attr_key == 'attribute_pa_city' && $attr == $event_type) {
                            $have_variation_price_rate_id = $variation['variation_id'];
                            break;
                        }
                    }
                }
            }
        }

        if (! empty($have_variation_price_rate_id)) {
            $variation_id = $have_variation_price_rate_id;

            $message['success_add_to_cart'] = true;
            $add_to_cart = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
            if ($add_to_cart != false) {
                wc_add_to_cart_message(array($product_id => $quantity), true);
            }
        } else {
            $message['success_add_to_cart'] = false;
            $error_message = __("Le produit n'est pas disponible dans la Zone choisit", THEME_TD);
            wc_add_notice($error_message, 'success');
        }

        $all_notices = WC()->session->get('wc_notices', array());
        $notices_html = [];

        if (isset($all_notices['success']) && ! empty($all_notices['success'])) {
            ob_start();
            wc_get_template("notices/success.php", array(
                'notices' => array_filter($all_notices['success']),
            ));
            $notices_html[] = ob_get_clean();
        }

        if (isset($all_notices['error']) && ! empty($all_notices['error'])) {
            ob_start();
            wc_get_template("notices/success.php", array(
                'notices' => array_filter($all_notices['error']),
            ));
            $notices_html[] = ob_get_clean();
        }

        if (isset($all_notices['notice']) && ! empty($all_notices['notice'])) {
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

        $message['cart'] = $object;
        $message['notices_html'] = $notices_html;
    } else {
        $message['message'] = __('Veuillez reessayer plus tard', THEME_TD);
        $message['product_url'] = apply_filters('cmrs_woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id);
    }

    $message['is_add_to_cart'] = true;

    return $message;
}

Action::add('cmrs_woocommerce_ajax_added_to_cart', function ($product_id, $lang) {
    global $sitepress;

    $sitepress->switch_lang($lang);
    do_action('wpml_switch_language', $lang);
});

function styleroomGetUrl($salon_slug = '', $lang = '')
{
    global $sitepress;

    $home_url = home_url();
    if (! empty($lang)) {
        $home_url = $sitepress->convert_url($home_url, $lang);
    }

    return rtrim($home_url, '/').'/styleroom/'.$salon_slug;
}

function styleroomGetProductUrl($slug = '', $salon_slug = '', $lang = '')
{
    global $sitepress;

    $home_url = home_url();
    if (! empty($lang)) {
        $home_url = $sitepress->convert_url($home_url, $lang);
    }

    return rtrim($home_url, '/').'/styleroom/'.$salon_slug.'/'.$slug;
}

function cmrs_get_post_by_slug($post_name, $post_type = 'post')
{
    $args = array(
        'name' => wc_clean($post_name),
        'post_type' => $post_type,
        'post_status' => ['publish', 'private'],
        'numberposts' => 1
    );
    $my_posts = get_posts($args);

    if (! empty($my_posts)) {
        $salon = $my_posts[0];

        if (! empty($salon) && $salon->post_name === $post_name) {
            return $salon;
        }
    }

    return null;
}