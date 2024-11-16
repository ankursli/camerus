<div id="layout" class="layout container-fluid">
    <div class="layout-body inner">
        <div class="row">

            <aside class="col-sm-3 col-sm-offset-1 hidden-xs">

                <div class="block block-blog__introduction">
                    <div class="block-content">
                        <div class="block-header">
                            <figure class="aligncenter img-container">
                                @if(!empty($category) && !empty($category->logo))
                                    {!! wp_get_attachment_image($category->logo['ID'], 'full') !!}
                                @endif
                            </figure>
                            <strong class="title">{!! $category->name !!}</strong>
                        </div><!-- .block-header -->
                        <div class="block-body">
                            <div class="summary">
                                <p>{!! $category->description !!}</p>
                            </div>
                        </div><!-- .block-body -->
                        <div class="block-footer"></div><!-- .block-footer -->
                    </div><!-- .block-content -->
                </div><!-- .block-blog__introduction -->

                @include('components.article.sidebar-article')

            </aside><!-- aside -->
            <div class="col-lg-6 col-lg-offset-1 col-md-7 col-md-offset-0 col-sm-7 col-sm-offset-0">

                <div class="block block-blog__content uk-width-1-1@m">
                    <div class="block-content">
                        <div class="block-header">
                            <time class="date"
                                  datetime="{{ Loop::date() }}">{{ Loop::date() }}</time>
                            @if(!empty($category))
                                <a href="{{ get_term_link($category->term_id) }}" class="tag"
                                   title="{{ $category->name }}">{{ $category->name }}</a>
                            @endif

                            <a class="share" href="#" rel="nofollow" title="<?php _e('PARTAGER', THEME_TD) ?>">
                                <i class="icon icon-blog__preview-share"></i>
                            </a>
                            @include('components.article.share-dropdown')

                            <a href="javascript:ui.links.close()" title="<?php e('Fermer', THEME_TD) ?>" class="close">
                                <svg version="1.1" class="blog__content-close" xmlns="http://www.w3.org/2000/svg"
                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     viewBox="0 0 30 29.9" style="enable-background:new 0 0 30 29.9;"
                                     xml:space="preserve">
                          <style type="text/css">
                              .st0 {
                                  fill-rule: evenodd;
                                  clip-rule: evenodd;
                                  fill: #919191;
                              }

                              .st1 {
                                  fill: #919191;
                              }

                              .st2 {
                                  fill: none;
                                  stroke: #FF571C;
                                  stroke-miterlimit: 10;
                              }
                          </style>
                                    <g>
                                        <polygon class="st0" points="21.2,9.2 20.7,8.8 15,14.4 9.2,8.8 8.8,9.2 14.4,15 8.8,20.7 9.2,21.2 15,15.5 20.7,21.2 21.2,20.7
                                                         15.5,15 	"/>
                                        <path class="st1" d="M15,29.9C6.7,29.9,0,23.2,0,15C0,6.7,6.7,0,15,0l0,0c8.3,0,15,6.7,15,15C29.9,23.2,23.2,29.9,15,29.9z M15,1
                                                 C7.2,1,1,7.2,1,15c0,7.7,6.2,14,14,14c7.7,0,14-6.2,14-14C28.9,7.2,22.7,1,15,1L15,1z"/>
                                    </g>
                                    <circle class="st2" cx="15" cy="15" r="12.4"/>
                        </svg>
                            </a>
                        </div><!-- .block-header -->
                        <div class="block-body">
                            <div class="img-container img-middle">
                                {!! Loop::thumbnail('full', ['width' => '285', 'height' => '218']) !!}
                            </div>
                            <h1 class="title">
                                {!! Loop::title() !!}
                            </h1>
                            <div class="summary">
                                <p>{!! Loop::excerpt() !!}</p>
                            </div>
                        </div><!-- .block-body -->
                        <div class="block-footer rte">
                            {!! Loop::content() !!}
                        </div>
                    </div><!-- .block-content -->
                </div><!-- .block -->

                <div class="block block-blog__share">
                    <div class="block-content">
                        <div class="block-body">

                            <a class="share" href="#" rel="nofollow" title="<?php _e('PARTAGER', THEME_TD) ?>" rel="nofollow">
								<?php _e('PARTAGER', THEME_TD) ?>
                                <i class="icon icon-blog__preview-share"></i>
                            </a>
                            @include('components.article.share-dropdown')

                        </div><!-- .block-body -->
                        <div class="block-footer"></div><!-- .block-footer -->
                    </div><!-- .block-content -->
                </div><!-- .block-blog__share -->

            </div>

        </div>
    </div><!-- .layout-body -->
</div><!-- #layout -->