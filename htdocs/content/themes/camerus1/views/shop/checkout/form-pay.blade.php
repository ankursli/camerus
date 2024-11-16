@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')
    <main id="main" {{ post_class() }}>
        <div data-uk-spinner="ratio: 2"></div>

        @include('shop.global.breadcrumb')

        <div id="layout" class="layout container-fluid order-pay-custom">
            <div class="layout-body inner">
                <div class="row">

                    <h1 class="order-pay-title">{{ WC()->query->get_endpoint_title( 'order-pay' ) }} - <strong>N°: {{ $order->get_id() }}</strong></h1>

                    <?php

                    do_action('before_woocommerce_pay');

                    $order_id = absint($order_id);

                    // Pay for existing order.
                    if ( isset($_GET['pay_for_order'], $_GET['key']) && $order_id ) { // WPCS: input var ok, CSRF ok.
                    try {
                    $order_key = isset($_GET['key']) ? wc_clean(wp_unslash($_GET['key'])) : ''; // WPCS: input var ok, CSRF ok.
                    $order = wc_get_order($order_id);
                    $hold_stock_minutes = (int) get_option('woocommerce_hold_stock_minutes', 0);

                    // Order or payment link is invalid.
                    if (!$order || $order->get_id() !== $order_id || !hash_equals($order->get_order_key(), $order_key)) {
                        throw new Exception(__('Sorry, this order is invalid and cannot be paid for.', 'woocommerce'));
                    }

                    // Logged out customer does not have permission to pay for this order.
                    if (!current_user_can('pay_for_order', $order_id) && !is_user_logged_in()) {
                        echo '<div class="woocommerce-info">'.esc_html__('Please log in to your account below to continue to the payment form.', 'woocommerce')
                            .'</div>';
                        woocommerce_login_form(
                            array(
                                'redirect' => $order->get_checkout_payment_url(),
                            )
                        );
                    }

                    // Logged in customer trying to pay for someone else's order.
                    if (!current_user_can('pay_for_order', $order_id)) {
                        throw new Exception(__('This order cannot be paid for. Please contact us if you need assistance.', 'woocommerce'));
                    }

                    // Does not need payment.
                    if (!$order->needs_payment()) {
                        /* translators: %s: order status */
                        throw new Exception(sprintf(__('This order&rsquo;s status is &ldquo;%s&rdquo;&mdash;it cannot be paid for. Please contact us if you need assistance.',
                            'woocommerce'), wc_get_order_status_name($order->get_status())));
                    }

                    // Ensure order items are still stocked if paying for a failed order. Pending orders do not need this check because stock is held.
                    if (!$order->has_status(wc_get_is_pending_statuses())) {
                        $quantities = array();

                        foreach ($order->get_items() as $item_key => $item) {
                            if ($item && is_callable(array($item, 'get_product'))) {
                                $product = $item->get_product();

                                if (!$product) {
                                    continue;
                                }

                                $quantities[$product->get_stock_managed_by_id()] = isset($quantities[$product->get_stock_managed_by_id()])
                                    ? $quantities[$product->get_stock_managed_by_id()] + $item->get_quantity() : $item->get_quantity();
                            }
                        }

                        foreach ($order->get_items() as $item_key => $item) {
                            if ($item && is_callable(array($item, 'get_product'))) {
                                $product = $item->get_product();

                                if (!$product) {
                                    continue;
                                }

                                if (!apply_filters('woocommerce_pay_order_product_in_stock', $product->is_in_stock(), $product, $order)) {
                                    /* translators: %s: product name */
                                    throw new Exception(sprintf(__('Sorry, "%s" is no longer in stock so this order cannot be paid for. We apologize for any inconvenience caused.',
                                        'woocommerce'), $product->get_name()));
                                }

                                // We only need to check products managing stock, with a limited stock qty.
                                if (!$product->managing_stock() || $product->backorders_allowed()) {
                                    continue;
                                }

                                // Check stock based on all items in the cart and consider any held stock within pending orders.
                                $held_stock = ($hold_stock_minutes > 0) ? wc_get_held_stock_quantity($product, $order->get_id()) : 0;
                                $required_stock = $quantities[$product->get_stock_managed_by_id()];

                                if ($product->get_stock_quantity() < ($held_stock + $required_stock)) {
                                    /* translators: 1: product name 2: quantity in stock */
                                    throw new Exception(sprintf(__('Sorry, we do not have enough "%1$s" in stock to fulfill your order (%2$s available). We apologize for any inconvenience caused.',
                                        'woocommerce'), $product->get_name(),
                                        wc_format_stock_quantity_for_display($product->get_stock_quantity() - $held_stock, $product)));
                                }
                            }
                        }
                    }

                    WC()->customer->set_props(
                        array(
                            'billing_country'  => $order->get_billing_country() ? $order->get_billing_country() : null,
                            'billing_state'    => $order->get_billing_state() ? $order->get_billing_state() : null,
                            'billing_postcode' => $order->get_billing_postcode() ? $order->get_billing_postcode() : null,
                        )
                    );
                    WC()->customer->save();

                    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

                    if (count($available_gateways)) {
                        current($available_gateways)->set_current();
                    }

                    ?>

                    <form id="order_review" method="post">
                        {{ csrf_field() }}

                        <table class="shop_table">
                            <thead>
                            <tr>
                                <th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                                <th class="product-quantity"><?php esc_html_e('Qty', 'woocommerce'); ?></th>
                                <th class="product-total"><?php esc_html_e('Totals', 'woocommerce'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ( count($order->get_items()) > 0 ) : ?>
                            <?php foreach ( $order->get_items() as $item_id => $item ) : ?>
                            <?php
                            if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
                                continue;
                            }
                            ?>
                            <tr class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'order_item', $item, $order)); ?>">
                                <td class="product-name">
                                    <?php
                                    echo apply_filters('woocommerce_order_item_name', esc_html($item->get_name()), $item, false); // @codingStandardsIgnoreLine

                                    do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, false);

                                    wc_display_item_meta($item);

                                    do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, false);
                                    ?>
                                </td>
                                <td class="product-quantity"><?php echo apply_filters('woocommerce_order_item_quantity_html',
                                        ' <strong class="product-quantity">'.sprintf('&times;&nbsp;%s', esc_html($item->get_quantity())).'</strong>',
                                        $item); ?></td><?php // @codingStandardsIgnoreLine ?>
                                <td class="product-subtotal"><?php echo $order->get_formatted_line_subtotal($item); ?></td><?php // @codingStandardsIgnoreLine ?>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                            <tfoot>
                            <?php if ( $totals ) : ?>
                            <?php foreach ( $totals as $total ) : ?>
                            <tr>
                                <th scope="row" colspan="2"><?php echo $total['label']; ?></th><?php // @codingStandardsIgnoreLine ?>
                                <td class="product-total"><?php echo $total['value']; ?></td><?php // @codingStandardsIgnoreLine ?>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tfoot>
                        </table>

                        <p class="order-pay-method-title"><?php _e('Choisir une méthode de paiement', THEME_TD) ?></p>

                        <div id="payment">
                            <?php if ( $order->needs_payment() ) : ?>
                            <ul class="wc_payment_methods payment_methods methods">
                                <?php
                                if (!empty($available_gateways)) {
                                    foreach ($available_gateways as $gateway) {
                                        wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
                                    }
                                } else {
                                    echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">'
                                        .apply_filters('woocommerce_no_available_payment_methods_message',
                                            esc_html__('Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.',
                                                'woocommerce')).'</li>'; // @codingStandardsIgnoreLine
                                }
                                ?>
                            </ul>
                            <?php endif; ?>
                            <div class="form-row">
                                <input type="hidden" name="woocommerce_pay" value="1"/>

                                <?php wc_get_template('checkout/terms.php'); ?>

                                <?php do_action('woocommerce_pay_order_before_submit'); ?>

                                <?php echo apply_filters('woocommerce_pay_order_button_html',
                                    '<button type="submit" class="button alt" id="place_order" value="'.esc_attr($order_button_text).'" data-value="'
                                    .esc_attr($order_button_text).'">'.esc_html($order_button_text).'</button>'); // @codingStandardsIgnoreLine ?>

                                <?php do_action('woocommerce_pay_order_after_submit'); ?>

                                <?php wp_nonce_field('woocommerce-pay', 'woocommerce-pay-nonce'); ?>
                            </div>
                        </div>
                    </form>

                    <?php

                    } catch (Exception $e) {
                        wc_print_notice($e->getMessage(), 'error');
                    }
                    } elseif ($order_id) {

                        // Pay for order after checkout step.
                        $order_key = isset($_GET['key']) ? wc_clean(wp_unslash($_GET['key'])) : ''; // WPCS: input var ok, CSRF ok.
                        $order = wc_get_order($order_id);

                        if ($order && $order->get_id() === $order_id && hash_equals($order->get_order_key(), $order_key)) {

                            if ($order->needs_payment()) {

                                wc_get_template('checkout/order-receipt.php', array('order' => $order));

                            } else {
                                /* translators: %s: order status */
                                wc_print_notice(sprintf(__('This order&rsquo;s status is &ldquo;%s&rdquo;&mdash;it cannot be paid for. Please contact us if you need assistance.',
                                    'woocommerce'), wc_get_order_status_name($order->get_status())), 'error');
                            }
                        } else {
                            wc_print_notice(__('Sorry, this order is invalid and cannot be paid for.', 'woocommerce'), 'error');
                        }
                    } else {
                        wc_print_notice(__('Invalid order.', 'woocommerce'), 'error');
                    }

                    do_action('after_woocommerce_pay');

                    ?>

                </div><!-- .row -->
            </div><!-- .layout-body -->
        </div><!-- #layout -->

        @include('components.page.reinsurances')

        @include('components.page.footer-social')

    </main><!-- #main -->
@endsection


