<?php

global $product;


$product = $the_product;
// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

$var_datas = getEventSalonCityDataInSession();
$json_data = product_get_json_data($product->get_ID());
?>

<?php if(!empty($var_datas['city']) && !empty($var_datas['price'])) :?>
<?php if(!is_product()) : ?>

<div <?php wc_product_class('block block-product__link uk-width-1-4@m uk-width-1-2@s', $product); ?>>

    <?php do_action('woocommerce_before_shop_loop_item') ?>

    <div class="block-content" data-product-info="<?php echo htmlspecialchars($json_data); ?>">
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

<?php else: ?>

<div class="card card-suggestions__product">
    <div class="card-content">
        <a class="card-header" href="<?php echo get_permalink($product->get_ID()) ?>" title="<?php _e('Réf', THEME_TD) ?>. <?php echo $product->get_sku() ?>">
            <span class="ref match-1"><?php _e('Réf', THEME_TD) ?>. <?php echo $product->get_sku() ?></span>
            <figure class="img-container img-middle">
                <?php do_action('woocommerce_before_shop_loop_item_title') ?>
            </figure>
        </a><!-- .card-header -->
        <div class="card-body">

            <?php do_action('woocommerce_shop_loop_item_title') ?>

            <div class="cart uk-flex uk-flex-middle uk-flex-between">

                <?php do_action('woocommerce_after_shop_loop_item_title') ?>

                <div class="num-spinner uk-flex">
                                  <span class="btn-container uk-flex uk-flex-column">
                                    <button onclick="ui.ns.increment('+',this)" type="button" class="btn"><i
                                                class="icon icon-product-arrow-up"></i></button>
                                    <button onclick="ui.ns.increment('-',this)" type="button" class="btn"><i
                                                class="icon icon-product-arrow-down"></i></button>
                                  </span>
                    <input type="number" name="product__characteristics-quantity"
                           value="1">
                </div>
            </div><!-- .cart -->
        </div><!-- .card-body -->
        <div class="card-footer">

            <?php do_action('woocommerce_after_shop_loop_item') ?>

        </div><!-- .card-footer -->
    </div><!-- .card-content -->
</div><!-- .card-suggestions__product -->

<?php endif; ?>
<?php endif; ?>
