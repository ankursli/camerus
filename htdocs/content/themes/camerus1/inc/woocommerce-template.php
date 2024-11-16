<?php
/**
 * Custom wc template loader
 */

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\Filter;

Action::remove('init', ['WC_Template_Loader', 'init'], 10);

/**
 * Change comment template load
 */
Filter::add('comments_template', ['WC_Template_Loader', 'comments_template_loader']);

/**
 * Custom template part path
 */
Filter::add('wc_get_template_part', function ($template, $slug, $name) {

//	var_dump($slug, $name);
//    $ref_product = rand(100000, 500000);
    if ($slug === 'content' && $name === 'product') {
//        global $post_ID;
//        if (get_field('reference', $post_ID) != "") {
//            $ref_product = get_field('reference', $post_ID);
//        }
//
//        $GLOBALS['reference_produit'] = $ref_product;
        $template = View::make('shop.content-product')->getPath();
    }

    return $template;
});

/**
 * Custom template path
 */
Filter::add('wc_get_template', function ($located, $template_name, $args = [], $template_path = '', $default_path = '') {
    //var_dump($template_name);

    $shop_tmp = null;
    $base_tmp = 'shop.rendered-view';

    $ref_product = rand(1000, 10000);

    /**
     * Listing product template
     */
    if ('loop/loop-start.php' === $template_name) {
        $shop_tmp = 'shop.loop.loop-start';
    }
    if ('loop/loop-end.php' === $template_name) {
        $shop_tmp = 'shop.loop.loop-end';
    }
    if ('loop/pagination.php' === $template_name) {
        $shop_tmp = 'shop.loop.pagination';
    }
    if ('loop/price.php' === $template_name) {
        $shop_tmp = 'shop.loop.price';
    }
    if ('loop/add-to-cart.php' === $template_name) {
        $shop_tmp = 'shop.loop.add-to-cart';
    }
    if ('loop/orderby.php' === $template_name) {
        $shop_tmp = 'shop.loop.orderby';
    }
    if ('loop/result-count.php' === $template_name) {
        $shop_tmp = 'shop.loop.result-count';
    }
    if ('loop/no-products-found.php' === $template_name) {
        $shop_tmp = 'shop.loop.no-products-found';
    }

    /**
     * Single product template
     */
    if ('single-product/title.php' === $template_name) {
//        global $post_ID;
//        if (get_field('reference', $post_ID) != "") {
//            $ref_product = get_field('reference', $post_ID);
//        }
//
//        $GLOBALS['reference_produit'] = $ref_product;
        $shop_tmp = 'shop.single-product.title';
    }
    if ('single-product/short-description.php' === $template_name) {
        $shop_tmp = 'shop.single-product.short-description';
    }
    if ('single-product/meta.php' === $template_name) {
        $shop_tmp = 'shop.single-product.meta';
    }
    if ('single-product/price.php' === $template_name) {
        $shop_tmp = 'shop.single-product.price';
    }
    if ('single-product/add-to-cart/simple.php' === $template_name) {
        $shop_tmp = 'shop.single-product.add-to-cart.simple';
    }
    if ('single-product/add-to-cart/variable.php' === $template_name) {
        $shop_tmp = 'shop.single-product.add-to-cart.variable';
    }
    if ('single-product/add-to-cart/variation-add-to-cart-button.php' === $template_name) {
        $shop_tmp = 'shop.single-product.add-to-cart.variation-add-to-cart-button';
    }
    if ('single-product/product-image.php' === $template_name) {
        $shop_tmp = 'shop.single-product.product-image';
    }
    if ('single-product/product-thumbnails.php' === $template_name) {
        $shop_tmp = 'shop.single-product.product-thumbnails';
    }
    if ('single-product/related.php' === $template_name) {
        $shop_tmp = 'shop.single-product.related';
    }

    /**
     * notices
     */
    if ('notices/notice.php' === $template_name) {
        $shop_tmp = 'shop.notices.notice';
    }

    /**
     * global login form
     */
    if ('global/form-login.php' === $template_name) {
        $shop_tmp = 'shop.global.form-login';
    }
    if ('global/quantity-input.php' === $template_name) {
        $shop_tmp = 'shop.global.quantity-input';
    }


    /**
     * Cart custom template
     */
    if ('cart/cart.php' === $template_name) {
        $shop_tmp = 'shop.cart.cart';
    }
    if ('cart/cart-item-data.php' === $template_name) {
        $shop_tmp = 'shop.cart.cart-item-data';
    }
    if ('cart/cart-shipping.php' === $template_name) {
        $shop_tmp = 'shop.cart.cart-shipping';
    }
    if ('cart/mini-cart.php' === $template_name) {
        $shop_tmp = 'shop.cart.mini-cart';
    }
    if ('cart/cart-totals.php' === $template_name) {
        $shop_tmp = 'shop.cart.cart-totals';
    }
    if ('cart/proceed-to-checkout-button.php' === $template_name) {
        $shop_tmp = 'shop.cart.proceed-to-checkout-button';
    }

    /**
     * Pipeline checkout
     */

    if ('checkout/form-login.php' === $template_name) {
        $shop_tmp = 'shop.checkout.form-login';
    }
    if ('checkout/form-coupon.php' === $template_name) {
        $shop_tmp = 'shop.checkout.form-coupon';
    }
    if ('checkout/form-checkout.php' === $template_name) {
        $shop_tmp = 'shop.checkout.form-checkout';
    }
    if ('checkout/form-billing.php' === $template_name) {
        $shop_tmp = 'shop.checkout.form-billing';
    }
    if ('checkout/form-shipping.php' === $template_name) {
        $shop_tmp = 'shop.checkout.form-shipping';
    }
    if ('checkout/review-order.php' === $template_name) {
        $shop_tmp = 'shop.checkout.review-order';
    }
    if ('checkout/payment.php' === $template_name) {
        $shop_tmp = 'shop.checkout.payment';
    }
    if ('checkout/payment-method.php' === $template_name) {
        $shop_tmp = 'shop.checkout.payment-method';
    }
    if ('checkout/terms.php' === $template_name) {
        $shop_tmp = 'shop.checkout.terms';
    }
    if ('checkout/thankyou.php' === $template_name) {
        $shop_tmp = 'shop.checkout.thankyou';
    }
    if ('checkout/form-pay.php' === $template_name) {
        $shop_tmp = 'shop.checkout.form-pay';
    }
    if ('checkout/order-receipt.php' === $template_name) {
        $shop_tmp = 'shop.checkout.order-receipt';
    }


    /**
     * My account custom template
     */
    if ('myaccount/my-account.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.my-account';
    }
    if ('myaccount/navigation.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.navigation';
    }
    if ('myaccount/dashboard.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.dashboard';
    }
    if ('myaccount/form-edit-account.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.form-edit-account';
    }
    if ('myaccount/my-address.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.my-address';
    }
    if ('myaccount/form-edit-address.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.form-edit-address';
    }
    if ('myaccount/form-lost-password.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.form-lost-password';
    }
    if ('myaccount/form-reset-password.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.form-reset-password';
    }
    if ('myaccount/lost-password-confirmation.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.lost-password-confirmation';
    }
    if ('myaccount/form-login.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.form-login';
    }
    if ('myaccount/orders.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.orders';
    }
    if ('myaccount/my-orders.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.my-orders';
    }
    if ('myaccount/view-order.php' === $template_name) {
        $shop_tmp = 'shop.myaccount.view-order';
    }

    /**
     * Emails
     */
    if ('emails/customer-new-account.php' === $template_name || 'emails/plain/customer-new-account.php' === $template_name) {
        $shop_tmp = 'shop.emails.customer-new-account';
    }
    if ('emails/customer-reset-password.php' === $template_name || 'emails/plain/customer-reset-password.php' === $template_name) {
        $shop_tmp = 'shop.emails.customer-reset-password';
    }
    if ('emails/email-order-details.php' === $template_name) {
        $shop_tmp = 'shop.emails.email-order-details';
    }

    if (!empty($shop_tmp)) {
        $GLOBALS['shop_account_tmp'] = $shop_tmp;
        $located = View::make($shop_tmp)->getPath();
    }

    return $located;
});

