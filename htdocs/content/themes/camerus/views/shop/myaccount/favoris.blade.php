<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product, $post, $current_user;

?>


<div class="col-sm-6">

    <?php echo do_shortcode('[yith_wcwl_wishlist]') ?>

</div>

<aside class="col-sm-2">
    <div class="block block-cart__title hidden-xs">
        <div class="block-content">
            <div class="block-body"><?php _e('Estimation panier', THEME_TD) ?></div><!-- .block-body -->
        </div><!-- .block-content -->
    </div><!-- .block-cart__title -->
    <div class="block block-cart__estimation hidden-xs">
        <div class="block-content">
            <div class="block-header">
                <i class="icon icon-cart__estimation-basket"></i>
            </div><!-- .block-header -->
            <ul class="block-body">
                <li>
                    <em class="name">
                        <?php _e('Panier', THEME_TD) ?>
                        <span class="number">{{ WC()->cart->get_cart_contents_count()  }}</span>
                    </em>
                    <strong class="value">{!! WC()->cart->get_total_ex_tax() !!}</strong>
                </li>
                <li>
                    <em class="name">
                        <?php _e('TOTAL H.T', THEME_TD) ?>
                    </em>
                    <strong class="value">
                        {!! WC()->cart->get_total_ex_tax() !!}
                    </strong>
                </li>
                <li>
                    <em class="name">
                        <?php _e('TVA', THEME_TD) ?>
                    </em>
                    <strong class="value">0€</strong>
                </li>
            </ul><!-- .block-body -->
            <div class="block-footer">
                <em class="name">
                    <?php _e('TOTAL', THEME_TD) ?>
                    <span><?php _e('TTC', THEME_TD) ?></span>
                </em>
                <strong class="value">
                    {!! WC()->cart->get_total() !!}
                </strong>
            </div><!-- .block-footer -->
        </div><!-- .block-content -->
    </div><!-- .block-cart__estimation -->

    <div class="block block-wishlist__cta">
        <div class="block-content">
            <div class="block-header"></div><!-- .block-header -->
            <div class="block-body">
                <button class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u hide">
                    <span><?php _e('Ajouter ma wishlist<br>à mon panier', THEME_TD) ?></span>
                </button>
            </div><!-- .block-body -->
            <div class="block-footer"></div><!-- .block-footer -->
        </div><!-- .block-content -->
    </div><!-- .block-wishlist__cta -->
</aside>