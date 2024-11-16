<?php
/**
 * Customer Reset Password email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-reset-password.php.
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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo SITE_MAIN_SYS_NAME; ?> : location de mobilier et accessoires pour salons et &eacute;v&eacute;nements.
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

<?php
/**
 * Mail content configuration
 */
$text_color = '#000';
$title_color = '#58585a';
$head_bg = '#ff560d';
$line_color = '#ff560d';
$att = get_field('app_header_logo', 'option');
if (empty($att)) {
    $att = get_template_directory_uri().'/dist/images/Logo_Cameru-450px.png';
}
?>

<table id="identifiant" cellpadding="0" cellspacing="0" align="center" border="0" summary="" style="font-family: 'Arial'; font-size: 14px; line-height:1.75">
    <tr>
        <td width="640">

            <table cellpadding="0" cellspacing="0" border="0" style="font-family:Arial; line-height:1.75; font-size: 14px">
                <tr>
                    <td>
                        <img src="<?php echo $att; ?>" width="280" height="64" class="" alt="" srcset="<?php echo $att; ?>}">
                    </td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>

            <table id="identifiant" width="100%" cellpadding="0" cellspacing="0" border="0" summary=""
                   style="font-family: 'Arial'; color:#ff560d ; line-height:1.75; font-size: 20px">
                <tr>
                    <td height="10" bgcolor="#616d70"></td>
                </tr>
                <tr>
                    <td height="20"></td>
                </tr>
                <tr>
                    <td><?php _e('Nouveau mot de passe', THEME_TD); ?></td>
                </tr>
                <tr>
                    <td height="20"></td>
                </tr>

            </table>

            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:Arial; line-height:1.75; font-size: 14px">
                <tr>
                    <td>
                        <?php /* translators: %s: Customer first name */ ?>
                        <p><?php printf(esc_html__('Hi %s,', 'woocommerce'), esc_html($user_login)); ?>
                        <?php /* translators: %s: Store name */ ?>
                        <p><?php printf(esc_html__('Someone has requested a new password for the following account on %s:', 'woocommerce'),
                                esc_html(wp_specialchars_decode(get_option('blogname'), ENT_QUOTES))); ?></p>
                        <?php /* translators: %s Customer username */ ?>
                        <p><?php printf(esc_html__('Username: %s', 'woocommerce'), esc_html($user_login)); ?></p>
                        <p><?php esc_html_e('If you didn\'t make this request, just ignore this email. If you\'d like to proceed:', 'woocommerce'); ?></p>
                        <p>
                            <a class="link" href="<?php echo esc_url(add_query_arg(array('key' => $reset_key, 'id' => $user_id),
                                wc_get_endpoint_url('lost-password', '', wc_get_page_permalink('myaccount')))); ?>"><?php // phpcs:ignore ?>
                                <?php esc_html_e('Click here to reset your password', 'woocommerce'); ?>
                            </a>
                        </p>

                        <?php
                        /**
                         * Show user-defined additional content - this is set in each email's settings.
                         */
                        if ($additional_content) {
                            echo wp_kses_post(wpautop(wptexturize($additional_content)));
                        }
                        ?>

                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <p><?php _e('Merci de votre confiance', THEME_TD); ?></p>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <p><b><?php echo sprintf("%s %s", __("L'équipe", THEME_TD), SITE_MAIN_SYS_NAME); ?></b></p>
                    </td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>

            <table cellpadding="0" align="center" cellspacing="0" border="0" style="font-family:Arial; line-height:1.75; font-size: 14px">
                <tr>
                    <td>
                        <img src="<?php echo $att; ?>" width="100" class="" alt="" srcset="<?php echo $att; ?>">
                    </td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>


        </td>
    </tr>
</table>


</body>
</html>