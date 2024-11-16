<div id="modal-category-zip-download" data-uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <a href="#" class="uk-modal-close btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-add pull-right" rel="nofollow"><i
                    class="icon icon-modal-close"></i></a>

        <div class="modal-title mb-4">
            <span class="big-title"><?php _e('Téléchargement de fichier 3D') ?></span>
            <span class="sub-title"><?php _e('Cliquer pour télécharger') ?></span>
        </div>
        <div id="category-zip-download" class="section container-fluid modal-category-zip-download-item">

            @include('components.popup.modal-category-zip-download-content')

        </div><!-- #product -->

        <div class="modal-footer">
            <a href="#" type="submit" class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-add uk-modal-close pull-right"
               rel="nofollow">
                <span><?php _e('Fermer', THEME_TD); ?></span>
            </a>
        </div>

    </div>
</div><!-- #modal-event -->