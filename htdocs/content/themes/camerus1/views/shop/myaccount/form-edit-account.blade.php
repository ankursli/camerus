<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_edit_account_form'); ?>

<div class="col-lg-6 col-md-6 col-sm-8">

    <div class="uk-grid-small" data-uk-grid>
        <!-- blocks -->

        <div class="block block-rte__default uk-width-1-1">
            <div class="block-content">
                <div class="block-body rte">
                    <p>&nbsp;<br><?php _e('Veuillez trouver ci-dessous les détails de votre compte', THEME_TD); ?></p>
                </div><!-- .block-body -->
            </div><!-- .block-content -->
        </div><!-- .block-rte__default -->

        <div class="block block-form__details uk-width-1-1" uk-height-match=".block-form__details [class*=col-]">
            <form class="block-content form" action=""
                  method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?> >
                <div class="block-body">
                    <div class="border">
                        <?php do_action('woocommerce_edit_account_form_start'); ?>
                        <div class="row">
                            <div class="col-sm-6 col-xs-7">
                                <label class="label" for="billing_company"><?php _e('Nom de la société', THEME_TD) ?></label>
                                <input class="form-control" type="text" name="billing_company"
                                       id="billing_company" placeholder="" value="<?php echo get_user_meta($user->ID, 'billing_company', true)?>"
                                       autocomplete="organization">
                            </div><!-- .col -->
                            <div class="col-sm-7 col-xs-12">
                                <label class="label" for="billing_address_1"><?php _e('Adresse complète', THEME_TD) ?></label>
                                <input class="form-control" type="text" name="billing_address_1"
                                       id="billing_address_1" placeholder="" value="<?php echo get_user_meta($user->ID, 'billing_address_1', true)?>">
                            </div><!-- .col -->
                            <div class="col-sm-3 col-xs-12">
                                <label class="label" for="billing_city"><?php _e('Ville', THEME_TD) ?></label>
                                <input class="form-control" type="text" name="billing_city"
                                       id="billing_city" placeholder="" value="<?php echo get_user_meta($user->ID, 'billing_city', true)?>">
                            </div><!-- .col -->
                            <div class="col-sm-2 col-xs-4">
                                <label class="label" for="billing_postcode"><?php _e('Code postal', THEME_TD) ?></label>
                                <input class="input-text form-control" type="text" name="billing_postcode"
                                       id="billing_postcode" placeholder="" value="<?php echo get_user_meta($user->ID, 'billing_postcode', true)?>">
                            </div><!-- .col -->

                            <div class="col-sm-2 col-xs-4">
                                <label class="label" for="billing_gender"><?php _e('Civilité', THEME_TD) ?></label>
                                <input id="billing_gender" name="billing_gender" type="text" class="form-control"
                                       value="<?php echo get_user_meta($user->ID, 'billing_genre', true)?>">
                            </div><!-- .col -->
                            <div class="col-sm-5 col-xs-8">
                                <label class="label"
                                       for="account_last_name"><?php esc_html_e('Last name', 'woocommerce'); ?>
                                    &nbsp;<span class="required">*</span></label>
                                <input type="text"
                                       class="input-text form-control woocommerce-Input woocommerce-Input--text input-text"
                                       name="account_last_name" id="account_last_name" autocomplete="family-name"
                                       value="<?php echo esc_attr($user->last_name); ?>"/>
                            </div><!-- .col -->
                            <div class="col-sm-5 col-xs-8">
                                <label class="label"
                                       for="account_first_name"><?php esc_html_e('First name', 'woocommerce'); ?>
                                    &nbsp;<span class="required">*</span></label>
                                <input type="text"
                                       class="input-text form-control woocommerce-Input woocommerce-Input--text input-text"
                                       name="account_first_name" id="account_first_name" autocomplete="given-name"
                                       value="<?php echo esc_attr($user->first_name); ?>"/>
                            </div><!-- .col -->

                            <div class="col-sm-7 col-xs-12">
                                <label class="label"
                                       for="account_email"><?php esc_html_e('Email address', 'woocommerce'); ?>
                                    &nbsp;<span class="required">*</span></label>
                                <input type="email"
                                       class="input-text form-control woocommerce-Input woocommerce-Input--email input-text"
                                       name="account_email" id="account_email" autocomplete="email"
                                       value="<?php echo esc_attr($user->user_email); ?>"/>
                            </div><!-- .col -->

                            <div class="col-sm-5 col-xs-8">
                                <label class="label" for="billing_phone"><?php _e('Téléphone', THEME_TD) ?></label>
                                <input class="form-control" type="text" name="billing_phone"
                                       id="billing_phone" placeholder="" value="<?php echo get_user_meta($user->ID, 'billing_phone', true)?>">
                            </div><!-- .col -->

                            <div class="col-sm-4 col-xs-7">
                                <label class="label" for="billing_eu_vat_number"><?php _e('N°TVA', THEME_TD) ?></label>
                                <input class="form-control" type="text" name="billing_eu_vat_number"
                                       id="billing_eu_vat_number" placeholder="ex: FR 54 388 289 365"
                                       value="<?php echo get_user_meta($user->ID, 'billing_eu_vat_number', true)?>">
                            </div><!-- .col -->
                            <div class="col-sm-5 col-xs-8 hide">
                                <label class="label"
                                       for="account_display_name"><?php esc_html_e('Display name', 'woocommerce'); ?>
                                    &nbsp;<span class="required">*</span></label>
                                <input type="text"
                                       class="input-text form-control woocommerce-Input woocommerce-Input--text input-text"
                                       name="account_display_name" id="account_display_name"
                                       value="<?php echo esc_attr($user->display_name); ?>"/>
                                <span><em><?php esc_html_e('This will be how your name will be displayed in the account section and in reviews',
                                            'woocommerce'); ?></em></span>
                            </div><!-- .col -->

                            <?php do_action('woocommerce_edit_account_form'); ?>

                            <div class="col-sm-12 col-xs-12">
                                <h4><?php esc_html_e('Password change', 'woocommerce'); ?></h4>
                            </div>

                            <div class="col-sm-8 col-xs-8">
                                <label class="label"
                                       for="password_current"><?php esc_html_e('Current password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
                                <input type="password"
                                       class="input-text form-control woocommerce-Input woocommerce-Input--password input-text"
                                       name="password_current" id="password_current" autocomplete="off"/>
                            </div><!-- .col -->
                            <div class="col-sm-8 col-xs-8">
                                <label class="label"
                                       for="password_1"><?php esc_html_e('New password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
                                <input type="password"
                                       class="input-text form-control woocommerce-Input woocommerce-Input--password input-text"
                                       name="password_1" id="password_1" autocomplete="off"/>
                            </div><!-- .col -->
                            <div class="col-sm-8 col-xs-8">
                                <label class="label"
                                       for="password_2"><?php esc_html_e('Confirm new password', 'woocommerce'); ?></label>
                                <input type="password"
                                       class="input-text form-control woocommerce-Input woocommerce-Input--password input-text"
                                       name="password_2" id="password_2" autocomplete="off"/>
                            </div><!-- .col -->

                            <div class="col-sm-5 col-sm-offset-4 col-xs-12">
                                <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
                                <button type="submit"
                                        class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12 "
                                        name="save_account_details"
                                        value="<?php esc_attr_e('Save', 'woocommerce'); ?>"><?php esc_html_e('Save', 'woocommerce'); ?></button>
                                <input type="hidden" name="action" value="save_account_details"/>

                            </div><!-- .col -->

                            <?php do_action('woocommerce_edit_account_form_end'); ?>
                        </div><!-- .row -->
                    </div>
                </div><!-- .block-body -->
            </form><!-- .block-content -->
        </div><!-- .block-form__details -->

    <?php do_action('woocommerce_after_edit_account_form'); ?>

    <!-- end: blocks -->

    </div>

</div><!-- .col -->
