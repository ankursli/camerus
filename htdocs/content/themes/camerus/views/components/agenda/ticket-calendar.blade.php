@php
    $data_uk = '';
    if(!(is_home() || is_front_page())) {
        $data_uk = 'data-uk-switcher="connect:#calendar .uk-switcher"';
    }
@endphp


<ul class="block-body slick-content-slide"
    data-uk-height-match="target:.ticket .ticket-header"
        {!! $data_uk !!}>

    @if(!empty($salons) && is_array($salons))
        @foreach($salons as $salon)
            <li>
                <div id="salon-{{ $salon->post_name }}" class="ticket trs" style="min-width: 100px;">
                    <div class="ticket-content">
                        <div class="ticket-header">
                            <strong class="number">{{ date('j', strtotime($salon->salon_start_date)) }}</strong>
                            <div class="month">{!! ucfirst(strftime('%B', strtotime($salon->salon_start_date))) !!}</div>
                            <div class="title">
                                {!! $salon->post_title !!}
                            </div>
                        </div><!-- .ticket-header -->
                        <address class="ticket-body">
                            <span class="address">{!! $salon->salon_address !!}<br></span>
                            <em class="city">{!! $salon->salon_ville_name !!}</em>
                        </address><!-- .ticket-body -->
                        <div class="ticket-footer">
                            @if((!empty($source) && 'home' === $source) || is_home() || is_front_page())
                                @if(!isActiveSalonSystem())
                                    <a class="btn"
                                       href="{{ get_permalink( wc_get_page_id( 'shop' ) ) }}"
                                       title="<?php _e('Choisir', THEME_TD) ?>"><?php _e('Choisir', THEME_TD) ?></a>
                                @else
                                    @if(isOverSalonLimitDate($salon->ID))
                                        <a class="btn"
                                           href="{{ get_permalink( $salon->ID ) }}"
                                           title="<?php _e('Clôturé', THEME_TD) ?>"><?php _e('Clôturé', THEME_TD) ?></a>
                                    @else
                                        @php
                                            $url_salon = showroomGetUrl($salon->post_name)
                                        @endphp
                                        @if(WC()->cart->is_empty())
                                            <a class="btn select-salon" href="#"
                                               data-href="{{ $url_salon }}"
                                               data-event-salon="{{ $salon->post_name }}"
                                               data-event-type="{{ $salon->salon_city_rate_slug }}"
                                               title="<?php _e('Choisir', THEME_TD) ?>"><?php _e('Choisir', THEME_TD) ?></a>
                                        @else
                                            @if(null === getEventSalonSlugInSession() && null === getEventSalonCitySlugInSession() )
                                                <a class="btn select-salon salon-change-btn" href="#"
                                                   data-href="{{ $url_salon }}"
                                                   data-event-salon="{{ $salon->post_name }}"
                                                   data-event-type="{{ $salon->salon_city_rate_slug }}"
                                                   title="<?php _e('Choisir', THEME_TD) ?>"><?php _e('Choisir', THEME_TD) ?></a>
                                            @else
                                                @if($salon->post_name === getEventSalonSlugInSession())
                                                    <a class="btn select-salon" href="#"
                                                       data-href="{{ $url_salon }}"
                                                       data-event-salon="{{ $salon->post_name }}"
                                                       data-event-type="{{ $salon->salon_city_rate_slug }}"
                                                       title="<?php _e('Choisir', THEME_TD) ?>"><?php _e('Choisir', THEME_TD) ?></a>
                                                @else
                                                    <a class="btn select-salon salon-change-btn" href="#"
                                                       data-href="{{ $url_salon }}"
                                                       data-event-salon="{{ $salon->post_name }}"
                                                       data-event-type="{{ $salon->salon_city_rate_slug }}"
                                                       title="<?php _e('Choisir', THEME_TD) ?>"><?php _e('Choisir', THEME_TD) ?></a>
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </div><!-- .ticket-footer -->
                    </div><!-- .ticket-content -->
                </div><!-- .ticket-lounge -->
            </li>
        @endforeach
    @endif

</ul>

<a class="uk-position-center-left uk-position-small uk-hidden-hover arrow-salon arrow-left" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
<a class="uk-position-center-right uk-position-small uk-hidden-hover arrow-salon arrow-right" href="#" uk-slidenav-next uk-slider-item="next"></a>
