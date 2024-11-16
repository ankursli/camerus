<?php
/**
 * Add to wishlist button template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.8
 */

if (!defined('YITH_WCWL')) {
	exit;
} // Exit if accessed directly

global $product;
?>

<a href="<?php echo esc_url(add_query_arg('add_to_wishlist', $product_id))?>"
   title="<?php _e('Ajouter aux favoris', THEME_TD) ?>" rel="nofollow"
   data-product-id="<?php echo $product_id ?>" data-product-type="<?php echo $product_type?>"
   class="<?php echo $link_classes ?>" data-uk-tooltip>
    <i class="icon icon-product-star-1"></i>
</a>
<img src="<?php echo esc_url(YITH_WCWL_URL . 'assets/images/wpspin_light.gif') ?>" class="ajax-loading" alt="loading"
     width="16" height="16" style="visibility:hidden"/>