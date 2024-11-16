<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $product, $post, $current_user;

?>

<div class="col-sm-8 mb-5">
    <div class="col-lg-12">
        <img style="height: auto; max-width: 500px; display: block; margin: 0 auto; padding-bottom: 50px;"
             src="<?php echo get_template_directory_uri() . '/dist/images/header-logo.svg' ?>" alt="">
    </div>
    <div class="col-sm-12">
        <h4><?php _e("Bienvenu sur votre Tableau de bord " . SITE_MAIN_SYS_NAME, THEME_TD) ?></h4>
        <p><?php
            /* translators: 1: user display name 2: logout url */
            printf(
                __('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce'),
                '<strong>' . esc_html($current_user->display_name) . '</strong>',
                esc_url(wc_logout_url(wc_get_page_permalink('myaccount')))
            );
            ?></p>

        <?php
        /**
         * My Account dashboard.
         *
         * @since 2.6.0
         */
        do_action('woocommerce_account_dashboard');

        /**
         * Deprecated woocommerce_before_my_account action.
         *
         * @deprecated 2.6.0
         */
        do_action('woocommerce_before_my_account');

        /**
         * Deprecated woocommerce_after_my_account action.
         *
         * @deprecated 2.6.0
         */
        do_action('woocommerce_after_my_account');

        /* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
        ?>
    </div>
</div>
