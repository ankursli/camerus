<?php
/**
 * Cart item data (when outputting non-flat)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-item-data.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see      https://docs.woocommerce.com/document/template-structure/
 * @package  WooCommerce/Templates
 * @version  2.4.0
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="variation">
    <?php foreach ( $item_data as $data ) : ?>
    <?php if(strtolower($data['key']) === 'color') : ?>
    <?php
    $term = get_term_by('slug', strtolower($data['value']), SLUG_PRODUCT_TAX_ATTRIBUT_COLOR);
    $color = '';
    if (!empty($term)) {
        $color = get_field('pa_color_picker', 'pa_color_'.$term->term_id);
    }
    ?>
    <?php if(!empty($color) ): ?>
    <div class="color">
        <span class="label"><?php _e('Couleur', THEME_TD) ?></span>
        <span class="sample" style="background: <?php echo $color; ?>"></span>
        <span class="name"><?php echo wp_kses_post($data['display']); ?></span>
    </div>
    <?php endif; ?>
    <?php else: ?>
    <dl class="variation hide">
        <dt class="<?php echo sanitize_html_class('variation-'.$data['key']); ?>"><?php echo wp_kses_post($data['key']); ?>:</dt>
        <dd class="<?php echo sanitize_html_class('variation-'.$data['key']); ?>"><?php echo wp_kses_post(wpautop($data['display'])); ?></dd>
    </dl>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
