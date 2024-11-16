<?php

use App\Hooks\Salon;
use Illuminate\Support\Facades\Request;

$url_agenda = get_field('app_agenda_popup_url_catalogue', 'option');
?>
<div id="modal-event-warning" class="no-close" data-uk-modal data-backdrop="static" data-keyboard="false">

    <div class="uk-modal-dialog uk-modal-body">
        <a href="#" class="uk-modal-close" rel="nofollow"><i class="icon icon-modal-close"></i></a>
        <div data-uk-spinner="ratio: 2"></div>
        <div class="block block-event__modal">

            <div class="block-header" style="text-align: center"><?php _e('Choisissez votre événement', THEME_TD) ?></div>

            <div class="block-body form-container">
                @include('components.popup.modal-event-warning-content')
            </div>

            <div class="row">
                <div class="col-sm-12 align-items-center">
                    <a href="{{ $url_agenda }}"
                       style="display: flex; max-width: 290px; margin: 25px auto 0;width: 100%;"
                       target="_blank"
                       class="btn btn-bgc_1 btn-tt_u btn-c_w btn-mih_34 btn-fz_12">
                        <span><?php _e('Télécharger le catalogue', THEME_TD) ?></span>
                    </a>
                </div>
            </div>
        </div><!-- .block-event__modal -->

    </div>

</div><!-- #modal-event -->