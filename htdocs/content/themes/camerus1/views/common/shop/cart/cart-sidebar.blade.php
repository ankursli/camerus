<aside class="col-sm-2 cmrs-cart-sidebar">

    <div class="block block-cart__title hidden-xs {{ is_checkout() ? '' : 'hide' }}">
        <div class="block-content">
            <div class="block-body"><?php _e('Mon panier', THEME_TD) ?></div><!-- .block-body -->
        </div><!-- .block-content -->
    </div><!-- .block-cart__title -->

    @include('common.shop.cart.cart-collaterals')

    <div class="block block-wishlist__cta {{ is_checkout() ? '' : 'hide' }}">
        <div class="block-content">
            <div class="block-header"></div><!-- .block-header -->
            <div class="block-body">
                <a href="javascript:void(0)" class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u" style="display: none"
                   data-href="" id="is_stape_4">
                    <?php if(isEventSalonSession()) : ?>
                    <span><?php _e('Poursuivre', THEME_TD) ?> <br> <?php _e('MA demande de devis', THEME_TD) ?></span>
                    <?php else : ?>
                    <span><?php _e('Poursuivre', THEME_TD) ?> <br> <?php _e('MA COMMANDE', THEME_TD) ?></span>
                    <?php endif; ?>
                </a>
                <a class="btn btn-edit" id="edit_cart" href="<?php echo wc_get_cart_url(); ?>">
                    <span><?php _e('MODIFIER MON PANIER', THEME_TD) ?></span>
                </a>
            </div><!-- .block-body -->
            <div class="block-footer"></div><!-- .block-footer -->
        </div><!-- .block-content -->
    </div><!-- .block-wishlist__cta -->

    @if(is_cart() && !isOrderFromReed())
        <?php $reduce_fee = abs(getReduceCreditAmountFee()); ?>
        <div class="block block-cart__mortgage">
            <form id="custom-credit-amount-reduce" class="block-content" action="">
                <div class="block-header">
                    <p><?php _e("Si vous bénéficiez d'un crédit mobilier, merci d'en indiquer le montant", THEME_TD); ?></p>
                </div><!-- .block-header -->
                <div class="block-body">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="number" class="input-text text-right form-control input-lg"
                                   name="credit-reduce-amount" id="credit-reduce-amount"
                                   placeholder="" value="<?php echo $reduce_fee; ?>" autocomplete="given-name"
                                   @if(!empty($reduce_fee)) readonly @endif>
                            <div class="input-group-addon">€</div>
                        </div>
                    </div>
                </div><!-- .block-body -->
                <div class="block-footer text-right">
                    @if(!empty($reduce_fee))
                        <button type="button" id="credit-reduce-amount-btn"
                                class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-delete">
                            <span><?php _e("Supprimer", THEME_TD); ?></span>
                        </button>
                    @else
                        <button type="button" id="credit-reduce-amount-btn"
                                class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u">
                            <span><?php _e("Ajouter", THEME_TD); ?></span>
                        </button>
                    @endif
                </div><!-- .block-footer -->
            </form><!-- .block-content -->
        </div><!-- .block-cart__mortgage -->
    @endif

    <div class="block block-wishlist__cta {{ is_cart() ? '' : 'hide' }}">
        <div class="block-content">
            <div class="block-header"></div><!-- .block-header -->
            <div class="block-body">
                <?php if(isEventSalonSession()) : ?>
                <a href="<?php  echo wc_get_checkout_url(); ?>"
                   class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-cart-to-checkout" data-href="">
                    <span><?php _e('Poursuivre MA demande de devis', THEME_TD) ?></span>
                </a>
                <?php else : ?>
                <a href="<?php  echo wc_get_checkout_url(); ?>"
                   class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-cart-to-checkout" data-href="">
                    <span><?php _e('Poursuivre MA COMMANDE', THEME_TD) ?></span>
                </a>
                <?php endif; ?>

            </div><!-- .block-body -->
            <div class="block-footer"></div><!-- .block-footer -->
        </div><!-- .block-content -->
    </div><!-- .block-wishlist__cta -->

</aside>