<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

use Illuminate\Support\Facades\Input;use Illuminate\Support\Facades\Request;if (!defined('ABSPATH')) {
    exit;
}

$total = isset($total) ? $total : wc_get_loop_prop('total_pages');
$current = isset($current) ? $current : wc_get_loop_prop('current_page');
$base = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
$format = isset($format) ? $format : '';

if ($total <= 1) {
    return;
}

$pagination = paginate_links(apply_filters('woocommerce_pagination_args', array( // WPCS: XSS ok.
                                                                                 'base'      => $base,
                                                                                 'format'    => $format,
                                                                                 'add_args'  => false,
                                                                                 'current'   => max(1, $current),
                                                                                 'total'     => $total,
                                                                                 'prev_text' => '&larr;',
                                                                                 'next_text' => '&rarr;',
                                                                                 'type'      => 'array',
                                                                                 'end_size'  => 3,
                                                                                 'mid_size'  => 3,
)));
$load_more = request()->get('view');
?>
<?php if(!empty($load_more) && $load_more == 'all') : ?>
<div id="product-ajax-load-more" data-load="0" class="block block-produ ct__link product-ajax-load-more uk-width-1-1"><div uk-spinner="" class="uk-icon uk-spinner uk-grid-margin"><svg width="50" height="50" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg" data-svg="spinner"><circle fill="none" stroke="#000" cx="15" cy="15" r="14"></circle></svg></div></div>
<?php else : ?>
<div class="block block-product__pagination uk-width-1-1">
    <div class="block-content">
        <div class="block-header"></div><!-- .block-header -->
        <?php if ( !empty($pagination) ) : ?>
        <ul class="block-body uk-pagination">
            <?php foreach ($pagination as $key => $page_link) : ?>
            <?php
            $c_class = '';
            if (strpos($page_link, 'current') !== false) {
                $c_class = 'uk-active';
            }
            ?>
            <li class="paginated_link <?php echo $c_class;  ?>">
                <?php echo $page_link ?>
            </li>
            <?php endforeach; ?>

            <li class="all">
                <a href="<?php echo Request::url().'?view=all'; ?>" title="<?php _e('Tout afficher', THEME_TD); ?>">
                    <span><?php _e('Tout afficher', THEME_TD); ?></span>
                </a>
            </li>
        </ul>
        <?php endif; ?>

        <div class="block-footer"></div><!-- .block-footer -->
    </div><!-- .block-content -->
</div><!-- .block-product__pagination -->
<?php endif; ?>
