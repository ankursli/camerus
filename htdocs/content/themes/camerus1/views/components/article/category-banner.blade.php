<?php
$acf_page_id = get_the_ID();
if (is_category()) {
    $acf_page_id = get_queried_object_id();
    $acf_page_id = 'category-'.$acf_page_id;
}
$banner = get_field('blog_banner_hover', $acf_page_id);
?>
<div id="banner" class="section container-fluid">
    <div class="section-header"></div><!-- .section-header -->
    <div class="section-body inner">

        <div class="row">
            <div class="col-md-12">


                <div class="block block-banner__slider uk trs-n">
                    <div class="block-content">
                        <div class="block-body" data-uk-slideshow="animation:fade;ratio:11:5">
                            <ul class="uk-slideshow-items">
                                <li class="img-container">
                                    @if(!empty($banner_img = get_the_post_thumbnail_url(get_the_ID(), 'full')))
                                        <img src="{{ $banner_img }}" width="1101" height="502" class="" alt="{{ SITE_MAIN_SYS_NAME }} banner"/>
                                    @endif
                                    @if(!empty($banner))
                                        <div id="play-button" class="slogan uk-position-center-right">
                                            <img src="{{ wp_get_attachment_url($banner['ID'], 'full') }}"
                                                 width="322" height="366" class="" alt="{{ SITE_MAIN_SYS_NAME }} Signe le Mobilier"
                                                 srcset="{{ wp_get_attachment_url($banner['ID'], 'full') }}"/>
                                        </div>
                                    @endif
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
