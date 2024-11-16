<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product;

$price_html = $product->get_price_html();
$city = $product->get_attribute(SLUG_PRODUCT_TAX_ATTRIBUT_CITY);

$var_datas = getEventSalonCityDataInSession();
if (!empty(getEventSalonCitySlugInSession()) && !empty($var_datas['price_html'])) {
    $price_html = $var_datas['price_html'];
    $city = $var_datas['city'];
}
?>

<?php if (!empty($price_html)) : ?>
<div class="price">
    <?php if(isEventSalonCitySlugDefaultInSession()) : ?>
    <span><?php  _e('A partir de'); ?>&nbsp;</span>
    <?php endif; ?>
    <strong><?php echo str_replace('&ndash;', '', $price_html); ?> </strong>
    <strong class="price_type">&nbsp;<?php _e('HT', THEME_TD); ?></strong>
</div>
<?php endif; ?>