/**
 * Locate custom Wishlist template
 */
Filter::add('yith_wcwl_locate_template', function ($located, $path) {
    if ($path === 'wishlist.php') {
        $shop_tmp = 'shop.myaccount.wishlist';
    }

    if ($path === 'wishlist-view.php') {
        $shop_tmp = 'shop.myaccount.wishlist-view';
    }

    if ($path === 'add-to-wishlist.php') {
        $shop_tmp = 'shop.myaccount.add-to-wishlist';
    }

    if ($path === 'add-to-wishlist-button.php') {
        $shop_tmp = 'shop.myaccount.add-to-wishlist-button';
    }

    if (!empty($shop_tmp)) {
        $GLOBALS['shop_account_tmp'] = $shop_tmp;
        $located = View::make($shop_tmp)->getPath();
    }

    return $located;
});

/**
 * Custom WC cart button
 */
Action::remove('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);
Action::add('woocommerce_widget_shopping_cart_buttons', function () {
    global $sitepress;
    $lang = getShortLangCode(request()->get('clang'));
    $sitepress->switch_lang($lang);
    do_action('wpml_switch_language', $lang);
    $original_link = wc_get_cart_url();
    echo '<a href="' . esc_url($original_link) . '" title="' . esc_html__('View cart', 'woocommerce')
        . '" class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u"><span>' . esc_html__('View cart', 'woocommerce')
        . '</span></a>';
}, 10);

/**
 * Custom WC checkout button
 */
Action::remove('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);
Action::add('woocommerce_widget_shopping_cart_buttons', function () {
    $original_link = wc_get_checkout_url();
    echo '<a href="' . esc_url($original_link) . '" title="' . esc_html__('Checkout', 'woocommerce')
        . '" class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u button checkout wc-forward hide"><span>' . esc_html__('Checkout',
            'woocommerce') . '</span></a>';
}, 20);

/**
 * Cart fragments
 */
Filter::add('woocommerce_add_to_cart_fragments', function ($fragments) {
    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_contents();
    ob_end_clean();
    $fragments['ul.woocommerce-mini-cart.cart_list'] = $mini_cart;

    return $fragments;
}, 10, 1);

/**
 * Checkout fragments
 */
Filter::add('woocommerce_update_order_review_fragments', function ($fragments) {
    ob_start();
    echo View::make('common.shop.cart.cart-sidebar')->render();
    $content = ob_get_contents();
    ob_end_clean();
    $fragments['.cmrs-cart-sidebar'] = $content;

    return $fragments;
}, 10, 1);

/**
 * Check Salon city before add to cart
 */
Filter::add('woocommerce_add_to_cart_validation', function ($passed, $product_id, $quantity, $variation_id = '', $variations = '') {
    $current_salon_slug = getEventSalonCitySlugInSession();

    if (!empty($current_salon_slug)) {
        return $passed;
    } elseif (empty($current_salon_slug)) {
        $msg = __("Vous devez choisir un évenement dans l'agenda avant de pouvoir ajouter un produit dans le panier", THEME_TD);
        wc_add_notice($msg, 'error');
    }
}, 10, 5);

/**
 * Custom loop item title
 */
Action::remove('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title');
Action::add('woocommerce_shop_loop_item_title', function () {
    global $product;

    if (is_product()) {
        echo '<span class="title">' . get_the_title() . '</span>';
    } else {
        echo '<h2 class="woocommerce-loop-product__title title">' . $product->get_title() . '</h2>';
    }
});

/**
 * WC custom breadcrumbs
 */
add_action('init', 'woo_remove_wc_breadcrumbs');
function woo_remove_wc_breadcrumbs()
{
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
}

Filter::add('woocommerce_breadcrumb_defaults', function () {
    return array(
        'delimiter' => '',
        'wrap_before' => '<ul class="block-body uk-breadcrumb woocommerce-breadcrumb"  itemprop="breadcrumb">',
        'wrap_after' => '</ul>',
        'before' => '<li>',
        'after' => '</li>',
        'home' => _x('Home', 'breadcrumb', 'woocommerce'),
    );
});

/**
 * Reorder Single product template
 */
// Remove product category/tag meta from its original position
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
// Add product meta in new position
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 39);

/**
 * Disable WC template
 */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);

