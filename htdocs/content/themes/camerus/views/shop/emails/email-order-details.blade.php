<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined('ABSPATH') || exit;

$text_align = is_rtl() ? 'right' : 'left';
$currency_symbol = get_woocommerce_currency_symbol(get_woocommerce_currency());

do_action('woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email); ?>

<h2>
    <?php
    if ($sent_to_admin) {
        $before = '<a class="link" href="'.esc_url($order->get_edit_order_url()).'">';
        $after = '</a>';
    } else {
        $before = '';
        $after = '';
    }
    /* translators: %s: Order ID. */
    echo wp_kses_post($before.sprintf(__('[Order #%s]', 'woocommerce').$after.' (<time datetime="%s">%s</time>)', $order->get_order_number(),
            $order->get_date_created()->format('c'), wc_format_datetime($order->get_date_created())));
    ?>
</h2>

<div style="margin-bottom: 40px;">
    <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
        <thead>
        <tr>
            <th class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php esc_html_e('Product', 'woocommerce'); ?></th>
            <th class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
            <th class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php esc_html_e('Price', 'woocommerce'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            $order,
            array(
                'show_sku'      => $sent_to_admin,
                'show_image'    => false,
                'image_size'    => array(32, 32),
                'plain_text'    => $plain_text,
                'sent_to_admin' => $sent_to_admin,
            )
        );
        ?>
        </tbody>
        <tfoot>
        <?php
        $item_totals = $order->get_order_item_totals();
        ?>
        <tr>
            <th class="td" scope="row" colspan="2"
                style="text-align:<?php echo esc_attr($text_align); ?>; <?php echo (1 === $i) ? 'border-top-width: 4px;' : ''; ?>">Mobilier
            </th>
            <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>; <?php echo (1 === $i) ? 'border-top-width: 4px;'
                : ''; ?>"><?php echo $order->get_subtotal().$currency_symbol ?></td>
        </tr>
        <?php if(!empty($order->get_fees())) : ?>
        <?php foreach($order->get_fees() as $fee) :  ?>
        <tr>
            <th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php echo $fee->get_name() ?></th>
            <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>; "><?php echo $fee->get_total().$currency_symbol ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php if(!empty($order->get_discount_total())) : ?>
        <tr>
            <th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr($text_align); ?>;">Réduction</th>
            <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php echo $order->get_discount_total().$currency_symbol ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr($text_align); ?>;">Sous-Total</th>
            <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php echo abs(
                        $cart_value = $order->get_total() - $order->get_total_tax()).$currency_symbol; ?></td>
        </tr>
        <tr>
            <th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr($text_align); ?>;">TVA (20%)</th>
            <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php echo $order->get_total_tax().$currency_symbol ?></td>
        </tr>
        <tr>
            <th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr($text_align); ?>;">Total TTC</th>
            <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php echo $order->get_total().$currency_symbol ?></td>
        </tr>
        <tr>
            <th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr($text_align); ?>;">Moyen de paiement</th>
            <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php echo $order->get_payment_method_title() ?></td>
        </tr>
        <?php
        if ( $order->get_customer_note() ) {
        ?>
        <tr>
            <th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php esc_html_e('Note:', 'woocommerce'); ?></th>
            <td class="td"
                style="text-align:<?php echo esc_attr($text_align); ?>;"><?php echo wp_kses_post(nl2br(wptexturize($order->get_customer_note()))); ?></td>
        </tr>
        <?php
        }
        ?>
        </tfoot>
    </table>
</div>

<?php do_action('woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email); ?>
