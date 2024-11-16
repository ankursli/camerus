<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version     3.0.0
 */

global $product;

if (!defined('ABSPATH')) {
    exit;
}
$custom_related_products = get_field('product_other_suggest', $product->get_id());
if (!empty($custom_related_products)) {
    $all_related = [];
    foreach ($custom_related_products as $custom_related_product) {
        if (property_exists($custom_related_product, 'ID')) {
            $c_id = $custom_related_product->ID;
        } elseif (!empty((int) $custom_related_product)) {
            $c_id = $custom_related_product;
        }
        if (!empty($c_id)) {
            $all_related[] = wc_get_product($c_id);
        }
    }
    $related_products = $all_related;
}

if ( $related_products ) : ?>

<div class="block-content" data-uk-slider>
    <p class="block-header"><?php esc_html_e('Vous aimerez aussi', THEME_TD); ?></p><!-- .block-header -->
    <div class="block-body uk-grid uk-grid-small uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-2@s">

        <?php woocommerce_product_loop_start(); ?>

        <?php foreach ($related_products as $related_product) : ?>

				<?php
            $post_object = get_post($related_product->get_id());

            setup_postdata($GLOBALS['post'] =& $post_object);

            wc_get_template_part('content', 'product'); ?>

			<?php endforeach; ?>

        <?php woocommerce_product_loop_end(); ?>

    </div><!-- .block-body -->
</div><!-- .block-content -->

<?php endif;

wp_reset_postdata();
