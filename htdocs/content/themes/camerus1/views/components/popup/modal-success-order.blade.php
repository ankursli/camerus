<!-- add class *no-close* to make required pop in-->
<div id="modal-message" class="no-close uk-modal uk-open" data-uk-modal="" style="display: none;">
    <div class="uk-modal-dialog uk-modal-body">
        <a href="#" class="uk-modal-close" rel="nofollow"><i class="icon icon-modal-close"></i></a>
        <div class="block block-event__modal">
            <form class="block-content" action="#">
                <div class="block-header"><?php _e('Commande reçue', THEME_TD) ?></div><!-- .block-header -->
                <div class="block-body">
                    <div class="summary">
                        <p><?php echo get_field('app_order_success_message', 'option'); ?></p>
                    </div>
                </div><!-- .block-body -->
                <div class="block-footer">
                    <button class="btn btn-1 btn-w_a"><span><?php _e('Annuler', THEME_TD) ?></span></button>
                    <button class="btn btn-2 btn-w_a"><span><?php _e('Continuer', THEME_TD) ?></span></button>
                </div><!-- .block-footer -->
            </form><!-- .block-content -->
        </div><!-- .block-event__modal -->

    </div>
</div>