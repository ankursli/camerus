<?php
global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

$var_datas = getEventSalonCityDataInSession();

?>

<?php
if(!is_product()) :
?>

<div <?php wc_product_class('block block-product__link uk-width-1-3@m uk-width-1-2@s', $product); ?>>

    <?php do_action('woocommerce_before_shop_loop_item') ?>

    <div class="block-content">
        <a class="block-header" href="<?php echo get_permalink($product->get_ID()) ?>" title="<?php _e('Réf', THEME_TD) ?>. <?php echo $product->get_sku(); ?>">
            <span class="ref match-1"><?php _e('Réf', THEME_TD) ?>. <?php echo $product->get_sku(); ?> -
                <span class="category"><?php echo getProductCategory($product->get_ID())->name ?></span>
            </span>
            <figure class="img-container img-middle">
                <?php do_action('woocommerce_before_shop_loop_item_title') ?>
            </figure>
        </a><!-- .block-header -->
        <div class="block-body">

            <?php do_action('woocommerce_shop_loop_item_title') ?>

            <div class="cart uk-flex uk-flex-middle uk-flex-between">

                <?php do_action('woocommerce_after_shop_loop_item_title') ?>

                <?php if (!is_product()) : ?>
                <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]') ?>
                <?php endif; ?>

            </div><!-- .cart -->
        </div><!-- .block-body -->
        <div class="block-footer">

            <div class="num-spinner uk-flex">
                            <span class="btn-container uk-flex uk-flex-column">
                              <button onclick="ui.ns.increment('+',this)" type="button" class="btn"><i
                                          class="icon icon-product-arrow-up"></i></button>
                              <button onclick="ui.ns.increment('-',this)" type="button" class="btn"><i
                                          class="icon icon-product-arrow-down"></i></button>
                            </span>
                <input type="number" name="product__characteristics-quantity" value="1">
            </div>

            <?php do_action('woocommerce_after_shop_loop_item') ?>

        </div><!-- .block-footer -->
    </div><!-- .block-content -->

</div>

<?php endif; ?>