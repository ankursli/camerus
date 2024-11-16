@php
    $tax_args = [
        'taxonomy'   => 'category',
        'hide_empty' => false,
        'exclude'    => [1]
    ];
    $taxonomies = get_terms($tax_args);
    if(!isset($post_count) || empty($post_count)) {
      $taxonomies = get_terms($tax_args);
        $post_args = [
                'numberposts'      => -1,
                'order'            => 'DESC',
                'orderby'          => 'date',
                'suppress_filters' => false
            ];
        $posts = get_posts($post_args);
        wp_reset_query();
        $post_count = count($posts);
    }
@endphp
<div class="block block-inspirations__filter uk-width-1-1">
    <div class="block-content">
        <ul class="block-body">
            <li class="<?php if (is_page(ID_LIST_POST)) : echo 'active'; endif; ?>">
                <a href="{{ get_permalink(ID_LIST_POST) }}"
                   title="<?php _e('Tous', THEME_TD) ?> ({{ $post_count }})">
                    <span><?php _e('Tous', THEME_TD) ?> ({{ $post_count }})</span>
                </a>
            </li>
            @if(!empty($taxonomies) && is_array($taxonomies))
                @foreach($taxonomies as $tax)
                    <?php
                    $current_tax = get_queried_object();
                    $active = '';
                    if ($current_tax->term_id === $tax->term_id) {
                        $active = 'active';
                    }
                    ?>
                    <li class="{{ $active }}">
                        <a href="{{ get_term_link($tax->term_id) }}"
                           title="{{ $tax->name }} ({{ $tax->count }})">
                            <span>{!! $tax->name !!} ({{ $tax->count }})</span>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul><!-- .block-body -->
    </div><!-- .block-content -->
</div><!-- .block-widget__categories -->