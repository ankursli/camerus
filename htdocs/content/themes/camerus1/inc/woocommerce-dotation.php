<?php

use App\StockSalDot;
use App\StockSalDotLog;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\Field;
use Themosis\Support\Facades\Filter;
use Themosis\Support\Facades\Metabox;

/**
 * Dotation manager
 */
Action::add('init', function () {
    class WC_Product_Dotation extends WC_Product
    {

        public $product_type = 'dotation';

        public function __construct($product)
        {
            parent::__construct($product);
        }

        public function get_type()
        {
            return 'dotation';
        }

        public function add_to_cart_url()
        {
            $url = $this->is_purchasable() && $this->is_in_stock() ? remove_query_arg('added-to-cart', add_query_arg('add-to-cart', $this->id))
                : get_permalink($this->id);

            return apply_filters('woocommerce_product_add_to_cart_url', $url, $this);
        }

        public function is_sold_individually()
        {
            return true;
        }

        public function get_max_purchase_quantity()
        {
            return 1;
        }
    }
});

Action::add('admin_footer', function () {
    if ('product' != get_post_type()) :
        return;
    endif;
    ?>
    <script type='text/javascript'>
        jQuery(document).ready(function () {
            //for Price tab
            jQuery('.product_data_tabs .general_tab').addClass('show_if_dotation').show();
            jQuery('#general_product_data .pricing').addClass('show_if_dotation').show();
            //for Inventory tab
            jQuery('.inventory_options').addClass('show_if_dotation').show();
            jQuery('#inventory_product_data ._manage_stock_field').addClass('show_if_dotation').show();
            jQuery('#inventory_product_data ._sold_individually_field').parent().addClass('show_if_dotation').show();
            jQuery('#inventory_product_data ._sold_individually_field').addClass('show_if_dotation').show()
        })
    </script>
    <?php
});

Action::add('woocommerce_single_product_summary', function () {
    global $product;

    if ('dotation' == $product->get_type()) {
        do_action('woocommerce_before_add_to_cart_button'); ?>

        <p class="cart">
            <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" rel="nofollow"
               class="single_add_to_cart_button button alt">
                <?php echo "Add to cart"; ?>
            </a>
        </p>

        <?php do_action('woocommerce_after_add_to_cart_button');
    }
}, 60);

Filter::add('product_type_selector', function ($type) {
    // Key should be exactly the same as in the class product_type
    $type['dotation'] = __('Dotation', THEME_TD);
    return $type;
});

Filter::add('woocommerce_product_class', function ($classname, $product_type) {
    if ($product_type == 'dotation') { // notice the checking here.
        $classname = 'WC_Product_Dotation';
    }

    return $classname;
}, 10, 2);

Filter::add('acf/location/rule_types', function ($choices) {

    $choices['Product']['product_type'] = __('Product type', THEME_TD);

    return $choices;

});

Filter::add('acf/location/rule_values/product_type', function ($choices) {
    $choices = wc_get_product_types();

    return $choices;
});

Filter::add('acf/location/rule_match/product_type', function ($match, $rule, $options) {
    $post_type = !empty($options) && array_key_exists('post_type', $options) ? $options['post_type'] : false;

    // Ensure is a product
    if ($post_type != 'product') {
        return false;
    }

    $selected_type = $rule['value'];
    $current_product_type = $options['current_product_type'];

    if ($rule['operator'] == "==") {
        $match = ($current_product_type == $selected_type);
    } elseif ($rule['operator'] == "!=") {
        $match = ($current_product_type != $selected_type);
    }

    return $match;
}, 10, 3);

Filter::add('acf/location/screen', function ($options) {
    $post_type = array_key_exists('post_type', $options) ? $options['post_type'] : false;

    if (!$post_type) {
        if (!array_key_exists('post_id', $options) || (array_key_exists('post_id', $options) && !$options['post_id'])) {
            return $options;
        }

        $post_type = get_post_type($options['post_id']);

        if ($post_type != 'product') {
            return $options;
        }
    }


    if (isset($options['post_id'])) {
        $post_id = $options['post_id'];

        if ($post_type == 'product') {
            $product = wc_get_product($post_id);
            $current_product_type = $product->get_type();

            $options['current_product_type'] = $current_product_type;
        }
    }

    return $options;
}, 10, 1);

Filter::add('woocommerce_product_data_tabs', function ($tabs) {
    // Key should be exactly the same as in the class product_type
    $tabs['dotation'] = array(
        'label' => __('Mobiliers', THEME_TD),
        'target' => 'dotation_card_options',
        'class' => ('show_if_dotation'),
    );
    return $tabs;
});

Action::add('woocommerce_product_data_panels', function () {
    global $post;

    // Dont forget to change the id in the div with your target of your product tab
    ?>
    <div id='dotation_card_options' class='panel woocommerce_options_panel'>
        <h2>Nothing for the moment ...</h2>
    </div><?php
});

/**
 *  Filter dotation
 */
