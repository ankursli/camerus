<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart'); ?>

<?php if ( !WC()->cart->is_empty() ) : ?>

<ul class="cart-content woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
    <?php
    do_action('woocommerce_before_mini_cart_contents');

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0
    && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key) ) {
    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
    $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item,
        $cart_item_key);
    ?>
    <li class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item,
        $cart_item_key)); ?>">

        <div>

            <span class="quantity">
                <i class="badge">
                     <?php echo esc_attr($cart_item['quantity']); ?>
                </i>
            </span>

            <div class="product">
                <?php if ( empty($product_permalink) ) : ?>
                <em class="label"> <?php echo $product_name; ?> </em>
                <?php else : ?>
                <a href="<?php echo esc_url($product_permalink); ?>">
                    <em class="label"> <?php echo $product_name; ?> </em>
                </a>
                <?php endif; ?>
                <strong class="value">
                    <?php echo $product_price; ?> <?php _e('HT', THEME_TD); ?>
                </strong>
            </div>

        </div>
    </li>
    <?php
    }
    }

    do_action('woocommerce_mini_cart_contents');
    ?>

    <li class="total hide">
        <div>
              <span class="quantity">
                <i class="icon icon-cart__estimation-basket"></i>
              </span>
            <div class="product">
                <strong class="value"><?php _e('TOTAL', THEME_TD) ?></strong>
                <small><?php _e('Hors Taxes', THEME_TD) ?></small>
            </div>
        </div>
        <div>
              <span class="quantity">
                <i class="badge"><?php echo WC()->cart->get_cart_contents_count(); ?></i>
              </span>
            <div class="product">
                <strong class="value">
                    <?php echo WC()->cart->get_total_ex_tax() ?>
                </strong>
            </div>
        </div>
    </li>
</ul>

<?php else : ?>

<ul class="cart-content woocommerce-mini-cart cart_list">
    <div class="woocommerce-mini-cart__empty-message">
        <p class="text-center"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>
    </div><!-- .card-footer -->
</ul>

<?php endif; ?>

<?php do_action('woocommerce_after_mini_cart'); ?>

