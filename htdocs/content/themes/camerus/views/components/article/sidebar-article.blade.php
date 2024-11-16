@if(!empty($category))

    @query(['post_type' => 'post', 'posts_per_page' => 2, 'category__not_in' => [$category->term_id]])
    <div class="block block-blog__preview uk-width-2-3@m">
        <div class="block-content">
            <div class="block-header">
                <time class="date" datetime="{{ Loop::date() }}">{{ Loop::date() }}</time>
                <a href="{{ get_term_link(Loop::category()[0]->term_id) }}" class="tag"
                   title="{{ Loop::category()[0]->name }}">{{ Loop::category()[0]->name }}</a>
            </div><!-- .block-header -->
            <a class="block-body" href="{{ Loop::link() }}" title="{{ Loop::title() }}">
                <div class="img-container img-middle">
                    {!! Loop::thumbnail('full', ['width' => '285', 'height' => '218']) !!}
                </div>
                <div class="title">
                    {!! Loop::title() !!}
                </div>
                <div class="summary">
                    <p>{!! Loop::excerpt() !!}</p>
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
                <a class="readmore" title="<?php _e('Ça m’intéresse', THEME_TD) ?>" href="{{ Loop::link() }}">
					<?php _e('Ça m’intéresse', THEME_TD) ?>
                    <i class="icon icon-preview-arrow-right"></i>
                </a>
            </div><!-- .block-footer -->
        </div><!-- .block-content -->
    </div><!-- .block -->
    @endquery

@else

    @query(['post_type' => 'post', 'posts_per_page' => 2])
    <div class="block block-blog__preview uk-width-2-3@m">
        <div class="block-content">
            <div class="block-header">
                <time class="date" datetime="{{ Loop::date() }}">{{ Loop::date() }}</time>
                <a href="{{ get_term_link(Loop::category()[0]->term_id) }}" class="tag"
                   title="{{ Loop::category()[0]->name }}">{{ Loop::category()[0]->name }}</a>
            </div><!-- .block-header -->
            <a class="block-body" href="{{ Loop::link() }}" title="{{ Loop::title() }}">
                <div class="img-container img-middle">
                    {!! Loop::thumbnail('full', ['width' => '285', 'height' => '218']) !!}
                </div>
                <div class="title">
                    {!! Loop::title() !!}
                </div>
                <div class="summary">
                    <p>{!! Loop::excerpt() !!}</p>
                </div>
            </a><!-- .block-body -->
            <div class="block-footer">
                <div>
                    <a class="share" href="#" rel="nofollow" title="<?php _e('PARTAGER', THEME_TD) ?>" rel="nofollow">
						<?php _e('PARTAGER', THEME_TD) ?>
                        <i class="icon icon-blog__preview-share"></i>
                    </a>
                      <div class="dropdown" data-uk-dropdown="mode:click">
                        <ul class="submenu">
                          <li><a href="#" title="Facebook"><i class="icon icon-social-facebook"></i></a></li>
                          <li><a href="#" title="Twitter"><i class="icon icon-social-twitter"></i></a></li>
                          <li><a href="#" title="Pinterest"><i class="icon icon-social-pinterest"></i></a></li>
                          <li><a href="#" title="Instagram"><i class="icon icon-social-instagram"></i></a></li>
                          <li><a href="#" title="Imprimer"><i class="icon icon-schedule-print"></i></a></li>
                        </ul>
                      </div>
                </div>
                <a class="readmore" title="<?php _e('Ça m’intéresse', THEME_TD) ?>" href="{{ Loop::link() }}">
					<?php _e('Ça m’intéresse', THEME_TD) ?>
                    <i class="icon icon-preview-arrow-right"></i>
                </a>
            </div><!-- .block-footer -->
        </div><!-- .block-content -->
    </div><!-- .block -->
    @endquery

@endif