Filter::add('acf/fields/post_object/query/key=field_5dfa2c8cc81a6', function ($args, $field, $post_id) {
    $args['tax_query'][] = [
        'taxonomy' => 'product_type',
        'field' => 'slug',
        'terms' => 'variable',
    ];

    return $args;
}, 10, 3);

/**
 *  Filter salon dotation list
 */
Filter::add('acf/fields/post_object/query/key=field_5dfca51fd981f', function ($args, $field, $post_id) {
    $args['tax_query'][] = [
        'taxonomy' => 'product_type',
        'field' => 'slug',
        'terms' => 'dotation',
    ];

    return $args;
}, 10, 3);

/**
 *  Filter product dotation list
 */
Filter::add('acf/fields/post_object/result/name=dotation_item', function ($title, $post, $field, $post_id) {

    if ($post->post_type === 'product') {
        $product = wc_get_product($post->ID);
        $sku = $product->get_sku();
        $title = $title . ' #' . $sku;
    }

    return $title;
}, 10, 4);

/**
 *  Filter product dotation list complementaire
 */
Filter::add('acf/fields/post_object/result/name=dotation_add_item', function ($title, $post, $field, $post_id) {

    if ($post->post_type === 'product') {
        $product = wc_get_product($post->ID);
        $sku = $product->get_sku();
        $title = $title . ' #' . $sku;
    }

    return $title;
}, 10, 4);

/**
 *  Filter product salon_products
 */
Filter::add('acf/fields/relationship/result/name=salon_products', function ($title, $post, $field, $post_id) {

    if ($post->post_type === 'product') {
        $product = wc_get_product($post->ID);
        $sku = $product->get_sku();
        $title = $title . ' #' . $sku;
    }

    return $title;
}, 10, 4);

/**
 * Checking and delete when dotation are added to cart
 **/
Filter::add('woocommerce_add_to_cart_validation', function ($passed, $product_id, $quantity) {
    $product = wc_get_product($product_id);
    if (!WC()->cart->is_empty() && $product->is_type('dotation')) {
        foreach (WC()->cart->get_cart() as $cart_item) {
            $_product = wc_get_product($cart_item['product_id']);

            if ($_product->is_type('dotation')) {
                WC()->cart->remove_cart_item($cart_item['key']);
            }
        }
    }

    return $passed;
}, 11, 3);

/**
 * Checking and validating when dotation are added to cart
 **/
Filter::add('woocommerce_add_to_cart_validation', function ($passed, $product_id, $quantity) {
    $product = wc_get_product($product_id);
    if (!WC()->cart->is_empty() && $product->is_type('dotation')) {
        foreach (WC()->cart->get_cart() as $cart_item) {
            $cart_item_ids = array($cart_item['product_id'], $cart_item['variation_id']);

            if (!is_array($product_id) && in_array($product_id, $cart_item_ids)) {
                $passed = false;
                wc_add_notice(__("Vous ne pouvez pas en avoir plus d'une dotation dans le panier", "woocommerce"), "error");
            }
        }
    }

    return $passed;
}, 10, 3);

/**
 * Add Box manager Stock salon
 */
Metabox::make('stock_salon_manager', 'salon')
    ->setTitle(_x('Gestion de stock des dotations', 'metabox', THEME_TD))
    ->add(Field::choice('color', [
        'choices' => [
            'red',
            'green',
            'blue'
        ],
        'multiple' => true
    ]))
    ->setCallback('App\Metabox\StockSalonManager@index')
    ->set();

/**
 * Save Box manager Stock salon
 */
Action::add('save_post', function ($post_id, $post) {
    if ($post->post_type == 'salon') {
        if (array_key_exists('ssm_types', $_POST)) {
            update_post_meta($post_id, '_ssm_types', $_POST['ssm_types']);
        }

        save_salon_stock($_POST);
    }
}, 10, 2);

/**
 * Manage logs stock
 */
Action::add('woocommerce_checkout_order_processed', function ($order_id, $posted_data, $order) {
    $line_items = $order->get_items();

    foreach ($line_items as $item) {
        $product = $item->get_product();

        if ($product->is_type('dotation')) {
            $salon = getEventSalonObjectInSession();
            $salon_ref = get_field('salon_id', $salon->ID);
            $dotation_ref = $product->get_sku();

            $stock = StockSalDot::where('id_salon', $salon_ref)
                ->where('id_dotation', $dotation_ref)
                ->first();

            if ($stock != null) {
                /**
                 * Stock update
                 */
                $old_stock = (int)$stock->stock;
                $new_stock = $old_stock - 1;
                StockSalDot::updateStock($salon_ref, $dotation_ref, $new_stock);

                $slug_type = '';
                $dotation_type = get_field('dotation_type', $product->get_id());
                if (!empty($dotation_type) && is_array($dotation_type)) {
                    $dotation_type = reset($dotation_type);
                    $slug_type = $dotation_type->slug;
                }

                /**
                 * Stock logs
                 */
                $stock_log = new StockSalDotLog();
                $stock_log->id_salon = $salon_ref;
                $stock_log->id_dotation = $dotation_ref;
                $stock_log->stock = $new_stock;
                $stock_log->old_stock = $old_stock;
                $stock_log->title_dotation = $product->get_title();
                $stock_log->slug_type = $slug_type;
                $stock_log->order_id = $order_id;
                $stock_log->save();

                /**
                 * Stock notification
                 */
                $limit_stock = (int)get_field('app_dotation_stock_limit_alert', 'option');
                $limit_stock = !empty($limit_stock) ? $limit_stock : 2;
                if ($new_stock <= $limit_stock) {
                    sendNotificationDotationStock($product, $salon, $new_stock);
                }
            }
        }
    }
});

