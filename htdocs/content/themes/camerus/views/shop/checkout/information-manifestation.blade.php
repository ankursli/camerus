<?php
$readonly = '';
if (!empty($salon_slug)) {
    $readonly = 'readonly';
}
$user_company = '';
$reed = getReedDataInfo();
if (!empty($reed->RaisonSociale)) {
    $user_company = $reed->RaisonSociale;
} elseif (is_user_logged_in()) {
    $user_company = get_user_meta(get_current_user_id(), 'billing_company', true);
}
?>

<div class="block block-form__event uk-width-1-1 uk-grid-margin uk-first-column checkout-event-info"
     uk-height-match=".block-form__event [class*=col]">
    <div class="block-content">
        <div class="block-body">
            <h2 class="title"><?php _e('Informations sur l’évenement', THEME_TD) ?> </h2>
            <div class="border">
                <div class="row">
                    <input type="hidden" name="_event-type" id="cmrs-event-type-select"
                           value="<?php echo getEventSalonCitySlugInSession(); ?>">
                    <input type="hidden" name="_event-slug" id="form__event-slug" value="{{ $salon_slug  }}">
                    <div class="col-sm-5 col-xs-12">
                        <label class="label"
                               for="form__event-name"> <?php _e('Nom de l’évenement', THEME_TD) ?> </label>
                        <input class="form-control" type="text" name="_event-name" id="form__event-name" placeholder=""
                               value="{{ $salon_title  }}" {{ $salon_title ? $readonly : '' }}>
                    </div><!-- .col -->
                    <div class="col-sm-6 col-xs-12">
                        <label class="label" for="form__event-place"><?php _e('Lieu', THEME_TD) ?></label>
                        <input class="form-control" type="text" name="_event-place" id="form__event-place"
                               placeholder=""
                               value="{{ $salon_lieu }}" {{ $salon_lieu ? $readonly : '' }}>
                    </div><!-- .col -->
                    <div class="col-sm-4 col-xs-6">
                        <label class="label" for="form__event-date"><?php _e('Date de début', THEME_TD) ?></label>
                        <input class="form-control {{ $salon_date ? '' : 'cpm-flatpickr' }}" type="text"
                               name="_event-date" id="form__event-date" placeholder=""
                               value="{{ $salon_date }}" {{ $salon_date ? $readonly : '' }}>
                    </div><!-- .col -->
                    <div class="col-sm-4 col-xs-6">
                        <label class="label" for="form__event-end-date"><?php _e('Date de fin', THEME_TD) ?></label>
                        <input class="form-control {{ $salon_date ? '' : 'cpm-flatpickr' }}" type="text"
                               name="_event-end-date" id="form__event-end-date" placeholder=""
                               value="{{ $salon_end_date }}" {{ $salon_end_date ? $readonly : '' }}>
                    </div><!-- .col -->
                    <div class="col-sm-6 col-xs-6">
                        <label class="label" for="form__event-city"><?php _e('Ville', THEME_TD) ?></label>
                        <input class="form-control" type="text" name="_event-city" id="form__event-city" placeholder=""
                               value="{{ $salon_ville }}" {{ $salon_ville ? $readonly : '' }}>
                    </div><!-- .col -->
                </div><!-- .row -->
            </div>
            <h2 class="title"><?php _e('Emplacement du stand pour cet évenement', THEME_TD) ?></h2>
            <div class="border">
                <div class="row">
                    <div class="col-sm-5 col-xs-7">
                        <label class="label" for="form__event-stand"><?php _e('Nom du stand', THEME_TD) ?><abbr class="required" title="obligatoire">*</abbr></label>
                        <input class="form-control" type="text" name="_event-stand" id="form__event-stand"
                               placeholder=""
                               value="{{ $user_company }}">
                    </div><!-- .col -->
                    <div class="col-sm-3 col-xs-5">
                        <label class="label" for="form__event-hall"><?php _e('Hall', THEME_TD) ?></label>
                        <input class="form-control" type="text" name="_event-hall" id="form__event-hall" placeholder=""
                               value="{{ $hall }}">
                    </div><!-- .col -->
                    <div class="col-sm-3 col-xs-6">
                        <label class="label" for="form__event-wing"><?php _e('Allée', THEME_TD) ?></label>
                        <input class="form-control" type="text" name="_event-wing" id="form__event-wing" placeholder=""
                               value="{{ $allee }}">
                    </div><!-- .col -->
                    <div class="col-sm-3 col-xs-6">
                        <label class="label" for="form__event-number"><?php _e('Numéro de stand', THEME_TD) ?></label>
                        <input class="form-control" type="text" name="_event-number" id="form__event-number"
                               placeholder=""
                               value="{{ $num_stand }}">
                    </div><!-- .col -->
                </div><!-- .row -->
            </div>
        </div><!-- .block-body -->
        <div class="block-footer">
            <div class="col-sm-6 col-xs-12">
                <a href="javascript:void(0)" class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12 nav-process"
                   data-process="2">
                    <span><?php _e('Précédent', THEME_TD) ?></span>
                </a>
            </div><!-- .col -->
            <div class="col-sm-6 col-xs-12">
                {{-- <button type="submit" class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12"><span>VALIDER</span></button> --}}
                <a href="javascript:void(0)" id="load-3-4"
                   class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u"><span><?php _e('VALIDER', THEME_TD) ?></span></a>
            </div><!-- .col -->
        </div>
    </div>
</div><!-- .block-form__details -->
