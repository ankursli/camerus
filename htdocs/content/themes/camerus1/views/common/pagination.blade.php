<?php
$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
$args = array(
    'posts_per_page' => 8,
    'post_type'      => 'post',
    'paged'          => $paged,
    'category__in'   => [$category->term_id],
);

$query_recettes = new WP_Query($args);

if (!$query_recettes->have_posts()) {
    return;
}

$big = 999999999;
$total = $query_recettes->max_num_pages ?? 1;
$current = get_query_var('paged') ?? 0;
$base = str_replace($big, '%#%', esc_url(get_pagenum_link($big)));
$format = $format ?? '?paged=%#%';

$pagination = paginate_links(array(
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
));

?>

<div class="block block-product__pagination dark uk-width-1-1">
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
        </ul>
        <?php endif; ?>

        <div class="block-footer"></div><!-- .block-footer -->
    </div><!-- .block-content -->
</div><!-- .block-product__pagination -->
