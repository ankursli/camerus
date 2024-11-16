<h1 class="hide">{{ the_title() }}</h1>
<main id="main" {!! post_class() !!}>

    <div id="banner" class="section container-fluid">
        <div class="section-header"></div><!-- .section-header -->
        <div class="section-body inner">

            <div class="row">
                <div class="col-md-10 col-md-offset-1">


                    <div class="block block-banner__slider uk trs-n">
                        <div class="block-content">
                            <div class="block-body" data-uk-slideshow="animation:fade;ratio:16:6">
                                <ul class="uk-slideshow-items">
                                    <li class="img-container">

                                        @if(!empty($home_video))
                                            {!! $home_video !!}
                                        @else
                                            {!! the_post_thumbnail('full', ['data-uk-c0ver' => null]) !!}
                                        @endif

                                        <div id="play-button" class="slogan uk-position-center-left">
                                            @if(!empty($home_png_img))
                                                {!! wp_get_attachment_image($home_png_img['ID'], 'full', false, ['width' => 322, 'height' => 366]) !!}
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- .block-content -->
                    </div><!-- .block-banner__slider -->

                </div><!-- .col -->
            </div><!-- .row -->


        </div><!-- .section-body -->
        <div class="section-footer"></div><!-- .section-footer -->
    </div><!-- #banner -->

    {!! Loop::content() !!}

    @include('components.page.full-post-list')

    @include('components.page.reinsurances')

    @include('components.page.footer-social')

</main><!-- #main -->
