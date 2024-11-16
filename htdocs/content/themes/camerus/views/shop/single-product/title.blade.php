<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
global $product;
?>
<div class="block block-product__title">
  <div class="block-content">
    <div class="block-header"><?php _e('Réf', THEME_TD) ?>. <?php global $product; echo $product->get_sku(); ?></div><!-- .block-header -->
      <?php  the_title('<h1 class="product_title entry-title block-body">', '</h1>'); ?>
    <div class="block-footer">
        <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]') ?>
    </div><!-- .block-footer -->
  </div><!-- .block-content -->
</div><!-- .block-product__title -->
