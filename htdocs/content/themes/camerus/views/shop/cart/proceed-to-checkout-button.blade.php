<?php
/**
 * Proceed to checkout button
 *
 * Contains the markup for the proceed to checkout button on the cart.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/proceed-to-checkout-button.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<div class="block block-wishlist__cta hide">
    <div class="block-content">
        <div class="block-header"></div><!-- .block-header -->
        <div class="block-body">
            <?php if(isEventSalonSession()) : ?>
            <a href="<?php  echo esc_url(wc_get_checkout_url()); ?>"
               class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u">
                <span><?php _e('Poursuivre MA demande de devis', THEME_TD) ?></span>
            </a>
            <?php else : ?>
            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>"
               class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u">
                <span><?php esc_html_e('Poursuivre MA COMMANDE', THEME_TD); ?></span>
            </a>
            <?php endif; ?>
        </div><!-- .block-body -->
        <div class="block-footer"></div><!-- .block-footer -->
    </div><!-- .block-content -->
</div><!-- .block-wishlist__cta -->

