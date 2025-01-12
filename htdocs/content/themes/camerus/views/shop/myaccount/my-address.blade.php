<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$customer_id = get_current_user_id();

//if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
//	$get_addresses = apply_filters('woocommerce_my_account_get_addresses', array(
//		'billing'  => __('Billing address', 'woocommerce'),
//		'shipping' => __('Shipping address', 'woocommerce'),
//	), $customer_id);
//} else {
//	$get_addresses = apply_filters('woocommerce_my_account_get_addresses', array(
//		'billing' => __('Billing address', 'woocommerce'),
//	), $customer_id);
//}

$get_addresses = apply_filters('woocommerce_my_account_get_addresses', array(
    'billing' => __('Billing address', 'woocommerce'),
), $customer_id);

$oldcol = 1;
$col = 1;
?>

<div class="col-lg-6 col-md-6 col-sm-8">

    <div class="uk-grid-small" data-uk-grid>
        <!-- blocks -->

        <div class="block block-rte__default uk-width-1-1">
            <div class="block-content">
                <div class="block-body rte">
                    <p>&nbsp;<br>
						<?php echo apply_filters('woocommerce_my_account_my_address_description', __('The following addresses will be used on the checkout page by default.', 'woocommerce')); ?>
                    </p>
                </div><!-- .block-body -->
            </div><!-- .block-content -->
        </div><!-- .block-rte__default -->

        <div class="block block-form__details uk-width-1-1" uk-height-match=".block-form__details [class*=col-]">

			<?php if ( !wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
            <div class="u-columns woocommerce-Addresses col2-set addresses">
				<?php endif; ?>

				<?php foreach ( $get_addresses as $name => $title ) : ?>

                <div class="u-column<?php echo (($col = $col * -1) < 0) ? 1 : 2; ?> col-<?php echo (($oldcol = $oldcol * -1) < 0) ? 1 : 2; ?> woocommerce-Address">
                    <header class="woocommerce-Address-title title">
                        <h3><?php echo $title; ?></h3>
                        <br>
                        <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $name)); ?>"
                           class="edit btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12"><?php _e('Edit', 'woocommerce'); ?></a>
                    </header>
                    <address><?php
						$address = wc_get_account_formatted_address($name);
						echo $address ? wp_kses_post($address) : esc_html_e('You have not set up this type of address yet.', 'woocommerce');
						?></address>
                </div>

				<?php endforeach; ?>

				<?php if ( !wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
            </div>
			<?php endif; ?>

        </div>
    </div>
</div>