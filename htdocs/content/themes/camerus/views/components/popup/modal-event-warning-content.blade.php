<?php
use App\Hooks\Salon;
use Illuminate\Support\Facades\Request;

$_salons = [];
$all_salons = Salon::getSalon();
if (!empty($all_salons)) {
    foreach ($all_salons as $sal) {
        if (!isOverSalonLimitDate($sal->ID)) {
            $_salons[] = $sal;
        }
    }
}
$salon_cities = Salon::getAgendaCityRate();
$class_btn = '';
if (!WC()->cart->is_empty()) {
    $class_btn = 'salon-change-city-btn';
}
$salons = [];
if (!empty($_salons) && is_array($_salons)) {
    foreach ($_salons as $_salon) {
        $salons[$_salon->post_title . $_salon->ID] = $_salon;
    }
    ksort($salons);
}
?>
<form class="block-content modal-select-salon-event" method="GET" action="{{ Request::url() }}">
    <input type="hidden" name="product_listing_page" class="product_listing_page"
           value="{{ get_permalink( wc_get_page_id( 'shop' ) ) }}">
    <input type="hidden" name="cmrs_homepage" class="cmrs_homepage" value="{{ home_url() }}">
    @if(!is_product_category() || Request::route()->getName() == 'showroom-template')
        <input type="hidden" name="salon-filter" id="cmrs-modal-salon-filter" value="">
    @endif
    @if(Request::route()->getName() == 'search-product' || (isset($page_type) && $page_type == 'search-list'))
        <input type="hidden" name="old-query" value="1">
@endif

<!-- .block-header -->
    <div class="block-body">
        <div class="showroom">
            <div class="form-group">
                <select class="select" name="{{ SLUG_EVENT_SALON_QUERY }}" id="event__modal-showroom">
                    @if(!empty($salons) && is_array($salons))
                        @foreach($salons as $salon)
                            <option data-type="event-salon"
                                    value="{{ $salon->post_name }}">{!! $salon->post_title !!}</option>
                        @endforeach
                    @endif

                    <option disabled value=""><?php _e('Ou sélectionnez sa zone géographique', THEME_TD); ?>:
                    </option>
                    @if(!empty($salon_cities) && is_array($salon_cities))
                        @foreach($salon_cities as $city)
                            @if($city->slug !== 'event')
                                <option data-type="event-type"
                                        value="{{ $city->slug }}">{!! !empty($city->description) ? $city->description : $city->name !!}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
        </div><!-- .shorwoom -->
    </div><!-- .block-body -->
</form>

