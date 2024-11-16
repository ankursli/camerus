<?php
/**
 * Add to wishlist template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.0
 */

if (!defined('YITH_WCWL')) {
	exit;
} // Exit if accessed directly

global $product;
?>

<div class="yith-wcwl-add-to-wishlist add-to-wishlist-<?php echo $product_id ?>">
	<?php if( !($disable_wishlist && !is_user_logged_in()) ): ?>
    <div class="yith-wcwl-add-button <?php echo ($exists && !$available_multi_wishlist) ? 'hide' : 'show' ?>"
         style="display:<?php echo ($exists && !$available_multi_wishlist) ? 'none' : 'block' ?>">

		<?php yith_wcwl_get_template('add-to-wishlist-' . $template_part . '.php', $atts); ?>

    </div>

	<?php if(!is_account_page()) : ?>
    <div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;">
          <span title="<?php _e('Dans mes favoris', THEME_TD) ?>" data-uk-tooltip>
            <i class="icon icon-product-star-2" style="color: #ff560d; font-size: 20px;"></i>
          </span>
    </div>


    <div class="yith-wcwl-wishlistexistsbrowse <?php echo ($exists && !$available_multi_wishlist) ? 'show' : 'hide' ?>"
         style="display:<?php echo ($exists && !$available_multi_wishlist) ? 'block' : 'none' ?>">
            <span title="<?php _e('Dans mes favoris', THEME_TD) ?>" data-uk-tooltip>
            <i class="icon icon-product-star-2" style="color: #ff560d; font-size: 20px;"></i>
          </span>
    </div>
	<?php endif; ?>

    <div style="clear:both"></div>
    <div class="yith-wcwl-wishlistaddresponse"></div>
	<?php else: ?>
    <a href="<?php echo esc_url(add_query_arg(array('wishlist_notice' => 'true', 'add_to_wishlist' => $product_id), get_permalink(wc_get_page_id('myaccount'))))?>"
       rel="nofollow" class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-add <?php echo str_replace('add_to_wishlist', '', $link_classes) ?>">
      <span class="visible-xs"><?php echo $icon ?></span><span class="hidden-xs"><?php echo $label ?></span>
    </a>
	<?php endif; ?>

</div>

<div class="clear"></div>