<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version     3.3.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<?php /*
<table class="shop_table woocommerce-checkout-review-order-table hide">
	<thead>
		<tr>
			<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<td class="product-name">
							<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; ?>
							<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
						</td>
						<td class="product-total">
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
						</td>
					</tr>
					<?php
				}
			}

			do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>

		<tr class="cart-subtotal">
			<th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php _e( 'Total', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</tfoot>
</table>

*/?>

<?php do_action('woocommerce_review_order_before_cart_contents');  ?>

<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) { ?>

<?php
$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
$product_permalink = apply_filters(
    'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key
);
$reference = get_field('reference', $product_id) != '' ? get_field('reference', $product_id) : "";
$salon = getEventSalonObjectInSession();
$_cat = getPrimaryTaxTerm('product_cat', true, $_product->get_id()) ? getPrimaryTaxTerm('product_cat', true, $_product->get_id())->name : '';
$count_product = $cart_item['quantity'];
$suffixe_num = ($count_product > 1) ? __('exemplaires', THEME_TD) : __('exemplaire', THEME_TD);
$product_color = $_product->get_attribute(SLUG_PRODUCT_TAX_ATTRIBUT_COLOR);
$product_city = $_product->get_attribute(SLUG_PRODUCT_TAX_ATTRIBUT_CITY);
?>

<div class="block block-productlist__item productlist__item-mini uk-width-1-1 uk uk-grid-margin uk-first-column">
    <div class="block-content">
        <div class="uk-grid uk-grid-small">

            <div class="block-aside uk-width-1-4">
                <a class="img-container img-middle" href="#" title="Next">
                    <?php
                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                    echo $thumbnail; // PHPCS: XSS ok.
                    ?>
                </a>
            </div><!-- .block-header -->
            <div class="block-body uk-width-3-4">

                <div class="top">
                    <div class="left">
                        <h2 class="title" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                            <?php
                            echo wp_kses_post(
                                apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key).'&nbsp;'
                            );

                            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                            // Backorder notification.
                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                echo wp_kses_post(
                                    apply_filters(
                                        'woocommerce_cart_item_backorder_notification',
                                        '<p class="backorder_notification">'.esc_html__('Available on backorder', 'woocommerce').'</p>', $product_id
                                    )
                                );
                            }
                            ?>
                        </h2>
                        <span class="ref">
									Réf. <?php echo $_product->get_sku(); ?> - <?php echo $_cat; ?>
								  </span>
                    </div>
                    <div class="right">
                        <div class="value uk-flex-column uk-flex-wrap-bottom uk-text-right">
                            <span class="quantity"><?php echo $count_product.' '.$suffixe_num?> </span>
                            <div>
                                <span class="place hide"><?php  echo $product_city ?></span>
                                <strong class="product-price price">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key
                                    ); // PHPCS: XSS ok.
                                    ?>
                                </strong>
                                <strong class="price_type" style="color: #ff9149">&nbsp;<?php _e('HT', THEME_TD); ?></strong>
                            </div>
                        </div>
                    </div>
                </div><!-- .block-body -->
                <div class="bottom">
                    <?php
                    if (array_key_exists("attribute_pa_color", $cart_item['variation'])) {
                        echo wc_get_formatted_cart_item_data($cart_item);
                    } else {
                        echo '<div class="variation"></div>';
                    }
                    ?>
                    <div class="product-price-review">
                        <strong class="product-price product-subtotal price"
                                data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>">
                            <?php
                            echo apply_filters(
                                'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item,
                                $cart_item_key
                            ); // PHPCS: XSS ok.
                            ?>
                        </strong>
                    </div>
                </div><!-- .block-footer -->
            </div><!-- .block-body -->
        </div>
    </div><!-- .block-content -->
</div>
<?php } ?>

<?php do_action('woocommerce_review_order_after_cart_contents'); ?>

