<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

$salon_slug = getEventSalonSlugInSession();
do_action('woocommerce_before_cart'); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
    <?php do_action('woocommerce_before_cart_table'); ?>

    <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">

        <div>
            <?php do_action('woocommerce_before_cart_contents'); ?>

            <?php
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0
            && apply_filters(
                'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key
            ) ) {
            $product_permalink = apply_filters(
                'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key
            );
            ?>
            <div class="block block-productlist__item uk-width-1-1 uk <?php echo esc_attr(
                apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)
            ); ?>">
                <div class="block-content">
                    <div class="uk-grid uk-grid-small">

                        <div class="block-aside uk-width-1-3">
                            <?php
                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                            if (!$product_permalink) {
                                echo $thumbnail; // PHPCS: XSS ok.
                            } else {
                                printf('<a class="img-container img-middle" href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                            }
                            ?>
                        </div><!-- .block-header -->
                        <div class="block-body uk-width-2-3">

                            <div class="top">
                                <div class="left">
                                    <h2 class="product-name title"
                                        data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                                        <?php
                                        if (!$product_permalink) {
                                            echo wp_kses_post(
                                                apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key).'&nbsp;'
                                            );
                                        } else {
                                            echo wp_kses_post(
                                                apply_filters(
                                                    'woocommerce_cart_item_name',
                                                    sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()),
                                                    $cart_item, $cart_item_key
                                                )
                                            );
                                        }

                                        do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                        // Backorder notification.
                                        if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                            echo wp_kses_post(
                                                apply_filters(
                                                    'woocommerce_cart_item_backorder_notification',
                                                    '<p class="backorder_notification">'.esc_html__('Available on backorder', 'woocommerce').'</p>', $product_id
                                                )
                                            );
                                        }
                                        ?>
                                    </h2>
                                    <span class="ref">
                                      <?php
                                        $the_cat = '';
                                        $_cat = getPrimaryTaxTerm('product_cat', true, $_product->get_parent_id());
                                        if (!empty($_cat) && $_cat->slug !== 'uncategorized') {
                                            $the_cat = ' - '.$_cat->name;
                                        }
                                        ?>
                                        <?php _e('Réf', THEME_TD) ?>.  <?php echo $_product->get_sku().$the_cat; ?>
                                    </span>
                                </div>
                                <div class="right">
                                    <strong class="product-price product-subtotal price"
                                            data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>">
                                        <?php
                                        echo apply_filters(
                                            'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item,
                                            $cart_item_key
                                        ); // PHPCS: XSS ok.
                                        ?>
                                    </strong>
                                </div>
                            </div><!-- .block-body -->
                            <div class="bottom">
                                <div>

                                    <?php echo wc_get_formatted_cart_item_data($cart_item); ?>

                                    <div class="value">
                                        <div class="num-spinner uk-flex">
                                            <?php
                                            if ($_product->is_sold_individually()) {
                                                $product_quantity = sprintf('<input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                            } else {
                                                $product_quantity = woocommerce_quantity_input(
                                                    array(
                                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                                        'input_value'  => $cart_item['quantity'],
                                                        'max_value'    => $_product->get_max_purchase_quantity(),
                                                        'min_value'    => '0',
                                                        'product_name' => $_product->get_name(),
                                                    ), $_product, false
                                                );
                                            }

                                            echo apply_filters(
                                                'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item
                                            ); // PHPCS: XSS ok.
                                            ?>
                                        </div>
                                        <span class="place hide"><?php  echo $_product->get_attribute(SLUG_PRODUCT_TAX_ATTRIBUT_CITY) ?></span>
                                        <strong class="product-price price">
                                            <?php
                                            echo apply_filters(
                                                'woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key
                                            ); // PHPCS: XSS ok.
                                            ?>
                                        </strong>
                                        <strong class="price_type" style="color: #ff9149">&nbsp;<?php _e('HT', THEME_TD); ?></strong>
                                    </div>
                                </div>
                                <div>

                                    <?php  if ($_product->is_type('dotation')) : ?>
                                    <div class="cta-container">
                                        <?php
                                        $dotation_url = home_url('dotations');
                                        $dotation_url = add_query_arg(SLUG_EVENT_SALON_QUERY, $salon_slug, $dotation_url);
                                        echo '<a href="'.$dotation_url
                                            .'" class="btn btn-c_line btn-bdc_line btn-tt_u btn-remove" ><span class="visible-xs">x</span><span class="hidden-xs">'
                                            .__('Modifier', THEME_TD).'</span></a>';
                                        ?>
                                    </div>
                                    <?php else : ?>
                                    <div class="cta-container product-remove">
                                        <?php
                                        echo apply_filters(
                                            'woocommerce_cart_item_remove_link', sprintf(
                                            '<a href="%s" class="btn btn-c_line btn-bdc_line btn-tt_u btn-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><span class="visible-xs">x</span><span class="hidden-xs">'
                                            .__('Supprimer', THEME_TD).'</span></a>',
                                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                                            __('Remove this item', 'woocommerce'),
                                            esc_attr($product_id),
                                            esc_attr($_product->get_sku())
                                        ), $cart_item_key
                                        );
                                        ?>
                                    </div>
                                    <?php endif; ?>

                                </div>
                            </div><!-- .block-footer -->
                        </div><!-- .block-body -->
                    </div>
                </div><!-- .block-content -->
            </div><!-- .block-productlist__item -->
            <?php
            }
            }
            ?>

            <?php do_action('woocommerce_cart_contents'); ?>

            <div>
                <div class="actions">

                    <?php if ( wc_coupons_enabled() ) { ?>
                    <div class="coupon hide">
                        <label for="coupon_code"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label>
                        <input type="text"
                               name="coupon_code"
                               class="input-text"
                               id="coupon_code"
                               value=""
                               placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>"/>
                        <button type="submit" class="button" name="apply_coupon"
                                value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_attr_e('Apply coupon', 'woocommerce'); ?></button>
                        <?php do_action('woocommerce_cart_coupon'); ?>
                    </div>
                    <?php } ?>

                    <button type="submit" class="button btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u" name="update_cart"
                            value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>

                    <?php do_action('woocommerce_cart_actions'); ?>

                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                </div>
            </div>

            <?php do_action('woocommerce_after_cart_contents'); ?>
        </div>
    </div>
    <?php do_action('woocommerce_after_cart_table'); ?>
</form>
