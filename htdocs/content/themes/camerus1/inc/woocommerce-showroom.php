<?php

Filter::add('woocommerce_cart_totals_coupon_label', function ($sprintf, $coupon) {
    if ($coupon->get_code() == 'showroomdiscount') {
        $discount_title = __('Remise mobilier', THEME_TD);
        $salon = getEventSalonObjectInSession();
        if (! empty($salon)) {
            $_discount_title = get_field('discount_title', $salon->ID);
            if (! empty($_discount_title)) {
                $discount_title = $_discount_title;
            }
        }

        return $discount_title;
    }
    return $sprintf;
});

Filter::add('woocommerce_coupon_get_amount', function ($data, $coupon) {
    if (! is_admin() && $coupon->get_code() === 'showroomdiscount') {
        return abs(getReduceShowroomAmountFee());
    }
    return $data;
});

Action::add('woocommerce_before_cart', function () {
    $coupon_code = 'showroomdiscount';
    $coupon_amount = abs(getReduceShowroomAmountFee());
    if (WC()->cart->has_discount($coupon_code) && ! empty($coupon_amount)) {
        return;
    }

    if ($coupon_amount) {
        WC()->cart->apply_coupon($coupon_code);
        wc_print_notices();
    } else {
        WC()->cart->remove_coupon($coupon_code);
    }
});

Filter::add('woocommerce_coupon_message', function ($msg, $msg_code, $coupon) {
    if ($coupon->get_code() == 'showroomdiscount') {
        $discount_title = __('Remise mobilier', THEME_TD);
        $salon = getEventSalonObjectInSession();
        if (! empty($salon)) {
            $_discount_title = get_field('discount_title', $salon->ID);
            if (! empty($_discount_title)) {
                $discount_title = $_discount_title;
            }
        }
        if ($msg_code == $coupon::WC_COUPON_SUCCESS) {
            return $discount_title.' '.__('appliqué avec succès.', THEME_TD);
        }
        if ($msg_code == $coupon::WC_COUPON_REMOVED) {
            return $discount_title.' '.__('supprimé avec succès.', THEME_TD);
        }
    }
    return $msg;
});

Filter::add('woocommerce_cart_totals_coupon_html', function ($coupon_html, $coupon, $discount_amount_html) {
    if ($coupon->get_code() == 'showroomdiscount') {
        $coupon_html = str_replace('woocommerce-remove-coupon', 'woocommerce-remove-coupon hide', $coupon_html);
    }
    return $coupon_html;
});

Filter::add('woocommerce_get_order_item_totals', function ($total_rows) {
    $rows = moveElementArray($total_rows, 2, 1);

    if (! empty($rows)) {
        foreach ($rows as $key => $row) {
            if ($key == 'discount') {
                $rows['discount']['label'] = __('Remise :', THEME_TD);
            }
        }
    }

    return $rows;
});