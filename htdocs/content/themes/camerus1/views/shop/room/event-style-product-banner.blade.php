<?php
$urlParts = explode("/", url()->current());
$style_slug = ! empty($urlParts[5]) ? $urlParts[5] : null;
$style = null;
$is_active_style = false;
if (! empty(! empty($urlParts[4]))) {
    $salon_slug = $urlParts[4];
    $salon = cmrs_get_post_by_slug($salon_slug, 'salon');
    if (! empty($salon)) {
        $is_active_style = get_field('is_style_active', $salon->ID);
    }
}
if (! empty($style_slug)) {
    $style = get_term_by('slug', $style_slug, 'salon_style');
    if (! empty($style)) {
        $top_image = get_field('top_image', 'salon_style_'.$style->term_id);
        $top_image_url = ! empty($top_image) ? $top_image['url'] : get_template_directory_uri().'/dist/images/banner__slide-img4.jpg';
        $left_image = get_field('left_image', 'salon_style_'.$style->term_id);
        $left_image_url = ! empty($left_image) ? $left_image['url'] : get_template_directory_uri().'/dist/images/download__item-img4.png';
        $right_image = get_field('right_image', 'salon_style_'.$style->term_id);
        $right_image_url = ! empty($right_image) ? $right_image['url'] : get_template_directory_uri().'/dist/images/banner__slide-img2.jpg';
        $bg_color = get_field('bg_color', 'salon_style_'.$style->term_id);
        $text_color = get_field('text_description_color', 'salon_style_'.$style->term_id);
        $content_3d = get_field('3d_content', 'salon_style_'.$style->term_id);
    }
}
?>

@if(!empty($is_active_style))
    <section class="container-fluid top-bg-block style-product">
        <div class="row">
            <div class="style-top-bg">
                <img src="{{ $top_image_url }}" alt="">
            </div>
        </div>
        <div class="row style-top-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 my-4">
                        <h2 class="top-title">{{ $style->name }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container-fluid content-style-product">
        <div class="row block-container-img">
            <div class="col-md-6 block-img-left">
                <div class="block-img-1">
                    <img src="{{ $left_image_url }}" alt="">
                </div>
                <div class="block-img-2" style="background-color: {{ !empty($bg_color) ? $bg_color : 'transparent' }};">
                    <div class="style-top-bg-slide-3d">
                        @if(!empty($content_3d))
                            @foreach($content_3d as $c_3d)
                                <div class="slide-sketchup-container">
                                    <div class="block-description"
                                         style="color: {{ !empty($text_color) ? $text_color : '#000' }} !important;">
                                        {!! $c_3d['description'] !!}
                                    </div>
                                    <a href="@if(!empty($c_3d['sketchfab_link'])) #modal-sketch @else # @endif"
                                       @if(!empty($c_3d['sketchfab_link']))
                                           data-uk-toggle
                                       data-src="{{ $c_3d['sketchfab_link'] }}"
                                       title="Vue 3D"
                                       data-uk-tooltip="title:Vue 3D;pos:right"
                                       @endif
                                       @if(empty($c_3d['sketchfab_link'])) style="pointer-events: none;" @endif
                                       class="slide-sketchup">
                                        @if(!empty($c_3d['image']))
                                            <div class="sketchup-image">
                                                <img src="{{ $c_3d['image']['url'] }}"
                                                     alt="{{ $c_3d['image']['alt'] }}">
                                            </div>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="block-footer style-product-3d-arrow">
                        <button class="btn arrow-left" data-uk-slideshow-item="previous" rel="nofollow">
                            <i class="icon icon-product-arrow-left"></i>
                        </button>
                        <button class="btn arrow-right" data-uk-slideshow-item="next" rel="nofollow">
                            <i class="icon icon-product-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 block-img-3">
                <div class="container-image">
                    <img src="{{ $right_image_url }}" alt="">
                </div>
            </div>
        </div>
    </section>
@endif