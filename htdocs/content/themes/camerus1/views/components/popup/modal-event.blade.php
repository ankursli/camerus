<?php
use App\Hooks\Salon;

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
<div id="modal-event" data-uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <div data-uk-spinner="ratio: 2"></div>
        <a href="#" class="uk-modal-close" rel="nofollow"><i class="icon icon-modal-close"></i></a>
        <div class="block block-event__modal">
            <div class="block-header" style="text-align: center"><?php _e('Choisissez sa zone', THEME_TD) ?></div>
            <!-- .block-header -->
            <div class="block-body" style="margin-top: 35px">
                <div class="summary hide" style="text-align: center">
                    <p><?php echo get_field('app_popup_salon_text', 'option') ?></p>
                </div>
            </div>
            <form class="block-content modal-select-salon-city" method="GET"
                  action="{{ get_permalink( wc_get_page_id( 'shop' )) }}">
                <input type="hidden" name="product_listing_page" class="product_listing_page"
                       value="{{ get_permalink( wc_get_page_id( 'shop' ) ) }}">
                <input type="hidden" name="cmrs_homepage" class="cmrs_homepage" value="{{ home_url() }}">
                @if(!is_product_category() || Request::route()->getName() == 'showroom-template')
                    <input type="hidden" name="reset_salon_slug" id="reset_salon_slug" value="1">
                @endif
                @if(Request::route()->getName() == 'search-product')
                    <input type="hidden" name="old-query" value="1">
                @endif
                <div class="block-footer">
                    <label class="label" for="event__modal-place">
                        <?php _e("Votre salon n'est pas dans l'agenda ?", THEME_TD) ?>
                        <br><?php _e('Sélectionnez sa zone géographique', THEME_TD) ?>
                    </label>
                    <select name="{{ SLUG_EVENT_CITY_QUERY }}" id="event__modal-place" class="select">
                        <option value="" disabled selected><?php _e('Choisir', THEME_TD); ?></option>
                        @if(!empty($salon_cities) && is_array($salon_cities))
                            @foreach($salon_cities as $city)
                                @if($city->slug !== 'event')
                                    <option value="{{ $city->slug }}">{!! !empty($city->description) ? $city->description : $city->name !!}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div><!-- .block-footer -->
            </form><!-- .block-content -->
        </div><!-- .block-event__modal -->

    </div>
</div><!-- #modal-event -->