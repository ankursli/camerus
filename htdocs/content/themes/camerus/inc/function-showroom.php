<?php

function getReduceShowroomAmountFee()
{
    global $woocommerce;

    $salon = getEventSalonObjectInSession();
    if (! empty($salon) && ! $woocommerce->cart->is_empty() && get_field('discount_active', $salon->ID)) {
        $discount_amount = get_field('discount_amount', $salon->ID);
        $discount_type = get_field('discount_type', $salon->ID);
        $cart_items = $woocommerce->cart->cart_contents;

        if (! empty($cart_items) && ! empty($discount_amount)) {

            if ($discount_type === 'currency') {
                return $discount_amount;
            }

            if ($discount_type === 'percent') {
                $fee_amount = 0;
                $cart_fee = $woocommerce->cart->get_fees();
                if (array_key_exists('assurance', $cart_fee) && ! empty($cart_fee['assurance'])) {
                    $fee_amount = (float)$cart_fee['assurance']->amount;
                }
                $cart_total = (float)$woocommerce->cart->subtotal + $woocommerce->cart->shipping_total + $fee_amount;
                return $cart_total * ($discount_amount / 100);
            }
        }
    }

    return 0;
}

function applyShowroomDiscount($salon_id)
{
    global $woocommerce;

    if (! empty($salon_id) && ! $woocommerce->cart->is_empty() && get_field('discount_active', $salon_id)) {
        $discount_amount = get_field('discount_amount', $salon_id);
        $discount_type = get_field('discount_type', $salon_id);

        $cart_items = $woocommerce->cart->cart_contents;
        if (! empty($cart_items) && ! empty($discount_amount)) {
            $cart_fee = $woocommerce->cart->get_fees();
            if ($discount_type === 'currency') {
                $surcharge_amount = $discount_amount;
            }

            if ($discount_type === 'percent') {
                $fee_amount = 0;
                if (array_key_exists('assurance', $cart_fee) && ! empty($cart_fee['assurance'])) {
                    $fee_amount = (float)$cart_fee['assurance']->amount;
                }
                $cart_total = (float)$woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total + $fee_amount;
                $surcharge_amount = $cart_total * ($discount_amount / 100);
            }

            if (! empty($surcharge_amount)) {
                $items = $woocommerce->cart->get_cart();

                foreach ($items as $item => $values) {
                    WC()->cart->cart_contents[$item]['discount_showroom_amount'] = $surcharge_amount;
                    WC()->cart->cart_contents[$item]['discount_showroom_type'] = $discount_type;
                }

                WC()->cart->set_session();
            }
        }
    }
}