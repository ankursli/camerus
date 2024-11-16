<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product;

$price_html = $product->get_price_html();
$price_title = __('Prix', THEME_TD) . ' : salon';
$var_datas = getEventSalonCityDataInSession();
if (!empty(getEventSalonCitySlugInSession()) && !empty($var_datas['price_html'])) {
    $price_html = $var_datas['price_html'];
    $city = $var_datas['city_slug'];
}
?>
<div class="block block-load-data">
    <div data-uk-spinner="ratio: 2"></div>
    <div class="block block-product__characteristics uk">
        <div class="cart block-content">
            <div class="block-body uk-grid uk-grid-small uk-flex-middle">
                <div class="uk-width-2-2 uk-flex uk-flex-middle">
                    <strong class="label"><?php _e('Prix HT', THEME_TD) ?></strong>
                    <div class="value">
                        <div data-uk-tooltip="title:<?php echo $price_title; ?>;pos:right"
                             class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price'));?>">
                            <?php if(isEventSalonCitySlugDefaultInSession()) : ?>
                            <span><?php  _e('A partir de'); ?>&nbsp;</span>
                            <?php endif; ?>
                            <strong><?php echo str_replace('&ndash;', '', $price_html); ?> </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>