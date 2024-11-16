<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
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

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
$reed_data = getReedDataInfo();
$reed_user_email = '';
$type_stand = '';
//$num_stand = '';
if (!empty($reed_data)) {
    $reed_user_email = $reed_data->Email;
//    $type_stand = $reed_data->TypeStand;
//    $num_stand = $reed_data->NumStand;
}

$login = true;
if (!empty($reed_data) && property_exists($reed_data, 'SurfaceStand') && !empty($reed_data->SurfaceStand)) {
    $login = false;
}
?>

<form name="checkout" method="post" class="form checkout woocommerce-checkout"
      action="<?php echo esc_url(wc_get_checkout_url()); ?>"
      enctype="multipart/form-data">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    {{-- ===== IDENTIFICATION & FORM FACTURATION ===--}}
    <div class="col-sm-6 stape-2">

        <div class="uk-grid-small uk-grid uk-grid-stack" data-uk-grid="">
            <!-- blocks -->
            <?php if($login) : ?>

            <?php if ( !is_user_logged_in() ) { ?>
            <div class="block block-rte__default uk-width-1-1 uk-first-column">
                <div class="block-content">
                    <div class="block-body rte">
                        <p><?php _e('Si vous possédez un compte', THEME_TD) ?>,
                            <strong><?php _e('connectez-vous', THEME_TD) ?>.</strong></p>
                    </div><!-- .block-body -->
                </div><!-- .block-content -->
            </div><!-- .block-rte__default -->

            <div class="block block-cart__login uk-width-1-1 uk-grid-margin uk-first-column">
                <h2 class="block-header"><?php _e('Se connecter', THEME_TD) ?></h2><!-- .block-header -->
                <div class="block-body">

                    <div class="uk-grid uk-grid-small uk-child-width-1-2@m uk-child-width-1-1@s">
                        <div class="form-row form-row-first">
                            <label for="username"><?php _e('E-mail', THEME_TD) ?><span class="required">*</span></label>
                            <input type="text" class="input-text form-control" name="username_custom"
                                   id="username_custom"
                                   placeholder="<?php _e('Saisissez votre email', THEME_TD) ?>"
                                   value="<?php echo $reed_user_email; ?>">
                            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline label-checkbox hidden">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox"
                                       name="rememberme" type="checkbox" id="rememberme"
                                       value="forever">
                                <span><?php _e('Se souvenir de moi', THEME_TD) ?></span>
                            </label>
                        </div>
                        <div class="form-row form-row-last">
                            <label for="password"><?php _e('Mot de passe', THEME_TD) ?><span
                                        class="required">*</span></label>
                            <input class="input-text form-control" type="password" name="password_custom"
                                   id="password_custom"
                                   placeholder="<?php _e('Saisissez votre mot de passe', THEME_TD) ?>">
                            <p class="lost_password">
                                <a href="<?php echo wp_lostpassword_url(); ?>"
                                   title="<?php _e('mot de passe oublié ?', THEME_TD) ?>"><?php _e('mot de passe oublié ?', THEME_TD) ?></a>
                            </p>
                        </div>
                    </div><!-- .uk-grid -->


                </div><!-- .block-body -->
                <div class="block-footer">
                    <p class="form-row">
                        <button class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12" type="submit" name="login_custom"
                                value="Login"><span><?php _e('Se connecter',
                                    THEME_TD) ?></span></button>
                    </p>
                </div><!-- .block-footer -->
                <!-- .block-content -->
            </div><!-- .block-cart__login -->
            <div class="block block-divider__horizontal uk-width-1-1 uk-grid-margin uk-first-column">
                <div class="block-content">
                    <div class="block-body">
                        <span><?php _e('ou', THEME_TD) ?></span>
                    </div><!-- .block-body -->
                </div><!-- .block-content -->
            </div><!-- .block-divider__horizontal -->
            <?php }?>

            <?php endif; ?>

            <div class="block block-rte__default uk-width-1-1 uk-grid-margin uk-first-column">
                <div class="block-content">
                    <div class="block-body rte">
                        <?php
                        if ($login) {
                            echo '<strong>';
                            sprintf(_e('Veuillez remplir les champs ci-dessous', THEME_TD));
                            echo '</strong>';
                        } else {
                            echo '<p>';
                            if (!is_user_logged_in()) {
                                sprintf(_e('Si vous n\'avez pas encore de compte ', THEME_TD));
                                echo '<strong>';
                                sprintf(_e('veuillez remplir les champs ci-dessous', THEME_TD));
                                echo '</strong>';
                            } else {
                                sprintf(_e('Remplir ou vérifier ', THEME_TD));
                                echo '<strong>';
                                sprintf(_e('les champs ci-dessous', THEME_TD));
                                echo '</strong>';
                            }
                            echo '</p>';
                        }
                        ?>
                    </div><!-- .block-body -->
                </div><!-- .block-content -->
            </div><!-- .block-rte__default -->

            <div class="block block-form__details uk-width-1-1 uk-grid-margin uk-first-column"
                 uk-height-match=".block-form__details [class*=col-]">
                <div class="block-content form">
                    <?php
                    $classToHide = 'hide';
                    if (is_user_logged_in()) {
                        $classToHide = '';
                    }
                    ?>
                    <h2 class="block-header hide">
                        <?php echo __('S’inscrire', THEME_TD); ?>
                    </h2>
                    <div class="block-body">
                        <div class="border">
                            <div class="row">
                                <?php if ( $checkout->get_checkout_fields() ) : ?>

                                <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                                <div class="col2-set" id="customer_details">
                                    <div class="">
                                        <?php do_action('woocommerce_checkout_billing'); ?>
                                    </div>

                                    <div class="hide">
                                        <?php do_action('woocommerce_checkout_shipping'); ?>
                                    </div>
                                </div>

                                <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                                <?php endif; ?>
                            </div><!-- .row -->
                        </div>
                    </div>
                    <div class="block-footer row">
                        <div class="col-sm-6 col-xs-12">
                            <a href="<?php echo wc_get_cart_url() ?>"
                               class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12 nav-process" data-process="1">
                                <span><?php _e('Précédent', THEME_TD) ?></span>
                            </a>
                        </div><!-- .col -->
                        <div class="col-sm-6 col-xs-12">
                            <?php if(isEventSalonSession()) : ?>
                            <a href="javascript:void(0)" class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12" id="load-2-3"
                               style="max-width: 250px;">
                                <span><?php _e('POURSUIVRE MA demande de devis', THEME_TD) ?></span>
                            </a>
                            <?php else : ?>
                            <a href="javascript:void(0)" class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12" id="load-2-3">
                                <span><?php _e('POURSUIVRE MA COMMANDE', THEME_TD) ?></span>
                            </a>
                            <?php endif; ?>
                        </div><!-- .col -->
                    </div><!-- .block-footer -->
                </div><!-- .block-content -->
            </div><!-- .block-form__details -->

            <div class="block block-spacer uk-width-1-1 uk hidden-xs uk-grid-margin uk-first-column">
                <div class="block-content">
                    <div class="block-body"></div><!-- .block-body -->
                </div><!-- .block-content -->
            </div><!-- .block-spacer -->

            <!-- end: blocks -->
        </div>
    </div>
    {{-- ==== IDENTIFICATION & FORM FACTURATION ===--}}

    {{-- ==== INFORMATION MANIFESTATION ======--}}
    <div class="col-sm-6 stape-3" style="display: none;">
        <div class="uk-grid-small uk-grid uk-grid-stack" data-uk-grid="">
            <!-- blocks -->

            <div class="block block-rte__default uk-width-1-1 uk-first-column">
                <div class="block-content">
                    <div class="block-body rte">
                        <p><?php _e('Veuillez renseigner les informations utiles sur l’évenement', THEME_TD) ?>.</p>
                    </div><!-- .block-body -->
                </div><!-- .block-content -->
            </div><!-- .block-rte__default -->

            @include('shop.checkout.information-manifestation')

            <div class="block block-spacer uk-width-1-1 uk hidden-xs uk-grid-margin uk-first-column">
                <div class="block-content">
                    <div class="block-body"></div><!-- .block-body -->
                </div><!-- .block-content -->
            </div><!-- .block-spacer -->
            <!-- end: blocks -->
        </div>
    </div>
    {{-- === INFORMATION MANIFESTATION =======--}}

    <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

    <?php do_action('woocommerce_checkout_before_order_review');  ?>

    <div id="order_review" class="woocommerce-checkout-review-order">
        {{-- =========== REVU COMMANDE =============--}}
        <?php
        $classContent = '6';
        if ($current_salon) {
            $classContent = '4';
        }
        ?>
        <div class="col-sm-<?php echo $classContent; ?> stape-4-1" style="display: none;">
            <div class=""></div>
            <?php /************** REVIEW without PAYEMENT ****************/ ?>
            <div class="block block-cart__title hidden-xs">
                <div class="block-content">
                    <div class="block-body"
                         style="min-height: 22.7273px;"><?php esc_html_e('Récapitulatif de la commande', 'woocommerce'); ?></div>
                    <!-- .block-body -->
                </div><!-- .block-content -->
            </div>
            <?php
            do_action('woocommerce_checkout_order_review');
            ?>
            <div class="block block-wishlist__cta">
                <div class="block-body">

                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <a href="javascript:void(0)" class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12 nav-process"
                               data-process="3">
                                <span><?php _e('Précédent', THEME_TD) ?></span>
                            </a>
                        </div><!-- .col -->
                        <div class="col-sm-6 col-xs-12">

                            <a class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12"
                               href="<?php echo wc_get_cart_url(); ?>"><span><?php _e('MODIFIER MON PANIER', THEME_TD) ?></span></a>
                            {{--<a href="javascript:void(0)" id="load-4-5" class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u"><span>VALIDER</span></a> --}}
                        </div><!-- .col -->

                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-2 stape-4-2" style="display: none;">
            @if($current_salon)
                <div class="block block-cart__title hidden-xs">
                    <div class="block-content">
                        <div class="block-body"
                             style="min-height: 22px;"><?php _e('Lieu de l’évènement', THEME_TD) ?></div>
                        <!-- .block-body -->
                    </div><!-- .block-content -->
                </div><!-- .block-cart__title -->

                <div class="block block-cart__events">
                    <div class="block-content">
                        <ul class="block-body">
                            <li>
                                <em class="name">
                                    <?php _e('Nom de l’évenement', THEME_TD) ?>
                                </em>
                                <strong class="value" id="event_name_detail">{{ $salon_title  }}</strong>
                            </li>
                            <li>
                                <em class="name">
                                    <?php _e('Lieu de l’évenement', THEME_TD) ?>
                                </em>
                                <strong class="value" id="event_lieu_detail">{{ $salon_ville }}
                                    - {{ $salon_lieu }} </strong>
                            </li>
                            <li>
                                <em class="name">
                                    <?php _e('Date de l’évenement', THEME_TD) ?>
                                </em>
                                <strong class="value" id="event_date_detail">{{ $salon_date }}</strong>
                            </li>
                            <li>
                                <em class="name">
                                    <?php _e('Nom du stand', THEME_TD) ?>
                                </em>
                                <strong class="value" id="event_stand_detail"><?php echo $type_stand; ?></strong>
                            </li>
                            <li>
                                <div class="uk-flex uk-child-width-1-2@m uk-child-width-1-4@s">
                                    <div>
                                        <em class="name">
                                            <?php _e('Hall', THEME_TD) ?>
                                        </em>
                                        <strong class="value" id="event_hall_detail"></strong>
                                    </div>
                                    <div class="uk-margin-small-left">
                                        <em class="name">
                                            <?php _e('Allée', THEME_TD) ?>
                                        </em>
                                        <strong class="value" id="event_wing_detail"></strong>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <em class="name">
                                    <?php _e('Numéro du stand', THEME_TD) ?>
                                </em>
                                <strong class="value" id="event_num_detail"><?php echo $num_stand; ?></strong>
                            </li>
                        </ul><!-- .block-body -->
                    </div><!-- .block-content -->
                </div><!-- .block-cart__events -->

                <div class="block block-wishlist__cta">
                    <div class="block-content">
                        <div class="block-header"></div><!-- .block-header -->
                        <div class="block-body">
                            <a class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12 nav-process"
                               title="3. <?php _e('Informations manifestations', THEME_TD) ?>" data-process="3"
                               href="#">
                                <span><?php _e('MODIFIER MON SALON', THEME_TD) ?></span>
                            </a>
                        </div><!-- .block-body -->
                        <div class="block-footer"></div><!-- .block-footer -->
                    </div><!-- .block-content -->
                </div><!-- .block-wishlist__cta -->
            @endif
        </div>
        {{-- =========== REVU COMMANDE =============-}}

        {{-- ============ PAYMENT ===============--}}
        <div class="col-sm-6 stape-5" style="display: none;">
            <div class="uk-grid-small" data-uk-grid>
                <div class="block block-rte__default uk-width-1-1">
                    <div class="block-content">
                        <div class="block-body rte">
                            <?php if(isEventSalonSession()) : ?>
                            <p><?php _e('Envoyer ma demande de devis', THEME_TD) ?></p>
                            <div><?php echo get_field('event_checkout_msg', 'option')?></div>
                            <?php else: ?>
                            <p><?php _e('Choisir une méthode de paiement', THEME_TD) ?></p>
                            <?php endif; ?>
                        </div><!-- .block-body -->
                    </div><!-- .block-content -->
                </div>

                <?php do_action('wc_show_payment'); ?>

                <div class="block block-wishlist__cta uk-width-1-1">
                    <div class="block-content">
                        <div class="block-header"></div><!-- .block-header -->
                        <div class="block-body">
                            <a href="javascript:void(0)" id="launch-order"
                               class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u">
                                <?php if(isEventSalonSession()) : ?>
                                <span><?php _e('Demander un devis', THEME_TD) ?></span>
                                <?php else: ?>
                                <span><?php _e('VALIDER LA COMMANDE', THEME_TD) ?></span>
                                <?php endif; ?>
                            </a>
                        </div><!-- .block-body -->
                        <div class="block-footer"></div><!-- .block-footer -->
                    </div><!-- .block-content -->
                </div>
            </div>
        </div>
        {{-- ============ PAYMENT ===============--}}
    </div>
    <?php do_action('woocommerce_checkout_after_order_review'); ?>

</form>

<input type="hidden" class="current_checkout_stape" id="current_checkout_stape" value="{{ $current_stape }}">
<input type="hidden" class="is_logged" id="is_logged" value="{{ $is_logged }}">

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>

