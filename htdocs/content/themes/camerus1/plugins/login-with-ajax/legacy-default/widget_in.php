<?php
/*
 * This is the page users will see logged in. 
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/

$myaccount_page_url = '#';
$myaccount_page_id = get_option('woocommerce_myaccount_page_id');
if ($myaccount_page_id) {
    $myaccount_page_url = get_permalink($myaccount_page_id);
}
?>
<li class="account uk-active uk-log">
    <a href="#" title="Connexion" rel="nofollow">
        <i class="icon icon-topbar-account"></i>
    </a>
    <div data-uk-dropdown="boundary: .block-topbar__tools;boundary-align:true;pos:bottom-right">
        <ul class="uk-nav uk-dropdown-nav">
            <li class="uk-header">
                <?php if (isProCustomer()) : ?>
                    <span><?php _e('Mon espace client PRO', THEME_TD) ?></span>
                <?php else : ?>
                    <span><?php _e('Mon espace client', THEME_TD) ?></span>
                <?php endif; ?>
            </li>
            <li>
                <a href="<?php echo wc_get_account_endpoint_url('orders') ?>"
                   title="<?php _e('Mes commandes', THEME_TD) ?>"><?php _e('Mes commandes', THEME_TD) ?></a>
            </li>
            <li>
                <a href="<?php echo wc_get_account_endpoint_url('salons') ?>"
                   title="<?php _e('Mes salons', THEME_TD) ?>"><?php _e('Mes salons', THEME_TD) ?></a>
            </li>
            <li>
                <a href="<?php echo wc_get_account_endpoint_url('favoris') ?>"
                   title="<?php _e('Mes favoris', THEME_TD) ?>"><?php _e('Mes favoris', THEME_TD) ?></a>
            </li>
            <li>
                <a href="<?php echo wc_get_account_endpoint_url('edit-account'); ?>"
                   title="<?php _e('Mon profil', THEME_TD) ?>"><?php _e('Mon profil', THEME_TD) ?></a>
            </li>
            <?php if (isProCustomer() && !isEventSalonSession()) : ?>
                <li>
                    <a href="<?php echo get_permalink(wc_get_page_id('shop')) . '?reset_salon_slug=1&event_city=event'; ?>"
                       title="<?php _e('Accéder aux tarifs Event', THEME_TD) ?>"><?php _e('Accéder aux tarifs Event', THEME_TD) ?></a>
                </li>
            <?php endif; ?>

            <?php
            //Blog Admin
            if (current_user_can('list_users', THEME_TD)) {
                ?>
                <li class="uk-header"><span><?php _e('Espace manager', THEME_TD) ?></span></li>
                <li>
                    <a href="<?php echo get_admin_url(); ?>"><?php esc_html_e("Administration du site", 'login-with-ajax'); ?></a>
                </li>
                <?php
                //Admin URL
                if ($lwa_data['profile_link'] == '1') {
                    if (function_exists('bp_loggedin_user_link')) {
                        ?>
                        <li>
                            <a href="<?php bp_loggedin_user_link(); ?>"><?php esc_html_e('Profile', 'login-with-ajax') ?></a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="<?php echo trailingslashit(get_admin_url()); ?>profile.php"><?php esc_html_e('Profile', 'login-with-ajax') ?></a>
                        </li>
                        <?php
                    }
                }
                //Logout URL
                ?>
                <?php if (current_user_can('editor') || current_user_can('administrator')) : ?>
                    <li>
                        <a id="wp-edit"
                           href="<?php echo get_edit_post_link(get_the_ID()) ?>"><?php esc_html_e('Edit Page', THEME_TD) ?></a>
                    </li>
                <?php endif; ?>

                <?php
            }
            ?>
            <li>
                <a id="wp-logout"
                   href="<?php echo wp_logout_url(home_url()) ?>"><?php esc_html_e('Log Out', 'login-with-ajax') ?></a>
            </li>
        </ul>
    </div>
</li>