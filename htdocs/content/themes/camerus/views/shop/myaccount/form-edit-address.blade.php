<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

$page_title = ('billing' === $load_address) ? __('Billing address', 'woocommerce') : __('Shipping address', 'woocommerce');

do_action('woocommerce_before_edit_account_address_form'); ?>

<?php if ( !$load_address ) : ?>
<?php wc_get_template('myaccount/my-address.php'); ?>
<?php else : ?>

<div class="col-lg-6 col-md-6 col-sm-8">

    <div class="uk-grid-small" data-uk-grid>
        <!-- blocks -->

        <div class="block block-rte__default uk-width-1-1">
            <div class="block-content">
                <div class="block-body rte">
                    <p><?php _e('Veuillez trouver ci-dessous les détails de votre compte', THEME_TD); ?></p>
                </div><!-- .block-body -->
            </div><!-- .block-content -->
        </div><!-- .block-rte__default -->

        <div class="block block-form__details uk-width-1-1" uk-height-match=".block-form__details [class*=col-]">
            <form class="block-content form" method="post">
                <div class="block-body">
                    <h2 class="title"><?php echo apply_filters('woocommerce_my_account_edit_address_title', $page_title, $load_address); ?><?php // @codingStandardsIgnoreLine ?></h2>
                    <div class="border">
                        <div class="row">


                            <?php foreach ($address as $key => $field) : ?>
                            <?php
                            $field['input_class'][] = 'input-text form-control';
                            $field['label_class'][] = 'label';
                            ?>
                            <?php //woocommerce_form_field($key, $field, wc_get_post_data_by_key($key, $field['value'])); ?>
                            <?php endforeach; ?>


                            <?php do_action("woocommerce_before_edit_address_form_{$load_address}"); ?>

                            <?php if(isset($address['billing_company'])): ?>
                            <div class="col-sm-5 col-xs-7">
                                <?php woocommerce_form_field('billing_company', $address['billing_company'], wc_get_post_data_by_key('billing_company', $address['billing_company']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>

                            <?php if(isset($address['billing_country'])): ?>
                            <div class="col-sm-5 col-xs-12">
                                <?php woocommerce_form_field('billing_country', $address['billing_country'], wc_get_post_data_by_key('billing_country', $address['billing_country']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>

                            <?php if(isset($address['billing_address_1'])): ?>
                            <div class="col-sm-9 col-xs-12">
                                <?php woocommerce_form_field('billing_address_1', $address['billing_address_1'], wc_get_post_data_by_key('billing_address_1', $address['billing_address_1']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>

                            <?php if(isset($address['billing_address_2'])): ?>
                            <div class="col-sm-9 col-xs-12">
                                <?php woocommerce_form_field('billing_address_2', $address['billing_address_2'], wc_get_post_data_by_key('billing_address_2', $address['billing_address_2']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>
                            
                            <div class="clearfix"></div>

                            <?php if(isset($address['billing_postcode'])): ?>
                            <div class="col-sm-2 col-xs-4">
                                <?php woocommerce_form_field('billing_postcode', $address['billing_postcode'], wc_get_post_data_by_key('billing_postcode', $address['billing_postcode']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>

                            <div class="clearfix"></div>

                            <?php if(isset($address['billing_genre'])): ?>
                            <div class="col-sm-2 col-xs-4">
                                <?php woocommerce_form_field('billing_genre', $address['billing_genre'], wc_get_post_data_by_key('billing_genre', $address['billing_genre']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>

                            <?php if(isset($address['billing_last_name'])): ?>
                            <div class="col-sm-5 col-xs-8">
                                <?php woocommerce_form_field('billing_last_name', $address['billing_last_name'], wc_get_post_data_by_key('billing_last_name', $address['billing_last_name']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>

                            <?php if(isset($address['billing_first_name'])): ?>
                            <div class="col-sm-5 col-xs-8">
                                <?php woocommerce_form_field('billing_first_name', $address['billing_first_name'], wc_get_post_data_by_key('billing_first_name', $address['billing_first_name']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>

                            <?php if(isset($address['billing_email'])): ?>
                            <div class="col-sm-7 col-xs-12">
                                <?php woocommerce_form_field('billing_email', $address['billing_email'], wc_get_post_data_by_key('billing_email', $address['billing_email']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>

                            <?php if(isset($address['billing_phone'])): ?>
                            <div class="col-sm-5 col-xs-8">
                                <?php woocommerce_form_field('billing_phone', $address['billing_phone'], wc_get_post_data_by_key('billing_phone', $address['billing_phone']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>

                            <?php if(isset($address['billing_num_tva'])): ?>
                            <div class="col-sm-4 col-xs-7">
                                <?php woocommerce_form_field('billing_num_tva', $address['billing_num_tva'], wc_get_post_data_by_key('billing_num_tva', $address['billing_num_tva']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>

                            <?php if(isset($address['billing_dematerialized_invoice'])): ?>
                            <div class="col-sm-12 col-xs-7">
                                <?php woocommerce_form_field('billing_dematerialized_invoice', $address['billing_dematerialized_invoice'], wc_get_post_data_by_key('billing_dematerialized_invoice', $address['billing_dematerialized_invoice']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>

                            <?php if(isset($address['billing_accounting_email'])): ?>
                            <div class="col-sm-12 col-xs-7">
                                <?php woocommerce_form_field('billing_accounting_email', $address['billing_accounting_email'], wc_get_post_data_by_key('billing_accounting_email', $address['billing_accounting_email']['value'])); ?>
                            </div><!-- .col -->
                            <?php endif; ?>


                            <div class="col-sm-5 col-sm-offset-4 col-xs-12">
                                <button type="submit" class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12"
                                        name="save_address"
                                        value="<?php esc_attr_e('Save address', 'woocommerce'); ?>">
                                    <span><?php esc_html_e('Save address', 'woocommerce'); ?></span>
                                </button>
                                <?php wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce'); ?>
                                <input type="hidden" name="action" value="edit_address"/>
                            </div><!-- .col -->

                            <?php do_action("woocommerce_after_edit_address_form_{$load_address}"); ?>

                        </div><!-- .row -->
                    </div>
                </div><!-- .block-body -->
            </form><!-- .block-content -->
        </div><!-- .block-form__details -->

        <div class="block block-account__delete uk-width-1-1">
            <div class="block-content">
                <div class="block-body">
                    <button type="submit" class="btn btn-tt_u btn-c_w"><span>SUPPRIMER MON COMPTE</span></button>
                </div><!-- .block-body -->
            </div><!-- .block-content -->
        </div><!-- .block-section__block -->

        <!-- end: blocks -->

    </div>


</div><!-- .col -->


<?php endif; ?>

<?php do_action('woocommerce_after_edit_account_address_form'); ?>
