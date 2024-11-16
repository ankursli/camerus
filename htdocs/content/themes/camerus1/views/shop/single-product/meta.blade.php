<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
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

if (!defined('ABSPATH')) {
    exit;
}

global $product;
$metas = get_field('product_options', $product->get_id())
?>

<div class="block block-product__attributes uk">
    <div class="block-content">
        <?php do_action('woocommerce_product_meta_start'); ?>

        <?php if ( wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable')) ) : ?>

        <span class="sku_wrapper hide"><?php esc_html_e('SKU:', 'woocommerce'); ?> <span
                    class="sku"><?php echo ($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'woocommerce'); ?></span></span>

        <?php endif; ?>

        <?php echo wc_get_product_category_list($product->get_id(), ', ',
            '<span class="posted_in">'._n('Category:', 'Categories:', count($product->get_category_ids()), 'woocommerce').' ', '</span>'); ?>

        <?php echo wc_get_product_tag_list($product->get_id(), ', ',
            '<span class="tagged_as hide">'._n('Tag:', 'Tags:', count($product->get_tag_ids()), 'woocommerce').' ', '</span>'); ?>

        <?php do_action('woocommerce_product_meta_end'); ?>

        <dl class="block-body">
            <?php if(!empty($metas) && is_array($metas)) : ?>
            <?php foreach ($metas as $meta) :?>
            <dt><?php echo esc_attr($meta['product_options_title']) ?></dt>
            <dd><?php echo esc_attr($meta['product_options_desc']) ?></dd>
            <?php endforeach; ?>
            <?php endif; ?>
        </dl><!-- .block-body -->
    </div><!-- .block-content -->
</div><!-- .block-product__attributes -->