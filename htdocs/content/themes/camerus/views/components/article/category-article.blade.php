@if(!empty($category) && !empty($category->count))

    <?php $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1; ?>
    @query(['post_type' => 'post', 'posts_per_page' => 8, 'category__in' => [$category->term_id], 'paged' => $paged])
    <div class="block block-blog__preview uk-width-1-3@m">
        <div class="block-content">
            <div class="block-header">
                <time class="date" datetime="{{ Loop::date() }}">{!! Loop::date() !!}</time>
                <a href="{{ get_term_link(Loop::category()[0]->term_id) }}" class="tag"
                   title="{{ Loop::category()[0]->name }}">{!! Loop::category()[0]->name !!}</a>
            </div><!-- .block-header -->
            <a class="block-body" href="{{ Loop::link() }}" title="{{ Loop::title() }}">
                <div class="img-container img-middle">
                    {!! Loop::thumbnail('agenda-lightbox-thumbnail', ['width' => '285', 'height' => '218']) !!}
                </div>
                <h2 class="title">
                    {!! Loop::title() !!}
                </h2>
                <div class="summary">
                    <p>{!! Loop::excerpt() !!}</p>
                </div>
            </a><!-- .block-body -->
            <div class="block-footer">
                <div>
                    <a class="share" href="{{ Loop::link() }}" rel="nofollow" title="<?php _e('PARTAGER', THEME_TD) ?>"
                       rel="nofollow">
                        <?php _e('PARTAGER', THEME_TD) ?>
                        <i class="icon icon-blog__preview-share"></i>
                    </a>
                    @include('components.article.share-dropdown')

                </div>
                <a class="readmore" title="<?php _e('Ça m’intéresse', THEME_TD) ?>" href="{{ Loop::link() }}">
                    <?php _e('Ça m’intéresse', THEME_TD) ?>
                    <i class="icon icon-preview-arrow-right"></i>
                </a>
            </div><!-- .block-footer -->
        </div><!-- .block-content -->
    </div><!-- .block -->
    @endquery

@else

    <p style="text-align: center; font-size: 20px; color: #fff; width: 75%; padding: 20px;"></p><?php _e("Pas d'article dans ce rubrique", THEME_TD) ?>

@endif