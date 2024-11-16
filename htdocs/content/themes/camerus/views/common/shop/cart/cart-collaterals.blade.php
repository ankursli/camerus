<div class="block block-cart__estimation _collaterals">

  <div class="block-content">

    <div class="block-header">
      <i class="icon icon-cart__estimation-basket"></i>
    </div><!-- .block-header -->

    @if(is_cart())
          <?php do_action('woocommerce_before_cart_collaterals'); ?>
          <?php
          /**
           * Cart collaterals hook.
           * @hooked woocommerce_cross_sell_display
           * @hooked woocommerce_cart_totals - 10
           */
            do_action('woocommerce_cart_collaterals');
          ?>
          <?php do_action('woocommerce_after_cart'); ?>
    @endif

    @if(is_checkout())
        <?php
        do_action('woocommerce_before_cart_collaterals');
        do_action( 'woocommerce_cart_collaterals_checkout');
        do_action( 'woocommerce_cart_total_checkout');
        do_action('woocommerce_after_cart');
        ?>
    @endif

  </div><!-- .block-cart__estimation -->

</div><!-- .block-cart__estimation -->