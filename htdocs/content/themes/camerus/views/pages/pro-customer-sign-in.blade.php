@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')
    <main id="main">

        <div id="primary" class="section container-fluid">
            <div class="section-body inner">

                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">

                        <div class="block block-section__breadcrumb uk">
                            <div class="block-content">
                                <ul class="block-body uk-breadcrumb">
                                    <li><a href="{{ home_url() }}"
                                           title="<?php _e('Accueil', THEME_TD) ?>"><?php _e('Accueil', THEME_TD) ?></a>
                                    </li>
                                    <li><a href="#"
                                           title="<?php _e('Mon espace', THEME_TD) ?>"><?php _e('Espace Pro', THEME_TD) ?></a>
                                    </li>
                                    <li><span><?php _e('Inscription', THEME_TD) ?></span></li>
                                </ul><!-- .block-body -->
                            </div><!-- .block-content -->
                        </div><!-- .block-section__breadcrumb -->

                    </div><!-- .col -->
                </div><!-- .row -->

            </div><!-- .section-body -->
        </div><!-- #primary -->

        <div id="layout" class="layout container-fluid">
            <div class="layout-body inner">
                <div class="row">


                    <aside class="col-sm-2 col-sm-offset-1">
                        <!-- blocks -->


                        <!-- end: blocks -->
                    </aside>

                    <div class="col-lg-6 col-md-6 col-sm-8">

                        <div class="uk-grid-small" data-uk-grid>
                            <!-- blocks -->

                            <div class="block block-rte__default uk-width-1-1 uk">
                                <div class="block-content">
                                    <div class="block-body rte">
                                        <p>
                                            <strong><?php _e('Demande d\'accès à nos tarifs événements', THEME_TD); ?></strong>
                                        </p>
                                    </div><!-- .block-body -->
                                </div><!-- .block-content -->
                            </div><!-- .block-rte__default -->


                            <div class="block block-notifications">
                                <div class="block-content">
                                    <div class="block-body">
                                        @if(!empty($user_name))
                                            <div class="alert-success"><?php _e("Votre demande d'accès à nos tarifs événement à bien été envoyée. Vous recevrez vos accès par e-mail dès validation de votre demande.", THEME_TD) ?>
                                            </div>
                                        @endif
                                        @if($user_error)
                                            <div class="alert-danger"><?php _e("Une erreur s'est produit, veuillez vérifier vos informations. Merci",
                                                    THEME_TD) ?></div>
                                        @endif
                                        @if($user_recaptcha_error)
                                            <div class="alert-danger"><?php _e("Une erreur s'est produit, veuillez vérifier le captcha. Merci",
                                                    THEME_TD) ?></div>
                                        @endif
                                    </div><!-- .block-body -->
                                </div><!-- .block-content -->
                            </div><!-- .block-notifications -->


                            <div class="block block-form__details uk-width-1-1"
                                 uk-height-match=".block-form__details [class*=col]">
                                <form id="pro-customer-sign-in" class="block-content form" method="POST"
                                      action="{{ get_permalink(get_the_ID()) }}">
                                    @csrf
                                    <div class="block-body">
                                        <div class="border">
                                            <div class="row">
                                                <div class="col-sm-3 col-xs-4">
                                                    <label class="label"
                                                           for="contact__form-gender"><?php _e('Civilité', THEME_TD) ?>
                                                        *</label>
                                                    <select class="uk-select form-control" name="billing_gender"
                                                            id="contact__form-gender" required>
                                                        <option value="">&nbsp;</option>
                                                        <option value="Monsieur"
                                                                selected><?php _e('Monsieur', THEME_TD) ?></option>
                                                        <option value="Madame"><?php _e('Madame', THEME_TD) ?></option>
                                                    </select>
                                                </div><!-- .col -->
                                                <div class="clearfix"></div>
                                                <div class="col-sm-5 col-xs-6">
                                                    <label class="label"
                                                           for="form__details-lastname"><?php _e('Nom', THEME_TD) ?>
                                                        *</label>
                                                    <input type="text" class="input-text form-control"
                                                           name="billing_last_name" id="billing_last_name"
                                                           autocomplete="family-name" required>
                                                </div><!-- .col -->
                                                <div class="col-sm-5 col-xs-6">
                                                    <label class="label"
                                                           for="form__details-first-name"><?php _e('Prénom', THEME_TD) ?>
                                                        *</label>
                                                    <input type="text" class="input-text form-control"
                                                           name="billing_first_name" id="billing_first_name"
                                                           autocomplete="given-name" autofocus="autofocus" required>
                                                </div><!-- .col -->
                                                <div class="col-sm-5 col-xs-7">
                                                    <label class="label"
                                                           for="form__details-company"><?php _e('Nom de la société', THEME_TD) ?>
                                                        *</label>
                                                    <input class="form-control" type="text" class="input-text "
                                                           name="billing_company" id="billing_company"
                                                           autocomplete="organization" required>
                                                </div><!-- .col -->
                                                <div class="col-sm-9 col-xs-12">
                                                    <label class="label"
                                                           for="form__details-address"><?php _e('Adresse complète', THEME_TD) ?>
                                                        *</label>
                                                    <input class="form-control" type="text" class="input-text "
                                                           name="billing_address_1" id="billing_address_1"
                                                           autocomplete="address-line1"
                                                           placeholder="Ex: 26/28 rue Gay Lussac 95501 Gonesse- France"
                                                           required>
                                                </div><!-- .col -->
                                                <div class="col-sm-2 col-xs-4">
                                                    <label class="label"
                                                           for="form__details-zipcode"><?php _e('Code Postal', THEME_TD) ?>
                                                        *</label>
                                                    <input type="text" class="input-text form-control"
                                                           name="billing_postcode" id="billing_postcode"
                                                           autocomplete="postal-code" placeholder="Ex: 69003" required>
                                                </div><!-- .col -->
                                                <div class="col-sm-1 col-xs-6">
                                                </div><!-- .col -->
                                                <div class="col-sm-7 col-xs-12">
                                                    <label class="label"
                                                           for="form__details-email"><?php _e('Adresse mail', THEME_TD) ?>
                                                        *</label>
                                                    <input type="email" class="input-text form-control"
                                                           name="billing_email" id="billing_email"
                                                           autocomplete="email username" required>
                                                </div><!-- .col -->
                                                <div class="col-sm-5 col-xs-8">
                                                    <label class="label"
                                                           for="form__details-phone"><?php _e('Téléphone', THEME_TD) ?>
                                                        *</label>
                                                    <input id="form__details-phone" name="billing_phone" type="text"
                                                           class="form-control" required>
                                                </div><!-- .col -->
                                                <div class="col-sm-4 col-xs-7">
                                                    <label class="label"
                                                           for="form__details-tva"><?php _e('N°TVA', THEME_TD) ?>
                                                        *</label>
                                                    <input id="form__details-tva" type="text" name="billing_num_tva"
                                                           class="form-control"
                                                           placeholder="Ex: FR 54 388 289 365" required>
                                                </div><!-- .col -->

                                                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                                                <div class="col-sm-5 col-sm-offset-4 col-xs-12">
                                                    <button type="submit"
                                                            class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12">
                                                        <span><?php _e('ENVOYER', THEME_TD) ?></span>
                                                    </button>
                                                </div><!-- .col -->
                                            </div><!-- .row -->
                                        </div>
                                    </div><!-- .block-body -->
                                </form><!-- .block-content -->
                            </div><!-- .block-form__details -->

                            <!-- end: blocks -->

                        </div>


                    </div><!-- .col -->

                </div>
            </div><!-- .layout-body -->
        </div><!-- #layout -->

        @include('components.page.reinsurances')
        @include('components.page.footer-social')

    </main>

@endsection