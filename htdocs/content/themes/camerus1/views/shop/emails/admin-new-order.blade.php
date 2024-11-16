<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo SITE_MAIN_SYS_NAME; ?> : <?php echo 'Mobilier'; ?>.
        &lt;contact@camerus.fr&gt; </title>
    <style type="text/css">
        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
            line-height: 100%;
        }

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 1em 0;
        }

        h1, h2, h3, h4, h5, h6 {
            color: black !important;
        }

        h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {
            color: blue !important;
        }

        h1 a:active, h2 a:active, h3 a:active, h4 a:active, h5 a:active, h6 a:active {
            color: red !important;
        }

        h1 a:visited, h2 a:visited, h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
            color: purple !important;
        }

        table td {
            border-collapse: collapse;
        }

        table td.pic {
            padding-top: 5px;
            padding-bottom: 5px
        }

        table td.pic img {
            width: auto;
            max-height: 100px;
            display: block;
            margin-left: auto;
            margin-right: auto
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: orange;
        }

        @media only screen and (max-device-width: 480px) {
            a[href^="tel"], a[href^="sms"] {
                text-decoration: none;
                color: black; /* or whatever your want */
                pointer-events: none;
                cursor: default;
            }

            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: orange !important; /* or whatever your want */
                pointer-events: auto;
                cursor: default;
            }
        }

        @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
            a[href^="tel"], a[href^="sms"] {
                text-decoration: none;
                color: blue;
                pointer-events: none;
                cursor: default;
            }

            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: orange !important;
                pointer-events: auto;
                cursor: default;
            }
        }

        @media only screen and (-webkit-min-device-pixel-ratio: 2) {
            /* Put your iPhone 4g styles in here */
        }

        @media only screen and (-webkit-device-pixel-ratio: .75) {
            /* Put CSS for low density (ldpi) Android layouts in here */
        }

        @media only screen and (-webkit-device-pixel-ratio: 1) {
            /* Put CSS for medium density (mdpi) Android layouts in here */
        }

        @media only screen and (-webkit-device-pixel-ratio: 1.5) {
            /* Put CSS for high density (hdpi) Android layouts in here */
        }
    </style>
    <!--[if IEMobile 7]>
    <style type="text/css">
        /* Targeting Windows Mobile */
    </style>
    <![endif]-->
    <!--[if gte mso 9]>
    <style>
        /* Target Outlook 2007 and 2010 */
    </style>
    <![endif]-->
</head>
<body>

