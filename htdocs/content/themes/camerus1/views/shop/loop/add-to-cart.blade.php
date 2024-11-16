<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version     3.3.0
 */

use Illuminate\Support\Facades\Input;if (!defined('ABSPATH')) {
    exit;
}

global $product, $wp;

$current_request = str_replace('//', '/', $wp->request.'/');
$current_url = home_url($current_request);
$price_html = $product->get_price_html();
$city = $product->get_attribute(SLUG_PRODUCT_TAX_ATTRIBUT_CITY);
$quantity = esc_attr(isset($args['quantity']) ? $args['quantity'] : 1);
$color_slug = '';
$city_slug = '';
$variation_id = '';

$var_datas = getEventSalonCityDataInSession();
if (!empty(getEventSalonCitySlugInSession()) && !empty($var_datas['city'])) {
    $price_html = $var_datas['price_html'];
    $city = strtolower($var_datas['city']);
    $city_slug = $var_datas['city_slug'];
    $variation = productGetVariationByAttributeCity($product->get_id(), $city_slug);
    $variation_id = $variation['variation_id'];
    $color = $product->get_attributes('pa_color');
    if (!empty($color) && array_key_exists('pa_color', $color)) {
        $color_options = $color['pa_color']->get_options();
        if (!empty($color_options)) {
            $_color = get_term($color_options[0], 'pa_color');
            if (!empty($_color)) {
                $color_slug = $_color->slug;
            }
        }
    }
}
$add_to_cart_url = add_query_arg(array(
    'add-to-cart'        => $product->get_id(),
    'variation_id'       => $variation_id,
    'quantity'           => $quantity,
    'attribute_pa_city'  => $city_slug,
    'attribute_pa_color' => $color_slug,
    'salon-filter'       => request()->get('salon-filter'),
), esc_url($current_url));
?>

<?php if (!empty($city) && $product->is_type('variable') && !empty($variation_id)) : ?>

<a href="<?php echo esc_url($add_to_cart_url); ?>"
   class="<?php echo $args['class']; ?> add_to_cart_button custom-variation-type btn"
   title="<?php _e('Ajouter au panier', THEME_TD) ?>"
   data-quantity="<?php echo $quantity; ?>"
   data-product_id="<?php echo $product->get_id(); ?>"
   data-variation_id="<?php echo $variation_id; ?>"
   data-product_sku="<?php echo $args['attributes']['data-product_sku']; ?>"
   data-pa_city="<?php echo $city_slug; ?>"
   data-pa_color="<?php echo $color_slug; ?>"
   aria-label="<?php _e('Ajouter au panier', THEME_TD) ?>"
   rel="nofollow"
><?php _e('Ajouter au panier', THEME_TD) ?></a>

<?php else : ?>

<?php
    echo apply_filters('woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
        sprintf('<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
            esc_url($product->add_to_cart_url()),
            esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
            esc_attr(isset($args['class']) ? $args['class'].' btn' : 'button'),
            isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
            esc_html($product->add_to_cart_text())
        ),
        $product, $args);
    ?>

<?php endif; ?>