<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.2
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_lost_password_form');
?>

<div class="col-sm-10 col-sm-offset-1">

    <div class="uk-grid-small" data-uk-grid>
        <!-- blocks -->

        <div class="block block-rte__default uk-width-1-1">
            <div class="block-content">
                <div class="block-body rte">
                    <p>&nbsp;<br><?php _e('Récuperation de mot de passe', THEME_TD) ?></p>
                </div><!-- .block-body -->
            </div><!-- .block-content -->
        </div><!-- .block-rte__default -->

        <div class="block block-form__details uk-width-1-1" uk-height-match=".block-form__details [class*=col-]">
            <form method="post" class="woocommerce-ResetPassword lost_reset_password block-content">

                <div class="border">
                    <p><?php echo apply_filters('woocommerce_lost_password_message',
                            esc_html__('Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.',
                                'woocommerce')); ?></p><?php // @codingStandardsIgnoreLine ?>
    
                    <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                        <label for="user_login"><?php esc_html_e('Username or email', 'woocommerce'); ?></label>
                        <input class="form-control woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login"
                               autocomplete="username"/>
                    </p>
    
                    <div class="clear"></div>
    
                    <?php do_action('woocommerce_lostpassword_form'); ?>
    
                    <p class="woocommerce-form-row form-row">
                        <input type="hidden" name="wc_reset_password" value="true"/>
                        <button type="submit" class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12"
                                value="<?php esc_attr_e('Reset password', 'woocommerce'); ?>"><?php esc_html_e('Reset password', 'woocommerce'); ?></button>
                    </p>
    
                    <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>
                </div>

            </form>
        </div>

    </div>

</div><!-- .col -->
<?php
do_action('woocommerce_after_lost_password_form');