<table id="identifiant" cellpadding="0" cellspacing="0" align="center" border="0" summary=""
       style="font-family: 'Arial'; font-size: 12px; line-height:normal">
    <tr>
        <td width="768">

            <table cellpadding="0" cellspacing="0" border="0"
                   style="font-family:Arial; line-height:normal; font-size: 12px">
                <tr>
                    <td>
                        <img src="<?php echo get_template_directory_uri() . '/dist/images/header-logo.svg' ?>"
                             width="200" height="64" class="" alt=""
                             srcset="<?php echo get_template_directory_uri() . '/dist/images/header-logo.svg' ?>">
                    </td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>

            <table id="identifiant" width="100%" cellpadding="0" cellspacing="0" border="0" summary=""
                   style="font-family: 'Arial'; line-height:normal; font-size: 18px">
                <tr>
                    <td height="10" bgcolor="#616d70"></td>
                </tr>
                <tr>
                    <td height="20"></td>
                </tr>
                <tr>
                    <td><?php _e('Demande sur le site', THEME_TD); ?>
                        : <?php echo ! empty($salon) ? $order->get_billing_company()
                            : '' ?></td>
                </tr>
                <tr>
                    <td height="20"></td>
                </tr>

            </table>

            <table cellpadding="0" cellspacing="0" border="0"
                   style="font-family:Arial; line-height:normal; font-size: 12px">
                <tr>
                    <td><strong><?php _e('SOCIÉTÉ', THEME_TD); ?> :</strong></td>
                </tr>
                <tr>
                    <td><?php _e('Société', THEME_TD); ?> : <?php echo $order->get_billing_company(); ?></td>
                </tr>
                <tr>
                    <td><?php _e('TVA', THEME_TD); ?> : <?php echo $order->get_meta('_billing_eu_vat_number'); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Facture dématérialisée', THEME_TD); ?>
                        : <?php echo $order->get_meta('billing_dematerialized_invoice'); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Contact', THEME_TD); ?> :
                        M <?php echo $order->get_billing_first_name(); ?><?php echo $order->get_billing_last_name(); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Tél', THEME_TD); ?> : <?php echo $order->get_billing_phone(); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Mail', THEME_TD); ?> : <?php echo $order->get_billing_email(); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Adresse mail comptabilité', THEME_TD); ?>
                        : <?php echo $order->get_meta('billing_accounting_email'); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Adresse', THEME_TD); ?>
                        : <?php echo $order->get_billing_address_1(); ?><?php echo $order->get_billing_address_2(); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Ville', THEME_TD); ?> : <?php echo $order->get_billing_city(); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Code postal', THEME_TD); ?> : <?php echo $order->get_billing_postcode(); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Pays', THEME_TD); ?>
                        : <?php echo WC()->countries->countries[$order->get_billing_country()]; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Déjà client', THEME_TD); ?> : <?php _e('Oui', THEME_TD) ?></td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>

            <?php if (! empty(getEventSalonCitySlugInSession())) : ?>
            <table cellpadding="0" cellspacing="0" border="0"
                   style="font-family:Arial; line-height:normal; font-size: 12px">
                <tr>
                    <td><?php _e('Zone', THEME_TD); ?> : <?php echo ucfirst(getEventSalonCitySlugInSession()); ?></td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>
            <?php endif; ?>

            <?php if (! empty($order->get_customer_note())) : ?>
            <table cellpadding="0" cellspacing="0" border="0"
                   style="font-family:Arial; line-height:normal; font-size: 12px">
                <tr>
                    <td><?php _e('Commentaires', THEME_TD); ?>
                        : <?php echo wp_kses_post(nl2br(wptexturize($order->get_customer_note()))); ?></td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>
            <?php endif; ?>

            <?php if (! empty($salon)) : ?>
            <table cellpadding="0" cellspacing="0" border="0"
                   style="font-family:Arial; line-height:normal; font-size: 12px">
                <tr>
                    <td><strong><?php _e('SALON', THEME_TD); ?> :</strong></td>
                </tr>
                <tr>
                    <td><?php _e('Salon', THEME_TD); ?> : <?php echo $salon->post_title; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Début', THEME_TD); ?>
                        : <?php echo date('d/m/Y', strtotime($salon->salon_start_date)); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Fin', THEME_TD); ?>
                        : <?php echo ! empty($salon->salon_end_date) ? date('d/m/Y', strtotime($salon->salon_end_date))
                            : ''; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Livraison', THEME_TD); ?>
                        : <?php echo $salon->salon_place; ?><?php echo $salon->salon_address; ?><?php echo $salon->salon_ville_name; ?>
                        France
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Numéro de stand', THEME_TD); ?>
                        : <?php echo $order->get_meta('numero_de_stand'); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Hall', THEME_TD); ?> : <?php echo $order->get_meta('hall_stand'); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Allée', THEME_TD); ?> : <?php echo $order->get_meta('allee_stand'); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Nom du Stand', THEME_TD); ?> : <?php echo $order->get_meta('nom_stand'); ?></td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>
            <?php elseif(isEventSalonSession() || isset($_POST['_event-name'])) : ?>
            <table cellpadding="0" cellspacing="0" border="0"
                   style="font-family:Arial; line-height:normal; font-size: 12px">
                <tr>
                    <td><strong><?php _e('SALON', THEME_TD); ?> :</strong></td>
                </tr>
                <tr>
                    <td><?php _e('Salon', THEME_TD); ?>
                        : <?php echo isset($_POST['_event-name']) ? $_POST['_event-name'] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Date début', THEME_TD); ?>
                        : <?php echo isset($_POST['_event-date']) ? $_POST['_event-date'] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Date fin', THEME_TD); ?>
                        : <?php echo isset($_POST['_event-end-date']) ? $_POST['_event-end-date'] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Livraison', THEME_TD); ?>
                        : <?php echo $_POST['_event-place']; ?><?php echo isset($_POST['_event-city']) ? $_POST['_event-city'] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Hall', THEME_TD); ?>
                        : <?php echo isset($_POST['_event-hall']) ? $_POST['_event-hall'] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Allée', THEME_TD); ?>
                        : <?php echo isset($_POST['_event-wing']) ? $_POST['_event-wing'] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Nom du Stand', THEME_TD); ?>
                        : <?php echo isset($_POST['_event-stand']) ? $_POST['_event-stand'] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Numéro du Stand', THEME_TD); ?>
                        : <?php echo isset($_POST['_event-number']) ? $_POST['_event-number'] : ''; ?></td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>
            <?php endif; ?>
            <table width="100%" cellspacing="0" cellpadding="0"
                   style="text-align: left; font-family: 'Arial'; font-size: 12px; line-height:normal">
                <thead>
                <tr style="color:white">
                    <th bgcolor="#616d70" height="20" class="pic"><?php _e('Photo', THEME_TD); ?></th>
                    <td bgcolor="#616d70" width="20"></td>
                    <th bgcolor="#616d70" class="desc"><?php _e('Description du produit', THEME_TD); ?></th>
                    <td bgcolor="#616d70" width="20"></td>
                    <th bgcolor="#616d70" class="ref"><?php _e('Réf', THEME_TD); ?></th>
                    <td bgcolor="#616d70" width="20"></td>
                    <th bgcolor="#616d70" class="pu" align="center"><?php _e('Prix Unitaire', THEME_TD); ?></th>
                    <td bgcolor="#616d70" width="20"></td>
                    <th bgcolor="#616d70" class="qty" align="center"><?php _e('Quantité', THEME_TD); ?></th>
                    <td bgcolor="#616d70" width="20"></td>
                    <th bgcolor="#616d70" class="price" align="right"><?php _e('Prix HT', THEME_TD); ?>&nbsp;</th>
                </tr>
                </thead>

                <?php
                $items = $order->get_items();
                foreach ($items as $item_id => $item) :
                    $product = $item->get_product();
                    $sku = '';
                    $purchase_note = '';
                    $image = '';

                    if (! empty($product)) {
                        $sku = $product->get_sku();
                        $purchase_note = $product->get_purchase_note();
                        $image = $product->get_image('thumbnail');
                    }

                    ?>
                <tr>
                    <td class="pic">
                            <?php echo wp_kses_post(apply_filters('woocommerce_order_item_thumbnail', $image, $item)); ?>
                    </td>
                    <td></td>
                    <td class="desc">
                        <strong><?php echo $product->get_name(); ?><br></strong>
                            <?php
                            $p_options = get_field('product_options', $product->get_id());
                        if (! empty($p_options)) :
                            $view_options = implode(' / ', $p_options);
                            ?>
                        <span><?php echo $view_options; ?></span>
                        <?php endif; ?>
                    </td>
                    <td></td>
                    <td class="ref">
                            <?php echo wp_kses_post(' #'.$sku); ?>
                    </td>
                    <td></td>
                    <td class="pu" align="center"><?php echo $product->get_regular_price(); ?> &euro;</td>
                    <td></td>
                    <td class="qty" align="center">
                            <?php
                            $qty = $item->get_quantity();
                            $refunded_qty = $order->get_qty_refunded_for_item($item_id);

                            if ($refunded_qty) {
                                $qty_display = '<del>'.esc_html($qty).'</del> <ins>'.esc_html($qty - ($refunded_qty * -1)).'</ins>';
                            } else {
                                $qty_display = esc_html($qty);
                            }
                            echo wp_kses_post(apply_filters('woocommerce_email_order_item_quantity', $qty_display, $item));
                            ?>
                    </td>
                    <td></td>
                    <td class="price" align="right">
                            <?php echo wp_kses_post($order->get_formatted_line_subtotal($item)); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="11" height="1" bgcolor="#bcbcbc"></td>
                </tr>

                <?php endforeach; ?>
            </table>

            <table cellspacing="0" cellpadding="0" style="font-family: 'Arial'; font-size: 12px; line-height:normal">
                <tr>
                    <td height="10"></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="0" align="right"
                   style="font-family: 'Arial'; font-size: 12px; line-height:normal">
                <tr>
                    <td height="25"><?php _e('Mobilier'); ?> :</td>
                    <td class="width:20"></td>
                    <td><?php echo wp_kses_post($order->get_subtotal_to_display()); ?></td>
                </tr>
                <tr>
                    <td colspan="3" height="1" bgcolor="#bcbcbc"></td>
                </tr>
                <?php if (!empty($order->get_fees()) && is_array($order->get_fees())) : ?>
                    <?php foreach ($order->get_fees() as $fee) : ?>
                <tr>
                    <td height="25"><?php echo wp_kses_post($fee->get_name()); ?> :</td>
                    <td class="width:20"></td>
                    <td><?php echo wp_kses_post($fee->get_total()); ?><?php echo get_woocommerce_currency_symbol() ?></td>
                </tr>
                <tr>
                    <td colspan="3" height="1" bgcolor="#bcbcbc"></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>

                <?php $coupons = $order->get_items('coupon'); ?>
                <?php if (! empty($coupons)) : ?>
                    <?php foreach ($coupons as $coupon) : ?>
                    <?php $coupon_amount = wc_format_decimal($coupon->get_discount()); ?>

                <tr>
                        <?php if ($coupon->get_code() == 'creditimmo') : ?>
                    <td height="25"><?php _e('Crédit mobilier', THEME_TD); ?> :</td>
                    <?php endif; ?>

                        <?php if ($coupon->get_code() == 'showroomdiscount') : ?>
                        <?php $salon_id = getPostIdBySlug($order->get_meta('slug_evenement')); ?>
                        <?php
                        $showroomDiscountTitle = __('Remise', THEME_TD);
                        if (!empty($salon_id)) {
                            $showroomDiscountTitle = get_field('discount_title', $salon_id);
                        }
                        ?>
                    <td height="25"><?php echo $showroomDiscountTitle ?> :</td>
                    <?php endif; ?>

                        <?php if ($coupon->get_code() !== 'showroomdiscount' && $coupon->get_code() !== 'creditimmo') : ?>
                    <td height="25"><?php _e('Remise', THEME_TD); ?> :</td>
                    <?php endif; ?>

                    <td class="width:20"></td>
                    <td><?php echo wc_price($coupon_amount); ?></td>
                </tr>
                <tr>
                    <td colspan="3" height="1" bgcolor="#bcbcbc"></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>

                <tr>
                    <td height="25"><?php _e('Montant HT', THEME_TD); ?> :</td>
                    <td class="width:20"></td>
                    <?php $subtotal = $order->get_subtotal() + $order->get_total_fees() - $order->get_discount_total(); ?>
                    <td><?php echo wp_kses_post($subtotal); ?><?php echo get_woocommerce_currency_symbol() ?></td>
                </tr>
                <tr>
                    <td colspan="3" height="1" bgcolor="#bcbcbc"></td>
                </tr>
                <?php if (!empty($order->get_tax_totals())) : ?>
                    <?php $tax_totals = $order->get_tax_totals(); ?>
                <tr>
                        <?php foreach ($tax_totals as $tax_total) : ?>
                    <td height="25"><?php echo wp_kses_post($tax_total->label); ?> :</td>
                    <td class="width:20"></td>
                    <td><?php echo wp_kses_post($tax_total->formatted_amount); ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td colspan="3" height="1" bgcolor="#bcbcbc"></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td height="25"><?php _e('Total TTC', THEME_TD); ?> :</td>
                    <td class="width:20"></td>
                    <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                </tr>
                <tr>
                    <td colspan="3" height="1" bgcolor="#bcbcbc"></td>
                </tr>
                <tr>
                    <td height="25"><?php _e('Moyen de paiement', THEME_TD); ?> :</td>
                    <td class="width:20"></td>
                    <td><?php echo wp_kses_post($order->get_payment_method_title()); ?></td>
                </tr>
                <tr>
                    <td colspan="3" height="1" bgcolor="#bcbcbc"></td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>