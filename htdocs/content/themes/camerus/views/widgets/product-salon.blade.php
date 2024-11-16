<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;

$class = 'load';
if (Route::currentRouteName() == 'search-product'
    || Route::currentRouteName() == 'dotation-list'
    || Route::currentRouteName() == 'dotation-list-en') {
    $class = 'no-salon';
}
$id = get_queried_object_id();
if ($id) {
    $product = wc_get_product($id);
    if (! empty($product)) {
        $type = $product->get_type();
        if ($type == 'dotation') {
            $class = 'no-salon';
        }
    }
}
?>

<div class="block block-widget__event {{ $class }}">
    <div data-uk-spinner="ratio: 2"></div>

    <div class="block-content">
        @if(!empty($term_salon))
                <?php

                $salon_city = '';
                $primary_city = getPrimaryTaxTerm('salon_city', true, $term_salon->ID);
                if (! empty($primary_city)) {
                    $salon_city = $primary_city->name;
                }
                $salon_active = '';
                $salon_event = request()->get('salon-filter');
                if (! empty($salon_event)) {
                    $salon_active = 'uk-active';
                }
                $is_active_style = get_field('is_style_active', $term_salon->ID);
                ?>
            <div class="block-header">

                <figure class="img-container hidden-xs @if($is_active_style) hidden @endif">
                    {!! get_the_post_thumbnail($term_salon->ID, 'agenda-lightbox-thumbnail', ['width' => '191', 'height' => '77']) !!}
                </figure>

                <strong class="title">{!! $term_salon->post_title !!}</strong>
                <div class="place">
                    {!! get_field('salon_place', $term_salon->ID) !!} <br>
                    {!! get_field('salon_address', $term_salon->ID) !!} - {!! $salon_city !!}
                    <i class="icon icon-widget__event-pin"></i>
                </div>
            </div><!-- .block-header -->
            <div class="block-body">
                <p>{!! $term_salon->post_excerpt !!}</p>
            </div><!-- .block-body -->
        @endif
        <div class="block-footer">
            @if(!empty($view_link))
                <a href="{{ get_permalink(ID_LIST_SALON) }}"
                   title="<?php _e('Vous souhaitez Changer d’évenement ?', THEME_TD) ?>"><?php _e('Vous souhaitez Changer d’évenement ?', THEME_TD) ?></a>
                <br>
                @if(!empty($term_salon))
                    <a href="{{ showroomGetUrl($term_salon->post_name) }}"
                       class="{{ $salon_active }}"
                       title="<?php _e('Voir les produits de cet évenement', THEME_TD) ?>"><?php _e('Voir les produits de cet évenement', THEME_TD) ?></a>
                    <br>
                @endif
                @if(isProCustomer() && !isEventSalonSession())
                    <a href="#"
                       id="cmrs-pro-change-event"
                       class="{{ $salon_active }}"
                       data-href="{{ get_permalink(wc_get_page_id('shop')) }}"
                       data-event-salon=""
                       data-event-type="event"
                       title="<?php _e('Accéder aux tarifs Event', THEME_TD) ?>"><?php _e('Accéder aux tarifs Event', THEME_TD) ?></a>
                @endif
            @endif
            @if(!empty($term_salon))
                    <?php $is_active_style = get_field('is_style_active', $term_salon->ID); ?>
                @if($is_active_style && !empty($_SESSION['event_style_url']))
                    <div class="btn-style styleroom-return">
                        <a href="{{ $_SESSION['event_style_url'] ?? '#' }}"
                           class="btn"><?php _e('Revenir aux Styles'); ?></a>
                    </div>
                @endif
            @endif
        </div><!-- .block-footer -->
    </div><!-- .block-content -->
</div><!-- .block-widget__event -->