/**
 * Manage logs stock
 */
Action::add('woocommerce_checkout_order_processed', function ($order_id, $posted_data, $order) {

    $customer_id = $order->get_customer_id();
    $reed_data = getReedDataInfo();

    if (!empty($customer_id) && !empty($reed_data)) {
        update_user_meta($customer_id, 'reed_IdExposants', $reed_data->IdExposants);
        update_user_meta($customer_id, 'reed_Langue', $reed_data->Langue);
        update_user_meta($customer_id, 'billing_phone', $reed_data->Telephone);
    }
});

/**
 * Add cart item data : Dotation product item added
 */
Filter::add('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id) {
    $dt_add_product = getProductToDotation();

    if (!empty($dt_add_product) && is_array($dt_add_product)) {
        $new_value = array(SLUG_REED_PRODUCT_DOTATION => $dt_add_product);
        if (empty($cart_item_data)) {
            return $new_value;
        } else {
            return array_merge($cart_item_data, $new_value);
        }
    }
});

/**
 * Add cart item data session : Dotation product item added
 */
Filter::add('woocommerce_get_cart_item_from_session', function ($item, $values, $key) {
    if (array_key_exists(SLUG_REED_PRODUCT_DOTATION, $values)) {
        $item[SLUG_REED_PRODUCT_DOTATION] = $values[SLUG_REED_PRODUCT_DOTATION];
    }
    return $item;
});

/**
 * Add order item data : Dotation product item added
 */
Action::add('woocommerce_add_order_item_meta', function ($item_id, $values) {
    if (is_array($values) && array_key_exists(SLUG_REED_PRODUCT_DOTATION, $values)) {
        $product_custom_values = $values[SLUG_REED_PRODUCT_DOTATION];
        if (!empty($product_custom_values)) {
            wc_add_order_item_meta($item_id, SLUG_REED_PRODUCT_DOTATION, $product_custom_values);
        }
    }
});

/**
 * Add dotation product to order
 */
Action::add('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    if (!empty($values) && array_key_exists(SLUG_REED_PRODUCT_DOTATION, $values) && !empty($values[SLUG_REED_PRODUCT_DOTATION])) {
        foreach ($values[SLUG_REED_PRODUCT_DOTATION] as $pr) {
            $value = ucfirst(get_the_title($pr['product_id'])) . ' X' . $pr['quantity'];
            $item->add_meta_data(
                __('+ Mobilier', THEME_TD) . '(id:' . $pr['product_id'] . ')',
                $value,
                true
            );
        }
    }
}, 10, 4);

/**
 * Add meta data dotation to email
 */
Filter::add('woocommerce_order_item_name', function ($product_name, $item) {
//    if (!empty($item) && array_key_exists(SLUG_REED_PRODUCT_DOTATION, $item) && isset($item[SLUG_REED_PRODUCT_DOTATION])) {
//        foreach ($item[SLUG_REED_PRODUCT_DOTATION] as $pr) {
//            $value = ucfirst(get_the_title($pr['product_id'])) . ' X' . $pr['quantity'];
//            $product_name .= sprintf(
//                '<ul><li>%s: %s</li></ul>',
//                __('+ Mobilier', THEME_TD) . '(id:' . $pr['product_id'] . ')',
//                $value
//            );
//        }
//    }
    return $product_name;
}, 10, 2);

/**
 * Add Reed base64 Token to order
 */
Action::add('woocommerce_checkout_create_order', function ($order, $data) {
    $reed_info = getReedDataInfo();
    if (!empty($reed_info) && property_exists($reed_info, 'token')) {
        $items = $order->get_items();
        $is_dotation = false;
        foreach ($items as $item) {
            $product_id = $item->get_product_id();
            $product = wc_get_product($product_id);
            $product_type = $product->get_type();

            if ($product_type == 'dotation') {
                $is_dotation = true;
                break;
            }
        }

        if ($is_dotation && !empty($reed_info->token)) {
            $order->update_meta_data('reed_token', $reed_info->token);
        } else {
            removeReedDataInfo();
        }
    }
}, 20, 2);

Filter::add('woocommerce_checkout_registration_required', function ($option) {
    $reed_info = getReedDataInfo();

    if (!empty($reed_info) && property_exists($reed_info, 'SurfaceStand') && !empty($reed_info->SurfaceStand)) {
        $option = false;
    }

    return $option;
}, 9999, 1);



