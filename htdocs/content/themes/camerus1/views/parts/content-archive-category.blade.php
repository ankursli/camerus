<main id="main" {!! post_class() !!}>

    {!! do_action('breadcrumb_navigation') !!}

    <div id="heading" class="layout container-fluid">
        <div class="layout-body inner">
            <div class="row">

                <div class="col-sm-10 col-sm-offset-1">
                    <div class="uk-grid-small" data-uk-grid>

                        <div class="block block-inspirations__logo uk-width-1-1">
                            <div class="block-content">
                                <a class="block-body" href="{{ home_url() }}/blog">
                                    <img src="{{ get_template_directory_uri() . '/dist/images/inspirations__logo-Inspirations.by.camerus.png' }}"
                                         width="406" height="207" class="" alt="Inspirations by {{ SITE_MAIN_SYS_NAME }}"
                                         srcset="{{ get_template_directory_uri() . '/dist/images/inspirations__logo-Inspirations.by.camerus.png' }}"/>
                                </a><!-- .block-body -->
                            </div><!-- .block-content -->
                        </div><!-- .block-inspiration__logo -->

                        <h1 style="text-align: center; color: #ff560d; font-size: 25px; width: 100%; margin-bottom: 30px;">{{ get_the_title() }}</h1>

                        @include('components.article.blog-filter')

                        @if(!empty($posts) && is_array($posts))
                            @foreach($posts as $p_key => $post)

                                @if($p_key === 0)
                                    @include('components.article.block-article', ['preview' => 1, 'size' => '2', 'post' => $post])
                                @elseif($p_key === 1)
                                    @include('components.article.block-article', ['preview' => 1, 'post' => $post])
                                @else
                                    @break
                                @endif

                            @endforeach
                        @endif

                    </div>
                </div><!-- .col -->

            </div>
        </div><!-- .layout-body -->
    </div><!-- #heading -->

    @include('components.article.category-banner')

    <div id="layout" class="layout container-fluid">
        <div class="layout-body inner">
            <div class="row">

                <div class="col-sm-10 col-sm-offset-1">
                    <div class="uk-grid-small uk-child-width-expand" data-uk-grid>

                        @if(!empty($posts) && is_array($posts))
                            @foreach($posts as $p_key => $post)

                                @if($p_key > 1)
                                    @include('components.article.block-article', ['preview' => 1, 'post' => $post])
                                @endif

                            @endforeach
                        @endif

                    </div>
                </div><!-- .col -->

            </div>
        </div><!-- .layout-body -->
    </div><!-- #layout -->

    @include('components.page.footer-social')

</main><!-- #main -->