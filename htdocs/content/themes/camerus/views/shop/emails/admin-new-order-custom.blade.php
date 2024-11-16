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

<table id="identifiant" cellpadding="0" cellspacing="0" align="center" border="0" summary="" style="font-family: 'Arial'; font-size: 12px; line-height:normal">
    <tr>
        <td width="768">

            <table cellpadding="0" cellspacing="0" border="0" style="font-family:Arial; line-height:normal; font-size: 12px">
                <tr>
                    <td>
                        <img src="<?php echo get_template_directory_uri().'/dist/images/header-logo.svg' ?>" width="200" height="64" class="" alt=""
                             srcset="<?php echo get_template_directory_uri().'/dist/images/header-logo.svg' ?>">
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
                    <td><?php _e('Demande de devis événement de', THEME_TD); ?> : M <?php echo $user->user_firstname.' '.$user->user_lastname ?></td>
                </tr>
                <tr>
                    <td height="20"></td>
                </tr>

            </table>

            <table cellpadding="0" cellspacing="0" border="0" style="font-family:Arial; line-height:normal; font-size: 12px">
                <tr>
                    <td><strong><?php _e('SOCIÉTÉ', THEME_TD); ?> :</strong></td>
                </tr>
                @if(array_key_exists('company', $cart_customer->billing))
                    <tr>
                        <td><?php _e('Société', THEME_TD); ?> : {!! $cart_customer->billing['company'] !!}</td>
                    </tr>
                @endif
                @if(array_key_exists('eu_vat_number', $cart_customer->billing))
                    <tr>
                        <td><?php _e('TVA', THEME_TD); ?> : {!! $cart_customer->billing['eu_vat_number'] !!}</td>
                    </tr>
                @endif
                <tr>
                    <td><?php _e('Contact', THEME_TD); ?> :
                        M <?php echo $user->user_firstname.' '.$user->user_lastname; ?></td>
                </tr>
                @if(array_key_exists('phone', $cart_customer->billing))
                    <tr>
                        <td><?php _e('Tél', THEME_TD); ?> : {!! $cart_customer->billing['phone'] !!}</td>
                    </tr>
                @endif
                <tr>
                    <td><?php _e('Mail', THEME_TD); ?> : <?php echo $user->user_email; ?></td>
                </tr>

                @if(array_key_exists('accounting_email', $cart_customer->billing))
                    <tr>
                        <td><?php _e('Adresse mail comptabilité', THEME_TD); ?> : {!! $cart_customer->billing['accounting_email'] !!}</td>
                    </tr>
                @endif

                @if(array_key_exists('address_1', $cart_customer->billing))
                    <tr>
                        <td><?php _e('Adresse', THEME_TD); ?> : {!! $cart_customer->billing['address_1'] !!}</td>
                    </tr>
                @endif
                @if(array_key_exists('city', $cart_customer->billing))
                    <tr>
                        <td><?php _e('Ville', THEME_TD); ?> : {!! $cart_customer->billing['city'] !!}</td>
                    </tr>
                @endif
                @if(array_key_exists('postcode', $cart_customer->billing))
                    <tr>
                        <td><?php _e('Code Postal', THEME_TD); ?> : {!! $cart_customer->billing['postcode'] !!}</td>
                    </tr>
                @endif
                <tr>
                    <td height="10"></td>
                </tr>
            </table>

            <table width="100%" cellspacing="0" cellpadding="0" style="text-align: left; font-family: 'Arial'; font-size: 12px; line-height:normal">
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
                foreach ( $products as $product ) :
                $sku = '';
                $purchase_note = '';
                $image = '';

                if (!empty($product)) {
                    $sku = $product->get_sku();
                    $purchase_note = $product->get_purchase_note();
                    $image = $product->get_image('thumbnail');
                }

                ?>
                <tr>
                    <td class="pic">
                        {!! $image !!}
                    </td>
                    <td></td>
                    <td class="desc">
                        <strong><?php echo $product->get_name(); ?><br></strong>
                        <?php
                        $p_options = get_field('product_options', $product->get_id());
                        if (!empty($p_options)) :
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
                        {{ $product->quantity }}
                    </td>
                    <td></td>
                    <td class="price" align="right">
                        {{ $product->total.'€' }}
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
            <table cellspacing="0" cellpadding="0" align="right" style="font-family: 'Arial'; font-size: 12px; line-height:normal">

                <tr>
                    <td height="25"><?php _e('Mobilier', THEME_TD) ?></td>
                    <td class="width:20"></td>
                    <td>{!! $cart_subtotal.'€' !!}</td>
                </tr>
                <tr>
                    <td colspan="3" height="1" bgcolor="#bcbcbc"></td>
                </tr>

                <tr>
                    <td height="25"><?php _e('Assurance', THEME_TD) ?></td>
                    <td class="width:20"></td>
                    <td>{!! $cart_total_fee.'€' !!}</td>
                </tr>
                <tr>
                    <td colspan="3" height="1" bgcolor="#bcbcbc"></td>
                </tr>

                <tr>
                    <td height="25"><?php _e('TVA', THEME_TD) ?></td>
                    <td class="width:20"></td>
                    <td>{!! $cart_total_tax.'€' !!}</td>
                </tr>
                <tr>
                    <td colspan="3" height="1" bgcolor="#bcbcbc"></td>
                </tr>

                <tr>
                    <td height="25"><?php _e('Total', THEME_TD) ?></td>
                    <td class="width:20"></td>
                    <td>{!! $cart_total.'€' !!}</td>
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