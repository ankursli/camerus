<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version     3.6.0
 */

use Illuminate\Support\Facades\Input;
use Themosis\Support\Facades\Route;

if (! defined('ABSPATH')) {
    exit;
}

$q_link = get_permalink(wc_get_page_id('shop')).'?product_tag=';
$tags = get_terms([
    'taxonomy' => 'product_tag',
    'hide_empty' => 'false',
]);
$colors = get_terms([
    'taxonomy' => 'pa_color',
    'hide_empty' => 'false',
]);
$product_ids = '';
$salon_event = request()->get('salon-filter');
if (Route::currentRouteName() == 'showroom-template' || Route::currentRouteName() == 'styleroom-template') {
    if (ICL_LANGUAGE_CODE == 'fr') {
        $salon_event = request()->segment(2);
    } else {
        $salon_event = request()->segment(3);
    }
}
if (! empty($salon_event)) {
    $salon_id = getEventSalonId($salon_event);

    $is_active_style = get_field('is_style_active', $salon_id);
    if ($is_active_style && str_contains(url()->current(), '/styleroom/')) {
        $styles = get_field('styles', $salon_id);
        $urlParts = explode("/", url()->current());
        if (! empty($urlParts[5])) {
            $style_slug = $urlParts[5];
        }
        if (! empty($styles) && ! empty($style_slug)) {
            foreach ($styles as $key => $style) {
                if (! empty($style['style'])) {
                    $style_item = get_term($style['style'], 'salon_style');
                    if (! empty($style_item) && $style_item->slug === $style_slug && ! empty($style['style_products'])) {
                        foreach ($style['style_products'] as $style_product) {
                            $product_ids .= $style_product->ID.',';
                        }
                    }
                }
            }
        }
    } else {
        $salon_products = get_field('salon_products', $salon_id);
        if (! empty($salon_products) && is_array($salon_products)) {
            foreach ($salon_products as $salon_product) {
                $product_ids .= $salon_product->ID.',';
            }
        }
    }
}
$cat_slug = '';
$cat = get_queried_object();
if ($cat instanceof WP_Term && $cat->taxonomy == 'product_cat') {
    $cat_slug = $cat->slug;
}
$view_all = request()->get('view');
$load_more = false;
if (! empty($view_all) && $view_all == 'all') {
    $load_more = true;
}
?>

<div class="block block-product__filter uk-width-1-1">
    <div class="block-content">
        <form class="block-body cmrs-custom-filter" method="get">

            <input type="hidden" name="paged" value="1"/>
            <?php if ($load_more) : ?>
            <input type="hidden" id="page-load-more" name="page-load-more" data-load="0" value="1"/>
            <?php endif; ?>
            <input type="hidden" name="security"
                   value="<?php echo wp_create_nonce('custom-cmrs-form' . date('Y-m-d-H', time())) ?>">
            <input type="hidden" name="salon-filter" value="<?php echo $product_ids; ?>"/>
            <input type="hidden" name="category" value="<?php echo $cat_slug; ?>"/>

            <div class="form-group">
                <label for="product__filter-order"><?php _e('Trier par', THEME_TD) ?> </label>
                <select id="product__filter-order" name="orderby" class="select c-selector">
                    <?php foreach ($catalog_orderby_options as $id => $name) : ?>
                    <option value="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if (! empty($tags) && is_array($tags)) : ?>
            <div class="form-group">
                <label for="product__filter-filter"><?php _e('Filtrer par', THEME_TD) ?> </label>
                <div class="dropdown">
                    <button class="btn ui-selectmenu-button" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="ui-selectmenu-text"><?php _e('Défaut', THEME_TD) ?></span>
                        <span class="ui-selectmenu-icon icon icon-selectmenu-arrows ui-icon ui-icon-triangle-1-s"></span>
                    </button>
                    <div class="dropdown-menu ui-selectmenu-menu" aria-labelledby="dropdownMenuButton">
                        <ul class="ui-menu">
                            <li>
                                <label class="container-input">Défaut
                                    <input name="filter-default" class="default-check" type="checkbox">
                                    <span class="checkmark"></span></label>
                            </li>
                                <?php foreach ($tags as $tag) : ?>
                            <li>
                                <label class="container-input"><?php echo $tag->name; ?>
                                    <input name="product_tag[]" type="checkbox" class="c-checkbox"
                                           value="<?php echo $tag->slug; ?>">
                                    <span class="checkmark"></span>
                                </label>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="product__filter-color"><?php _e('Coloris ', THEME_TD) ?> </label>
                <select name="product_color" id="product__filter-color" class="select c-selector">
                    <option selected="selected" value=""><?php _e('Défaut', THEME_TD) ?></option>
                    <?php foreach ($colors as $color) : ?>
                        <?php
                        $data_color = get_field('pa_color_picker', 'pa_color_'.$color->term_id);
                        if (empty($data_color)) {
                            $tag_icon = get_field('tag_icon', 'pa_color_'.$color->term_id);
                            if (! empty($tag_icon)) {
                                $data_color = $tag_icon['url'];
                            }
                        }
                        ?>
                    <option value="<?php echo $color->slug; ?>"
                            data-color="<?php echo $data_color; ?>"><?php echo $color->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </form>
    </div><!-- .block-content -->
</div><!-- .block-product__filter -->