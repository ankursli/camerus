<?php
$is_active_style = false;
$styleUrl = '#';
$slides = [];
$urlParts = explode("/", url()->current());
if (! empty($urlParts[4])) {
    $salon_slug = $urlParts[4];
    $styleUrl = styleroomGetUrl($salon_slug);
    $_SESSION['event_style_url'] = $styleUrl;
    $salon = cmrs_get_post_by_slug($salon_slug, 'salon');
    if (! empty($salon)) {
        $slides = get_field('salon_slide', $salon->ID);
        $is_active_style = get_field('is_style_active', $salon->ID);
        $style_banner_text_1 = get_field('style_banner_text_1', $salon->ID);
        $style_banner_text_2 = get_field('style_banner_text_2', $salon->ID);
        $style_banner_default = __('Trouvez').'<br>'.__('votre style');
        if (str_contains(url()->current(), '/showroom/')) {
            $style_banner_text = ! empty($style_banner_text_1) ? $style_banner_text_1 : $style_banner_default;
        } else {
            $style_banner_text = ! empty($style_banner_text_2) ? $style_banner_text_2 : $style_banner_default;
        }
    }
}
?>
@if(!empty($slides) && !empty($is_active_style))
    <section class="container-fluid top-bg-block salon-banner">
        <div class="row style-top-bg-slide">
            @foreach($slides as $slide)
                @if(!empty($slide['image']))
                    <div class="style-top-bg">
                        <img src="{{ $slide['image']['url'] }}" alt="{{ $slide['image']['alt'] }}">
                    </div>
                @endif
            @endforeach
        </div>
        <a href="{{ $styleUrl }}" class="d-flex row style-top-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 my-4">
                        <h2 class="top-title">{!! $style_banner_text !!}</h2>
                    </div>
                    @if(str_contains(url()->current(), '/showroom/'))
                        <div class="col-sm-6 my-4">
                            <div class="btn-style">
                                <span class="btn"><?php _e('En savoir plus'); ?></span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </a>
    </section>
@endif