/**
 * My account page salon
 */
addEndpointWoocommerce(__('Mes salons', THEME_TD), 'salons', View::make('shop.myaccount.salons'));

/**
 * My account page favoris
 */
addEndpointWoocommerce(__('Mes favoris', THEME_TD), 'favoris', View::make('shop.myaccount.favoris'));

/**
 * Remove menu my account
 */
Filter::add('woocommerce_account_menu_items', function ($items) {
    /**
     * Remove menu to items
     */
    unset($items['dashboard']);
    unset($items['downloads']);
    unset($items['edit-address']);

    if (isset($items['orders'])) {
        $items['orders'] = __('Mes commandes', THEME_TD);
    }

    /**
     * Change menu position
     * //     */
    $items = moveElementArray($items, 1, 4);
    $items = moveElementArray($items, 2, 1);

    return $items;
}, 10, 1);

/**
 * @author Rv
 * remove payment in rorder review
 */
Action::remove('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);

/**
 * @author: Rv
 * hook to show paiment option
 */
Action::add('wc_show_payment', 'woocommerce_checkout_payment', 10);

/**
 * @author Rv
 * Add Custom hook to display collateral in checkout page
 */

Action::add('woocommerce_cross_sell_display_checkout', function ($limit = 2, $columns = 2, $orderby = 'rand', $order = 'desc') {

    // et visible cross sells then sort them at random.
    $cross_sells = array_filter(array_map('wc_get_product', WC()->cart->get_cross_sells()), 'wc_products_array_filter_visible');

    wc_set_loop_prop('name', 'cross-sells');
    wc_set_loop_prop('columns', apply_filters('woocommerce_cross_sells_columns', $columns));

    // Handle orderby and limit results.
    $orderby = apply_filters('woocommerce_cross_sells_orderby', $orderby);
    $order = apply_filters('woocommerce_cross_sells_order', $order);
    $cross_sells = wc_products_array_orderby($cross_sells, $orderby, $order);
    $limit = apply_filters('woocommerce_cross_sells_total', $limit);
    $cross_sells = $limit > 0 ? array_slice($cross_sells, 0, $limit) : $cross_sells;

    wc_get_template(
        'cart/cross-sells.php',
        array(
            'cross_sells' => $cross_sells,

            // Not used now, but used in previous version of up-sells.php.
            'posts_per_page' => $limit,
            'orderby' => $orderby,
            'columns' => $columns,
        )
    );
});

Action::add('woocommerce_cart_total_checkout', function () {
    wc_get_template('cart/cart-totals.php');
});

/**
 * @author Rv
 * Add extra field in billing form
 */
Filter::add('woocommerce_billing_fields', function ($fields) {

    $fields['billing_genre'] = array(
        'label' => __('Civilité', 'woocommerce'), // Add custom field label
        'placeholder' => _x('Monsieur', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => false, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'text', // add field type
        'class' => array('my-css')    // add class name
    );

    $fields['billing_accounting_email'] = array(
        'label' => __('Adresse mail comptabilité', THEME_TD), // Add custom field label
        'placeholder' => _x('', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => false, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'text', // add field type
        'class' => array('my-css'),    // add class name
        'priority' => 201
    );

    $fields['billing_dematerialized_invoice'] = array(
        'label' => __('Facture dématérialisée', 'woocommerce'),
        'type' => 'radio',
        'class' => array('form-row-wide'),
        'options' => array(
            'OUI' => __('OUI', THEME_TD),
            'NON' => __('NON', THEME_TD),
        ),
        'default' => '',
        'priority' => 200
    );

    return $fields;
});

/**
 * “Ship to A Different Address” by default
 */
Filter::add('woocommerce_ship_to_different_address_checked', '__return_true');

/**
 * Default value in shipping checkout : Salon address
 */
Filter::add('woocommerce_checkout_fields', function ($fields) {
    $salon = getEventSalonObjectInSession();
    $salon_name = $salon_address = $salon_place = $salon_cp = $salon_city = '';

    if (!empty($salon)) {
        $salon_name = $salon->post_title;
        $salon_address = get_field('salon_address', $salon->ID);
        $salon_place = get_field('salon_place', $salon->ID);
        $salon_cp = get_field('salon_cp', $salon->ID);
        $salon_city = get_field('salon_ville', $salon->ID);
        if (!empty($salon_city)) {
            $salon_city = get_term($salon_city, 'salon_city');
            $salon_city = $salon_city->name;
        }

        $fields['shipping']['shipping_first_name']['default'] = $salon_name;
        $fields['shipping']['shipping_last_name']['default'] = SITE_MAIN_SYS_NAME;
        $fields['shipping']['shipping_company']['default'] = $salon_name;
        $fields['shipping']['shipping_country']['default'] = 'France';
        $fields['shipping']['shipping_address_1']['default'] = $salon_address;
        $fields['shipping']['shipping_address_2']['default'] = $salon_place;
        $fields['shipping']['shipping_city']['default'] = $salon_city;
        $fields['shipping']['shipping_state']['default'] = $salon_city;
        $fields['shipping']['shipping_postcode']['default'] = $salon_cp;
    } else {
        $fields['shipping']['shipping_first_name']['default'] = get_bloginfo('name');
        $fields['shipping']['shipping_last_name']['default'] = SITE_MAIN_SYS_NAME;
        $fields['shipping']['shipping_company']['default'] = get_bloginfo('name');
        $fields['shipping']['shipping_country']['default'] = 'FR';
        $fields['shipping']['shipping_address_1']['default'] = get_option('woocommerce_store_address');
        $fields['shipping']['shipping_address_2']['default'] = get_option('woocommerce_store_address_2');
        $fields['shipping']['shipping_city']['default'] = get_option('woocommerce_store_city');
        $fields['shipping']['shipping_state']['default'] = get_option('woocommerce_store_city');
        $fields['shipping']['shipping_postcode']['default'] = get_option('woocommerce_store_postcode');
    }

    return $fields;
});

/**
 * Default value in billing checkout : Reed Info
 */
Filter::add('woocommerce_checkout_fields', function ($fields) {

    $reed_data = getReedDataInfo();

    if (!empty($reed_data)) {
        $fields['billing']['billing_last_name']['default'] = $reed_data->Nom ? $reed_data->Nom : '';
        $fields['billing']['billing_first_name']['default'] = $reed_data->Prenom ?? '';
        $fields['billing']['billing_company']['default'] = $reed_data->RaisonSociale ?? '';
        $fields['billing']['billing_email']['default'] = $reed_data->Email ?? '';
        $fields['billing']['billing_address_1']['default'] = $reed_data->Adresse1 ?? '';
        $fields['billing']['billing_address_2']['default'] = $reed_data->Adresse2 ?? '';
        $fields['billing']['billing_address_2']['default'] = $reed_data->Adresse3 ?? $fields['billing']['billing_address_2']['default'];
        $fields['billing']['billing_postcode']['default'] = $reed_data->CodePostal ?? '';
        $fields['billing']['billing_phone']['default'] = $reed_data->Telephone ?? '';
        $fields['billing']['billing_city']['default'] = $reed_data->Ville ?? '';
    }

    return $fields;
});

Filter::add('default_checkout_billing_country', function () {
    return 'FR';
});

Filter::add('default_checkout_shipping_country', function () {
    return 'FR';
});

Filter::add('woocommerce_checkout_fields', function ($fields) {

    $fields['billing']['billing_genre'] = array(
        'label' => __('Civilité', 'woocommerce'), // Add custom field label
        'placeholder' => _x('Monsieur', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => false, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'text', // add field type
        'class' => array('my-css')    // add class name
    );

    $fields['billing']['billing_accounting_email'] = array(
        'label' => __('Adresse mail comptabilité', THEME_TD), // Add custom field label
        'placeholder' => _x('', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => false, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'text', // add field type
        'class' => array('my-css'),    // add class name
        'priority' => 201
    );

    $fields['billing']['billing_dematerialized_invoice'] = array(
        'label' => __('Facture dématérialisée', 'woocommerce'),
        'type' => 'radio',
        'class' => array('form-row-wide'),
        'options' => array(
            'OUI' => __('OUI', THEME_TD),
            'NON' => __('NON', THEME_TD),
        ),
        'default' => __('OUI', THEME_TD),
        'priority' => 200
    );

    return $fields;
});

/**
 * Save custom fields in account page to user account
 */
Action::add('woocommerce_save_account_details', function ($user_ID) {
    $postcode = !empty($_POST['billing_postcode']) ? wc_clean($_POST['billing_postcode']) : '';
    update_user_meta($user_ID, 'billing_postcode', $postcode);
//    update_user_meta($user_ID, 'shipping_postcode', $postcode);

    $phone = !empty($_POST['billing_phone']) ? wc_clean($_POST['billing_phone']) : '';
    update_user_meta($user_ID, 'billing_phone', $phone);

    $company = !empty($_POST['billing_company']) ? wc_clean($_POST['billing_company']) : '';
    update_user_meta($user_ID, 'billing_company', $company);
//    update_user_meta($user_ID, 'shipping_company', $company);

    $gender = !empty($_POST['billing_gender']) ? wc_clean($_POST['billing_gender']) : '';
    update_user_meta($user_ID, 'billing_genre', $gender);

//    $tva = !empty($_POST['billing_num_tva']) ? wc_clean($_POST['billing_num_tva']) : '';
//    update_user_meta($user_ID, 'billing_num_tva', $tva);

    $tva = !empty($_POST['billing_eu_vat_number']) ? wc_clean($_POST['billing_eu_vat_number']) : '';
    update_user_meta($user_ID, 'billing_eu_vat_number', $tva);

    $address_1 = !empty($_POST['billing_address_1']) ? wc_clean($_POST['billing_address_1']) : '';
    update_user_meta($user_ID, 'billing_address_1', $address_1);

    $address_1 = !empty($_POST['billing_city']) ? wc_clean($_POST['billing_city']) : '';
    update_user_meta($user_ID, 'billing_city', $address_1);
//    update_user_meta($user_ID, 'shipping_address_1', $address_1);
});

/**
 * Delete Account Functionality
 */
Action::add('woocommerce_after_edit_account_form', function () {
    $delete_url = add_query_arg('wc-api', 'wc-delete-account', home_url('/'));
    $delete_url = wp_nonce_url($delete_url, 'wc_delete_user'); ?>
    <div class="block block-account__delete uk-width-1-1">
        <div class="block-content">
            <div class="block-body">
                <a href="<?php echo $delete_url; ?>" id="cmrs-btn-delete-user-account" class="btn btn-tt_u btn-c_w">
                    <span><?php _e('Delete Account', THEME_TD) ?></span>
                </a>
            </div><!-- .block-body -->
        </div><!-- .block-content -->
    </div><!-- .block-section__block -->
    <?php
});

/**
 * API to delete user account
 */
Action::add('woocommerce_api_' . strtolower('wc-delete-account'), function () {
    if (!current_user_can('manage_options')) {
        $security_check_result = check_admin_referer('wc_delete_user');
        if ($security_check_result) {
            require('./wp-admin/includes/user.php');
            wp_delete_user(get_current_user_id());
            wp_redirect(home_url());
            die();
        }
    }
});

/**
 * View wc custom field in back-office user account
 *
 * @param $profileuser
 */
function cmrs_print_user_admin_fields($profileuser)
{
    $fields = cmsrs_account_fields();
    ?>
    <h2><?php _e('Additional Information', THEME_TD); ?></h2>
    <table class="form-table" id="camerus-additional-information">
        <tbody>
        <?php foreach ($fields as $key => $field_args) { ?>
            <tr>
                <th>
                    <label for="<?php echo $key; ?>"><?php echo $field_args['label']; ?></label>
                </th>
                <td>
                    <?php $field_args['label'] = false; ?>
                    <?php woocommerce_form_field($key, $field_args, get_user_meta($profileuser->ID, $key, true)); ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php
}

add_action('show_user_profile', 'cmrs_print_user_admin_fields', 30); // admin: edit profile
add_action('edit_user_profile', 'cmrs_print_user_admin_fields', 30);

/**
 * Show new extra field in Woocomerce admin Order after billing
 */
Action::add('woocommerce_admin_order_data_after_billing_address', function ($order) {
    echo View::make('shop.admin.after-billing', ['order' => $order])->render();
});

/**
 * Show new extra field in Woocomerce admin Order after shipping
 */
Action::add('woocommerce_admin_order_data_after_shipping_address', function ($order) {
    echo View::make('shop.admin.after-shipping-manifest', ['order' => $order])->render();
});

/*
/**
 * @author
 * To redirect after checkout
 */
Action::add('template_redirect', function () {
    global $wp;
    $url = get_permalink(wc_get_page_id('shop')) . '?order=success';
    if (is_checkout() && !empty($wp->query_vars['order-received'])) {
        $order = new WC_Order($wp->query_vars['order-received']);
        if ($order) {
            $message = get_field('app_order_success_message', 'option') . '.';
            wc_add_notice($message);
            wp_safe_redirect($url);
            //echo '<script>UIkit.modal("#modal-message").show();</script>';
            exit;
        }
    }
});

/**
 * Variation dropdown
 */
Filter::add('woocommerce_dropdown_variation_attribute_options_html', function ($html, $args) {

    if ($args['attribute'] === SLUG_PRODUCT_TAX_ATTRIBUT_COLOR) {

        $args = wp_parse_args(
            apply_filters('woocommerce_dropdown_variation_attribute_options_args', $args),
            array(
                'options' => false,
                'attribute' => false,
                'product' => false,
                'selected' => false,
                'name' => '',
                'id' => '',
                'class' => '',
                'show_option_none' => __('Choose an option', 'woocommerce'),
            )
        );

        // Get selected value.
        if (false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product) {
            $selected_key = 'attribute_' . sanitize_title($args['attribute']);
            $args['selected'] = isset($_REQUEST[$selected_key]) ? wc_clean(wp_unslash($_REQUEST[$selected_key]))
                : $args['product']->get_variation_default_attribute($args['attribute']); // WPCS: input var ok, CSRF ok, sanitization ok.
        }

        $options = $args['options'];
        $product = $args['product'];
        $attribute = $args['attribute'];
        $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
        $id = $args['id'] ? $args['id'] : sanitize_title($attribute);
        $class = $args['class'];
        $show_option_none = (bool)$args['show_option_none'];
        $show_option_none_text = $args['show_option_none'] ? $args['show_option_none']
            : __('Choose an option', 'woocommerce'); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

        if (empty($options) && !empty($product) && !empty($attribute)) {
            $attributes = $product->get_variation_attributes();
            $options = $attributes[$attribute];
        }

        $html = '<div class="value">';
//        $html .= '<span value="">'.esc_html($show_option_none_text).'</span>';
        $html .= '<span class="ui-selectmenu-text">';

        if (!empty($options)) {
            if ($product && taxonomy_exists($attribute)) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms(
                    $product->get_id(),
                    $attribute,
                    array(
                        'fields' => 'all',
                    )
                );

                $current_color = [$product->get_id() => $terms[0]];
                $c_term = reset($current_color);

                $current_color_data = [];
                if (!empty($terms) && is_array($terms)) {
                    foreach ($terms as $_term) {
                        $current_color_data[$product->get_id()][] = $_term->term_id;
                    }
                }

                $other_color = getProductColorVariation($product->get_id());
                $all_color = $current_color_data + $other_color;

                $second_value = false;
                $first_other = reset($other_color);
                if (!empty($other_color)) {
                    $current_color_data_one = reset($current_color_data);
                    $color_one = reset($current_color_data_one);
                    $color_two = reset($first_other);
                    if ($color_one == $color_two) {
                        $second_value = true;
                    }
                }


                $colors = [];
                if (!empty($all_color)) {
                    foreach ($all_color as $c_id => $color) {
                        if (!empty($second_value)) {
                            $colors[$c_id] = get_term(end($color), 'pa_color');
                        } else {
                            $colors[$c_id] = get_term(reset($color), 'pa_color');
                        }
                    }
                }

                foreach ($terms as $term) {
                    if ($term->slug == $c_term->slug) {
                        $html .= '<input type="hidden" id="' . esc_attr($id) . '" class="' . esc_attr($class) . '" name="' . esc_attr($name)
                            . '" data-attribute_name="attribute_'
                            . esc_attr(sanitize_title($attribute)) . '" value="' . esc_attr($term->slug) . '">';
                        break;
                    }
                }

                if (count($colors) <= 1) {
                    $color_picker = get_field('pa_color_picker', 'pa_color_' . $c_term->term_id);
                    if (empty($color_picker)) {
                        $tag_icon = get_field('tag_icon', 'pa_color_' . $c_term->term_id);
                        if (!empty($tag_icon)) {
                            $data_color = $tag_icon['sizes']['picto-color'];
                            $color_picker = 'url(' . $data_color . ')';
                        }
                    }

                    $html .= '<input type="hidden" id="product_pa_color" class="select" value="' . esc_attr($c_term->slug) . '">';
                    $html .= '<span tabindex="0" id="product__characteristics-color-button" role="combobox" aria-expanded="false" aria-autocomplete="list" aria-owns="product__characteristics-color-menu" aria-haspopup="true" class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget">';
                    $html .= '<span class="ui-selectmenu-icon icon icon-selectmenu-arrows ui-icon ui-icon-triangle-1-s"></span>';
                    $html .= '<span class="ui-selectmenu-text disabled">';
                    $html .= '<span style="background:' . $color_picker . '" class="bg"></span>' . $c_term->name . '</span>';
                    $html .= '</span>';
                } else {
                    $html .= '<select id="product_pa_color" class="select">';

                    foreach ($colors as $pid => $term) {
                        $color_picker = get_field('pa_color_picker', 'pa_color_' . $term->term_id);
                        if (empty($color_picker)) {
                            $tag_icon = get_field('tag_icon', 'pa_color_' . $term->term_id);
                            if (!empty($tag_icon)) {
                                $data_color = $tag_icon['sizes']['picto-color'];
                                $color_picker = 'url(' . $data_color . ')';
                            }
                        }

                        $html .= '<option value="' . esc_attr($term->slug) . '" ' . selected(sanitize_title($args['selected']), $term->slug, false)
                            . ' data-bg="' . $color_picker . '" data-url="' . get_permalink($pid) . '">'
                            . esc_html(apply_filters('woocommerce_variation_option_name', $term->name, $term, $attribute, $product)) . '</option>';
                    }
                    $html .= '</select>';
                }
            }
        }

        $html .= '</span>';
        $html .= '</div>';

    } elseif ($args['attribute'] === SLUG_PRODUCT_TAX_ATTRIBUT_CITY) {

        $salon_city = getEventSalonCitySlugInSession();

        $args = wp_parse_args(
            apply_filters('woocommerce_dropdown_variation_attribute_options_args', $args),
            array(
                'options' => false,
                'attribute' => false,
                'product' => false,
                'selected' => false,
                'name' => '',
                'id' => '',
                'class' => '',
                'show_option_none' => __('Choose an option', 'woocommerce'),
            )
        );

        // Get selected value.
        if (false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product) {
            $selected_key = 'attribute_' . sanitize_title($args['attribute']);
            $args['selected'] = isset($_REQUEST[$selected_key]) ? wc_clean(wp_unslash($_REQUEST[$selected_key]))
                : $args['product']->get_variation_default_attribute($args['attribute']); // WPCS: input var ok, CSRF ok, sanitization ok.
        }

        $options = $args['options'];
        $product = $args['product'];
        $attribute = $args['attribute'];
        $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
        $id = $args['id'] ? $args['id'] : sanitize_title($attribute);
        $class = $args['class'];
        $show_option_none = (bool)$args['show_option_none'];
        $show_option_none_text = $args['show_option_none'] ? $args['show_option_none']
            : __('Choose an option', 'woocommerce'); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

        if (empty($options) && !empty($product) && !empty($attribute)) {
            $attributes = $product->get_variation_attributes();
            $options = $attributes[$attribute];
        }

        if (!empty($salon_city)) {
            $html = '<input type="hidden" id="' . esc_attr($name) . '" name="' . esc_attr($name) . '" data-attribute_name="attribute_'
                . esc_attr(sanitize_title($attribute)) . '" value="' . $salon_city . '">';
        } else {
            $html = '<select id="' . esc_attr($id) . '" class="select custom-select ' . esc_attr($class) . '" name="' . esc_attr($name) . '" data-attribute_name="attribute_'
                . esc_attr(sanitize_title($attribute)) . '" data-show_option_none="' . ($show_option_none ? 'yes' : 'no') . '">';
            if (empty($salon_city)) {
                $html .= '<option value="">' . esc_html($show_option_none_text) . '</option>';
            }

            if (!empty($options)) {
                if ($product && taxonomy_exists($attribute)) {
                    // Get terms if this is a taxonomy - ordered. We need the names too.
                    $terms = wc_get_product_terms(
                        $product->get_id(),
                        $attribute,
                        array(
                            'fields' => 'all',
                        )
                    );

                    $variations = $product->get_available_variations();

                    foreach ($variations as $var) {
                        if (array_key_exists('attribute_pa_city', $var['attributes'])
                            && !empty($var['attributes']['attribute_pa_city'] && $var['attributes']['attribute_pa_city'] === $salon_city)
                        ) {
                            $_term = get_term_by('slug', $var['attributes']['attribute_pa_city'], SLUG_PRODUCT_TAX_ATTRIBUT_CITY);
                            if (!empty($_term)) {
                                $html .= '<option value="' . esc_attr($_term->slug) . '" ' . selected(sanitize_title($args['selected']), $_term->slug, false) . '">'
                                    . esc_html(apply_filters('woocommerce_variation_option_name', $_term->name, $_term, $attribute, $product)) . ' - '
                                    . $var['price_html'] . '</option>';
                            }
                        }
                    }
                } else {
                    foreach ($options as $option) {
                        // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                        $selected = sanitize_title($args['selected']) === $args['selected'] ? selected($args['selected'], sanitize_title($option), false)
                            : selected($args['selected'], $option, false);
                        $html .= '<option value="' . esc_attr($option) . '" ' . $selected . '>' . esc_html(apply_filters('woocommerce_variation_option_name', $option,
                                null,
                                $attribute, $product)) . '</option>';
                    }
                }
            }

            $html .= '</select>';
        }
    }

    echo $html;
});

/**
 * Change number of products that are displayed per page (shop page)
 */
Filter::add('loop_shop_per_page', function ($cols) {
    // $cols contains the current number of products per page based on the value stored on Options -> Reading
    // Return the number of products you wanna show per page.
    $cols = 9;
    return $cols;
}, 20);

/**
 * Remove attribute variation from product title
 */
Filter::add('woocommerce_cart_item_name', function ($title, $cart_item, $cart_item_key) {
    $_product = $cart_item['data'];
    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item,
        $cart_item_key);

    if ($_product->is_type('variation')) {
        if (!$product_permalink) {
            return $_product->get_title();
        } else {
            return sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_title());
        }
    }

    return $title;
}, 10, 3);


Action::add('woocommerce_after_checkout_validation', function ($fields, $errors) {
    if (empty($fields['billing_company'])) {
        $errors->add('validation', __('<strong>Le nom de la société</strong> est obligatoire', THEME_TD));
    }
}, 10, 2);

Filter::add('woocommerce_checkout_fields', function ($fields) {
    $fields['billing']['billing_eu_vat_number']['placeholder'] = __('N° TVA', THEME_TD);
    $fields['billing']['billing_eu_vat_number']['label'] = __('N° TVA', THEME_TD);
    $fields['billing']['billing_eu_vat_number']['description'] = __('(sans espace)', THEME_TD);

    return $fields;
}, PHP_INT_MAX);


Filter::add('yith_wcwl_table_product_show_add_to_cart', function() {
    return false;
});