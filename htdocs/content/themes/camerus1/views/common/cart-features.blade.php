<div class="block block-cart__estimation">


    <div class="block-content">
        <div class="cart-collaterals ">
            <?php
            /**
             * Cart collaterals hook.
             *
             * @hooked woocommerce_cross_sell_display
             * @hooked woocommerce_cart_totals - 10
             */
            do_action('woocommerce_cart_collaterals');
            ?>
        </div>

        <?php do_action('woocommerce_after_cart'); ?>


        <div class="block-header">
            <i class="icon icon-cart__estimation-basket"></i>
        </div><!-- .block-header -->
        <ul class="block-body">
            <li>
                <em class="name">
                    Panier
                    <span class="number"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    <span class="test"></span>
                </em>
                <strong class="value"><?php echo WC()->cart->get_cart_total(); ?>€</strong>
            </li>
            <li>
                <em class="name">
                    Assurance
                </em>
                <strong class="value">€</strong>
            </li>
            <li>
                <em class="name">
                    TOTAL H.T
                </em>
                <strong class="value"><?php  ?>€</strong>
            </li>
            <li>
                <em class="name">
                    TVA
                </em>
                <strong class="value"><?php echo WC()->cart->get_cart_tax(); ?></strong>
            </li>
        </ul><!-- .block-body -->
        <div class="block-footer">
            <em class="name">
                TOTAL
                <span>TTC</span>
            </em>
            <strong class="value"><?php echo WC()->cart->get_cart_total() ?></strong>
        </div><!-- .block-footer -->
    </div><!-- .block-content -->
</div><!-- .block-cart__estimation -->