<div id="modal-salon-change" class="no-close" data-uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <div class="block block-event__modal">
            <form class="block-content" action="#">
                <div class="block-header"><?php _e('Changement de salon', THEME_TD) ?></div><!-- .block-header -->
                <div class="block-body">
                    <div class="summary">
                        <p><?php _e('Vous êtes sur le point de changer de salon. Tous les produits dans votre panier vont être supprimés. Voulez-vous continuer ?',
                                THEME_TD) ?></p>
                    </div>
                </div><!-- .block-body -->
                <div class="block-footer">
                    <button onclick="UIkit.modal('#modal-salon-change').hide()" class="btn btn-1 btn-w_a uk-modal-close">
                        <span><?php _e('Annuler', THEME_TD) ?></span>
                    </button>
                    <a href="" class="btn btn-2 btn-w_a salon-next-btn">
                        <span><?php _e('Continuer', THEME_TD) ?></span>
                    </a>
                </div><!-- .block-footer -->
            </form><!-- .block-content -->
        </div><!-- .block-event__modal -->

    </div>
</div><!-- #modal-event -->