<?php
/**
 * Get salon query
 */
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

$url_args = '?';
$salon_session = getEventSalonSlugInSession();
$salon_event = Request::input(defined('SLUG_EVENT_SALON_QUERY') ? SLUG_EVENT_SALON_QUERY : 'event_salon');
$salon_city = Request::input(defined('SLUG_EVENT_CITY_QUERY') ? SLUG_EVENT_CITY_QUERY : 'event_city');
$salon_hide_cat = [];
if (!empty($salon_session)) {
    $salon_id = getPostIdBySlug($salon_session);
    if (!empty($salon_id)) {
        $salon_hide_cat = get_field('salon_hide_cat', $salon_id);
    }
}

if (!empty($salon_event)) {
    $url_args .= '&' . SLUG_EVENT_SALON_QUERY . '=' . $salon_event;
}
if (!empty($salon_city)) {
    $url_args .= '&' . SLUG_EVENT_CITY_QUERY . '=' . $salon_city;
}
$url_args = str_replace('?&', '?', $url_args);
$url_args = rtrim($url_args, '?');
?>

<div class="block block-widget__categories">
    <div class="block-content">
        <ul class="block-body">
            <li class="header">
                <a href="{{ get_permalink( wc_get_page_id( 'shop' ) ) . $url_args }}"
                   title="<?php _e('Tous les produits', THEME_TD) ?>">
                    <i class="icon icon-preview-arrow-right"></i>
                    <span>{!! $title !!}</span>
                </a>
                <button class="btn visible-xs" data-uk-toggle="target:.block-widget__categories;cls:active">
                    <i class="icon icon-modal-close"></i>
                </button>
            </li>

            @if(!empty($product_categories) && is_array($product_categories))
                @foreach($product_categories as $pc)
                    <?php
                    if (!empty($salon_hide_cat) && in_array($pc->term_id, $salon_hide_cat)) {
                        continue;
                    }
                    ?>
                    <?php
                    $class = '';
                    if (!empty($current_term) && $pc->term_id === $current_term) {
                        $class = 'active';
                    }
                    ?>
                    <li class="{{ $class ?? '' }}">
                        @if(Route::currentRouteName() == 'showroom-template')
                            <a href="{{ showroomGetProductCatagoryUrl(get_term_link($pc->term_id)) }}"
                               title="{{ $pc->name }}">
                                <i class="icon icon-preview-arrow-right"></i>
                                <span>{!! $pc->name !!}</span>
                            </a>
                        @else
                            <a href="{{ get_term_link($pc->term_id) }}" title="{{ $pc->name }}">
                                <i class="icon icon-preview-arrow-right"></i>
                                <span>{!! $pc->name !!}</span>
                            </a>
                        @endif
                        <?php
                        $args = array(
                            'parent' => $pc->term_id,
                        );
                        $subcategories = get_terms('product_cat', $args);
                        ?>
                        @if(!empty($subcategories))
                            <ul class="sub-category">
                                @foreach($subcategories as $sb)
                                    <?php
                                    if (!empty($salon_hide_cat) && in_array($sb->term_id, $salon_hide_cat)) {
                                        continue;
                                    }
                                    ?>
                                    <?php
                                    $class = '';
                                    if (!empty($current_term) && $sb->term_id === $current_term) {
                                        $class = 'active';
                                    }
                                    ?>
                                    <li class="{{ $class ?? '' }}">
                                        @if(Route::currentRouteName() == 'showroom-template')
                                            <a href="{{ showroomGetProductCatagoryUrl(get_term_link($sb->term_id)) }}"
                                               title="{{ $sb->name }}">
                                                <i class="icon icon-preview-arrow-right"></i>
                                                <span>{!! $sb->name !!}</span>
                                            </a>
                                        @else
                                            <a href="{{ get_term_link($sb->term_id) }}" title="{{ $sb->name }}">
                                                <i class="icon icon-preview-arrow-right"></i>
                                                <span>{!! $sb->name !!}</span>
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            @endif

        </ul><!-- .block-body -->
    </div><!-- .block-content -->
    <div class="block-extra visible-xs">
        <button class="btn" data-uk-toggle="target:.block-product__filter;cls:active">
            <i class="icon icon-product__filter-sort"></i>
        </button>
    </div>
</div><!-- .block-widget__categories -->