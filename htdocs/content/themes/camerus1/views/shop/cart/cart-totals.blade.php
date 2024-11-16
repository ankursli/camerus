<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.3.6
 */

defined('ABSPATH') || exit;

?>
<div class="cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

    <?php do_action('woocommerce_before_cart_totals'); ?>

    <h2 class="hide"><?php esc_html_e('Cart totals', 'woocommerce'); ?></h2>

    <ul class="block-body">

        <li class="cart-subtotal">
            <em class="name"><?php esc_html_e('Mobilier', THEME_TD); ?></em>
            <strong class="value" data-title="<?php esc_html_e('Mobilier', THEME_TD); ?>"><?php wc_cart_totals_subtotal_html(); ?></strong>
        </li>

        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action('woocommerce_cart_totals_before_shipping'); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action('woocommerce_cart_totals_after_shipping'); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc') ) : ?>

        <li class="shipping">
            <em class="name"><?php esc_html_e('Shipping', 'woocommerce'); ?></em>
            <strong class="value" data-title="<?php esc_attr_e('Shipping', 'woocommerce'); ?>"><?php woocommerce_shipping_calculator(); ?></strong>
        </li>

        <?php endif; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
        <li class="fee">
            <em class="name"><?php echo esc_html($fee->name); ?></em>
            <strong class="value" data-title="<?php echo esc_attr($fee->name); ?>"><?php wc_cart_totals_fee_html($fee); ?></strong>
        </li>
        <?php endforeach; ?>

        <?php if(empty(WC()->cart->get_fees())) : ?>
        <li class="fee">
            <em class="name"><?php esc_html_e('Assurance', THEME_TD); ?></em>
            <strong class="value" data-title="<?php esc_html_e('Assurance', THEME_TD); ?>"><?php echo WC()->cart->get_fee_total()
                    .get_woocommerce_currency_symbol(); ?></strong>
        </li>
        <?php endif; ?>

        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
        <li class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
            <em class="name"><?php wc_cart_totals_coupon_label($coupon); ?></em>
            <strong class="value"
                    data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>"><?php wc_cart_totals_coupon_html($coupon); ?></strong>
        </li>
        <?php endforeach; ?>

        <li class="cart-subtotal">
            <em class="name"><?php esc_html_e('Sous-total', THEME_TD); ?></em>
            <strong class="value" data-title="<?php esc_html_e('Sous-total', THEME_TD); ?>"><?php echo WC()->cart->get_total_ex_tax(); ?></strong>
        </li>

        <?php
        if ( wc_tax_enabled() && !WC()->cart->display_prices_including_tax() ) {
        $taxable_address = WC()->customer->get_taxable_address();
        $estimated_text = '';
        $tax_rate = WC_Tax::_get_tax_rate(1);
        $tax_rate = (int) $tax_rate['tax_rate'];
        $tax_rate_text = ' ('.$tax_rate.'%) ';

        if (WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()) {
            /* translators: %s location. */
            $estimated_text = sprintf(' <small>'.esc_html__('(estimated for %s)', 'woocommerce').'</small>',
                WC()->countries->estimated_for_prefix($taxable_address[0]).WC()->countries->countries[$taxable_address[0]]);
        }

        if ( 'itemized' === get_option('woocommerce_tax_total_display') ) {
        foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
        ?>
        <li class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
            <em class="name"><?php echo esc_html($tax->label).$tax_rate_text
                    .$estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></em>
            <strong class="value" data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></strong>
        </li>
        <?php
        }
        } else {
        ?>
        <li class="tax-total">
            <em class="name"><?php echo esc_html(WC()->countries->tax_or_vat()).$tax_rate_text
                    .$estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></em>
            <strong class="value" data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); ?></strong>
        </li>
        <?php
        }
        }
        ?>

    </ul>

    <?php do_action('woocommerce_cart_totals_before_order_total'); ?>

    <div class="block-footer">
        <em class="name">
            <?php esc_html_e('Total', 'woocommerce'); ?>
            <span>TTC</span>
        </em>
        <strong class="value" data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>"><?php wc_cart_totals_order_total_html(); ?></strong>
    </div><!-- .block-footer -->

    <?php do_action('woocommerce_cart_totals_after_order_total'); ?>

    <div class="wc-proceed-to-checkout">
        <?php do_action('woocommerce_proceed_to_checkout'); ?>
    </div>

    <?php do_action('woocommerce_after_cart_totals'); ?>

</div>
