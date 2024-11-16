<aside class="col-sm-2 col-sm-offset-1">
    <!-- blocks -->

    <div class="uk-grid-small" data-uk-grid>

        <div class="block block-widget__cartnav uk-width-1-1">
            <div class="block-content">
                <?php
                $classNavIndex = '';
                //         $classNavIndex = ' class=nav-process';
                //          if(is_cart() ){
                //             $classNavIndex = '';
                //          }
                ?>
                <ul class="block-body nav-checkout-stape">
                    <li class="{{ is_cart()  ? 'active' : '' }}">
                        <a href="{{ wc_get_cart_url() }}" title="1. <?php _e('Mon panier', THEME_TD) ?>"
                           data-process="1" {{ $classNavIndex }}>1. <?php _e('Mon panier', THEME_TD) ?></a>
                    </li>
                    <li class="{{ is_checkout()  ? 'active' : '' }}">
                        <a href="#" title="2. <?php _e(' S’identifier', THEME_TD) ?>" data-process="2" {{ $classNavIndex }}>
                            2.
                            <?php
                            $labelStape = _e('Détail facturation', THEME_TD);
                            if (!is_user_logged_in()) {
                                $labelStape = _e(' S’identifier', THEME_TD);
                            }
                            ?></a>
                    </li>
                    <li>
                        <a href="#" title="3. <?php _e('Informations manifestations', THEME_TD) ?>"
                           data-process="3" {{ $classNavIndex }}>3. <?php _e('Informations manifestations', THEME_TD) ?></a>
                    </li>
                    <li>
                        <a href="#" title="4. <?php _e('Récapitulatif de la commande', THEME_TD) ?>"
                           data-process="4" {{ $classNavIndex }}>4. <?php _e('Récapitulatif de la commande', THEME_TD) ?></a>
                    </li>
                    <li>
                        <?php if(isEventSalonSession()) : ?>
                        <a href="#" title="5. <?php _e('Devis', THEME_TD) ?>" data-process="5" {{ $classNavIndex }}>5. <?php _e('Devis', THEME_TD) ?></a>
                        <?php else : ?>
                        <a href="#" title="5. <?php _e('Paiement', THEME_TD) ?>" data-process="5" {{ $classNavIndex }}>5. <?php _e('Paiement', THEME_TD) ?></a>
                        <?php endif; ?>
                    </li>
                </ul><!-- .block-body -->
            </div><!-- .block-content -->
        </div><!-- .block-widget__cartnav -->
    </div>

    <!-- end: blocks -->
</aside>