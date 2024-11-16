<?php
$user_info = null;
$userid = get_current_user_id();
if (!empty($userid)) {
    $user_info = get_userdata($userid);
    $userloginname = $user_info->user_login ?: '';
    $nicename = $user_info->user_nicename ?: '';
    $email = $user_info->user_email ?: '';
    $last_name = $user_info->last_name ?: '';
    $first_name = $user_info->first_name ?: '';
    $phone = get_user_meta($userid, 'billing_phone', true);
    $company = get_user_meta($userid, 'billing_company', true);
}
?>

<div id="contact" class="section container-fluid" style="background-image: url({{ get_the_post_thumbnail_url(get_the_ID(), 'full') }});"
     data-uk-parallax="bgy: -200">
    <div class="section-body inner">
        <div class="row">


            <div class="col-sm-10 col-sm-offset-1">
                <!-- blocks -->

                <div class="uk-grid-small" data-uk-grid>

                    <div class="col-left uk-width-2-5@m uk-width-1-2@l">

                        <div class="block block-contact__text">
                            <div class="block-content">
                                <h1 class="block-header">{!! $title !!}</h1><!-- .block-header -->
                                <div class="block-body rte">
                                    <p>{!! $desc !!}</p>
                                </div><!-- .block-body -->
                                <div class="block-footer"></div><!-- .block-footer -->
                            </div><!-- .block-content -->
                        </div><!-- .block-contact__text -->

                    </div><!-- .col-left -->
                    <div class="col-right uk-width-3-5@m uk-width-1-2@l">

                        <div class="block block-contact__form">
                            <form id="contact__form" class="block-content form" action="#">
                                <input type="hidden" id="_wpnonce" name="_wpnonce"
                                       value="<?php echo wp_create_nonce('contact-form-security') ?>">
                                @csrf
                                <div class="block-body">
                                    <div class="border">
                                        <div class="row">
                                            <div class="col-sm-3 col-xs-4">
                                                <label class="label" for="contact__form-gender"><?php _e('Civilité', THEME_TD) ?>
                                                    *</label>
                                                <select class="uk-select form-control" name="contact__form-gender"
                                                        id="contact__form-gender" placeholder="<?php _e('Civilité', THEME_TD) ?>"
                                                        required>
                                                    <option value="">&nbsp;</option>
                                                    <option value="<?php _e('Monsieur', THEME_TD) ?>"><?php _e('Monsieur', THEME_TD) ?></option>
                                                    <option value="<?php _e('Madame', THEME_TD) ?>"><?php _e('Madame', THEME_TD) ?></option>
                                                </select>
                                            </div><!-- .col -->
                                            <div class="col-sm-4 col-xs-12">
                                                <label class="label" for="contact__form-lastname"><?php _e('Nom', THEME_TD) ?>
                                                    *</label>
                                                <input type="text" class="input-text form-control"
                                                       name="contact__form-lastname" id="contact__form-lastname"
                                                       placeholder="<?php _e('Nom', THEME_TD) ?>" autocomplete="family-name"
                                                       value="{{ $last_name ?? '' }}"
                                                       required>
                                            </div><!-- .col -->
                                            <div class="col-sm-5 col-xs-12">
                                                <label class="label" for="contact__form-firstname"><?php _e('Prénom', THEME_TD) ?>
                                                    *</label>
                                                <input type="text" class="input-text form-control"
                                                       name="contact__form-firstname" id="contact__form-firstname"
                                                       placeholder="<?php _e('Prénom', THEME_TD) ?>" autocomplete="given-name"
                                                       autofocus="autofocus"
                                                       value="{{ $first_name ?? '' }}" required>
                                            </div><!-- .col -->
                                            <div class="col-sm-7 col-xs-12">
                                                <label class="label"
                                                       for="contact__form-companyname"><?php _e('Nom de la société', THEME_TD) ?></label>
                                                <input type="text" class="input-text form-control"
                                                       name="contact__form-companyname" id="contact__form-companyname"
                                                       placeholder="<?php _e('Nom de la société', THEME_TD) ?>"
                                                       value="{{ $company ?? '' }}"
                                                       autocomplete="email username"
                                                       required>
                                            </div><!-- .col -->
                                            <div class="col-sm-5 col-xs-12">
                                                <label class="label"
                                                       for="contact__form-phone"><?php _e('Téléphone', THEME_TD) ?></label>
                                                <input id="contact__form-phone" name="contact__form-phone" type="tel"
                                                       class="form-control"
                                                       placeholder="<?php _e('Téléphone', THEME_TD) ?>"
                                                       value="{{ $phone ?? '' }}">
                                            </div><!-- .col -->
                                            <div class="col-xs-12">
                                                <label class="label"
                                                       for="contact__form-email"><?php _e('Adresse mail', THEME_TD) ?>*</label>
                                                <input type="email" class="input-text form-control"
                                                       name="contact__form-email" id="contact__form-email"
                                                       placeholder="<?php _e('Adresse mail', THEME_TD) ?>" value="{{ $email ?? '' }}"
                                                       autocomplete="email username" required>
                                            </div><!-- .col -->
                                            <div class="col-xs-12">
                                                <label class="label"
                                                       for="contact__form-message"><?php _e('Votre message', THEME_TD) ?>*</label>
                                                <textarea class="form-control" name="contact__form-message"
                                                          id="contact__form-message" cols="30" rows="6"
                                                          placeholder="<?php _e('Votre message', THEME_TD) ?>"
                                                          required></textarea>
                                            </div><!-- .col -->
                                            <div class="col-xs-12">
                                                <button id="contact__form_submit" type="submit"
                                                        class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12">
                                                    <span><?php _e('Envoyer', THEME_TD) ?></span>
                                                </button>
                                            </div><!-- .col -->
                                        </div><!-- .row -->
                                    </div>
                                </div><!-- .block-body -->
                                <div class="block-footer">
                                    <p class="msg-error text-center">
                                        * <?php _e('Veuillez remplir les champs obligatoires', THEME_TD) ?></p>
                                </div>
                            </form><!-- .block-content -->
                        </div><!-- .block-contact__form -->

                    </div><!-- .col-right -->

                </div>

                <!-- end: blocks -->
            </div><!-- .col -->

        </div><!-- .row -->
    </div><!-- .section-body -->
</div><!-- #layout -->