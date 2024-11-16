@if(!empty($post))
    @php
        $cat = get_the_category($post->ID);
        if(!empty($cat)) {
            $post_cat = $cat[0];
        }
    @endphp

    @if(!empty($preview))
        <div class="block block-blog__preview uk-width-{{ $size ?? '1' }}-3@m uk-first-column">
            <div class="block-content">
                <div class="block-header">
                    <time class="date"
                          datetime="{{ date('Y-m-d', strtotime($post->post_date)) }}">{!! date('d M Y', strtotime($post->post_date)) !!}</time>
                    @if(!empty($post_cat))
                        <a href="{{ get_term_link($post_cat->term_id) }}" class="tag"
                           title="{{ $post_cat->name }}">{!! $post_cat->name !!}</a>
                    @endif
                </div><!-- .block-header -->
                <a class="block-body" href="{{ get_permalink($post->ID) }}" title="{{ $post->post_title }}">
                    <div class="img-container img-middle">
                        {!! get_the_post_thumbnail($post->ID, 'agenda-lightbox-thumbnail', ['width' => '285', 'height' => '218']) !!}
                    </div>
                    <h2 class="title">
                        {!! $post->post_title !!}
                    </h2>
                    <div class="summary">
                        <p>{!! $post->post_excerpt !!}</p>
                    </div>
                </a><!-- .block-body -->
                <div class="block-footer">
                    <div>
                        <a class="share" href="#" rel="nofollow" title="<?php _e('PARTAGER', THEME_TD) ?>" rel="nofollow">
                            <?php _e('PARTAGER', THEME_TD) ?>
                            <i class="icon icon-blog__preview-share"></i>
                        </a>
                        @include('components.article.share-dropdown')

                    </div>
                    <a class="readmore" title="<?php _e('Ça m’intéresse', THEME_TD) ?>" href="{{ get_permalink($post->ID) }}">
                        <?php _e('Ça m’intéresse', THEME_TD) ?>
                        <i class="icon icon-preview-arrow-right"></i>
                    </a>
                </div><!-- .block-footer -->
            </div><!-- .block-content -->
        </div><!-- .block -->
    @else
        <div class="block block-articles__preview uk-width-{{ $size ?? '1' }}-3@m">
            <a class="block-content match-1" href="{{ get_permalink($post->ID) }}" title="{{ $post->post_title }}">
                <div class="block-header img-container img-middle">
                    {!! get_the_post_thumbnail($post->ID, 'agenda-lightbox-thumbnail', ['width' => '285', 'height' => '218']) !!}
                </div><!-- .block-header -->
                <div class="block-body">
                    <h2 class="title">
                        {!! $post->post_title !!}
                    </h2>
                    <div class="summary">
                        <p>{!! $post->post_excerpt !!}</p>
                    </div>
                </div><!-- .block-body -->
                <div class="block-footer">
            <span class="readmore" title="<?php _e('Ça m’intéresse', THEME_TD) ?>">
              <?php _e('Ça m’intéresse', THEME_TD) ?>
              <i class="icon icon-preview-arrow-right"></i>
            </span>
                </div><!-- .block-footer -->
            </a><!-- .block-content -->
        </div><!-- .block-articles__preview -->
    @endif
@endif