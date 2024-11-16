<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

global $product;

if (!$product->is_purchasable()) {
    return;
}

echo wc_get_stock_html($product); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

<?php do_action('woocommerce_before_add_to_cart_form'); ?>

<div class="block block-product__characteristics uk">

  <form class="cart block-content"
        action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
        method="post" enctype='multipart/form-data'>

      <?php do_action('woocommerce_before_add_to_cart_button'); ?>

    <div class="block-body uk-grid uk-grid-small uk-flex-middle">

      <div class="uk-width-2-2 uk-flex uk-flex-middle hide">
        <strong class="label"><?php _e('Prix', THEME_TD) ?></strong>
        <div class="value">
          <div class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price'));?>"><?php echo $product->get_price_html(); ?></div>
        </div>
      </div>

      <div class="uk-width-auto uk-flex uk-flex-middle">
        <strong class="label"><?php _e('Quantité', THEME_TD) ?></strong>

        <div class="value">
          <div class="num-spinner uk-flex">

              <?php
              do_action('woocommerce_before_add_to_cart_quantity');

              woocommerce_quantity_input(array(
                  'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                  'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                  'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
                  // WPCS: CSRF ok, input var ok.
              ));

              do_action('woocommerce_after_add_to_cart_quantity');
              ?>

          </div>
        </div>
      </div>
      <div class="uk-width-expand uk-text-right">

        <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>"
                class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12">
          <span><?php echo esc_html($product->single_add_to_cart_text()); ?></span>
        </button>

          <?php do_action('woocommerce_after_add_to_cart_button'); ?>

      </div>
    </div><!-- .block-body -->
  </form><!-- .block-content -->
</div><!-- .block-product__characteristics -->

<?php do_action('woocommerce_after_add_to_cart_form'); ?>

<?php endif; ?>