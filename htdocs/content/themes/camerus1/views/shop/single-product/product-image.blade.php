<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined('ABSPATH') || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if (!function_exists('wc_get_gallery_image_html')) {
    return;
}

global $product;

$columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$attachment_ids = $product->get_gallery_image_ids();
$wrapper_classes = apply_filters('woocommerce_single_product_image_gallery_classes', array(
    'woocommerce-product-gallery',
    'woocommerce-product-gallery--' . ($product->get_image_id() ? 'with-images' : 'without-images'),
    'woocommerce-product-gallery--columns-' . absint($columns),
    'images',
));
?>
<div class="block block-product__slider <?php  /*echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); */ ?>"
     data-columns="<?php /*echo esc_attr($columns);*/ ?>">
    <div class="block-content">
        <div class="block-body slider woocommerce-product-gallery__wrapper show-in-lightbox">

            <?php if(!empty($attachment_ids) && is_array($attachment_ids)) : ?>
            <?php foreach ($attachment_ids as $attachment_id) : ?>
            <a class="img-container img-middle"
               href="<?php echo wp_get_attachment_image_url($attachment_id, 'full') ?>">
                <?php echo wp_get_attachment_image($attachment_id, 'single-product-thumbnail', false, ['width' => '460', 'height' => '460']) ?>
            </a>
            <?php endforeach; ?>
            <?php elseif($product->get_image_id()): ?>
            <a class="img-container img-middle"
               href="<?php echo wp_get_attachment_image_url($post_thumbnail_id, 'full') ?>">
                <?php echo wp_get_attachment_image($post_thumbnail_id, 'single-product-thumbnail', false, ['width' => '460', 'height' => '460']) ?>
            </a>
            <?php else: ?>
            	<?php
                $html = '<div class="woocommerce-product-gallery__image--placeholder img-container img-middle">';
                $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')),
                    esc_html__('Awaiting product image', 'woocommerce'));
                $html .= '</div>';

                echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html,
                    $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
                ?>
            <?php endif; ?>

        </div><!-- .block-body -->
    </div><!-- .block-content -->
</div><!-- .block-product__slider -->