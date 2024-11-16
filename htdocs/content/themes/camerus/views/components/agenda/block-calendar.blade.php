@if(!empty($salon))
    @php $inner_class = '' @endphp
    @if ($loop->first)
        @php $inner_class = 'uk-active' @endphp
    @endif
    <div class="inner {{ $inner_class }}">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">

                <div class="block block-calendar__event uk">
                    <div class="block-content">
                        <div class="block-body uk-grid uk-grid-large">

                            <div class="uk-width-1-3@m col-left uk-text-right@m uk-text-center">
                                {!! get_the_post_thumbnail($salon->ID, 'loop-product-thumbnail', ['width' => '184', 'height' => '103']) !!}
                            </div>
                            <div class="uk-width-2-3@m col-right">

                                <div class="about">
                                    <time class="date"
                                          datetime="{{ date('Y-m-d', strtotime($salon->salon_start_date)) }}">{!! strftime('%e %B', strtotime($salon->salon_start_date)) !!}
                                            <?php _e('au', THEME_TD) ?> {!! strftime('%e %B %Y', strtotime($salon->salon_end_date)) !!}</time>
                                    <h2 class="title">
                                        {!! $salon->post_title !!}
                                    </h2>
                                    <address class="address">
                                        {!! $salon->salon_place !!}
                                        {!! $salon->salon_address !!} - {!! $salon->salon_ville_name !!}
                                    </address>
                                </div>

                                <div class="btn-wrapper agenda-btn">
                                    @if(!isActiveSalonSystem())
                                        @if(!empty($salon->salon_external_link) && is_array($salon->salon_external_link))
                                            @include('shop.components.calendar-external-btn', ['link' => $salon->salon_external_link])
                                        @else
                                            <a class="btn" href="{{ get_permalink( wc_get_page_id( 'shop' ) ) }}">
                                                <span><?php _e('Choisir cet évenement', THEME_TD) ?></span>
                                            </a>
                                        @endif
                                    @else
                                        @if(isOverSalonLimitDate($salon->ID))
                                            <a class="btn" href="#" disabled="">
                                                <span><?php _e('Clôturé', THEME_TD) ?></span>
                                            </a>
                                        @else
                                            @if(WC()->cart->is_empty())
                                                @if(!empty($salon->salon_external_link) && is_array($salon->salon_external_link))
                                                    @include('shop.components.calendar-external-btn', ['link' => $salon->salon_external_link])
                                                @else
                                                    <a class="btn select-salon" href="#"
                                                       data-href="{{ $salon->product_url }}"
                                                       data-event-salon="{{ $salon->post_name }}"
                                                       data-event-type="{{ $salon->salon_city_rate_slug }}">
                                                        <span><?php _e('Choisir cet évenement', THEME_TD) ?></span>
                                                    </a>
                                                @endif
                                            @else
                                                @if(null === getEventSalonSlugInSession() && null === getEventSalonCitySlugInSession() )
                                                    @if(!empty($salon->salon_external_link) && is_array($salon->salon_external_link))
                                                        @include('shop.components.calendar-external-btn', ['link' => $salon->salon_external_link])
                                                    @else
                                                        <a class="btn select-salon" href="#"
                                                           data-href="{{ $salon->product_url }}"
                                                           data-event-salon="{{ $salon->post_name }}"
                                                           data-event-type="{{ $salon->salon_city_rate_slug }}">
                                                            <span><?php _e('Choisir cet évenement', THEME_TD) ?></span>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if($salon->post_name === getEventSalonSlugInSession())
                                                        @if(!empty($salon->salon_external_link) && is_array($salon->salon_external_link))
                                                            @include('shop.components.calendar-external-btn', ['link' => $salon->salon_external_link])
                                                        @else
                                                            <a class="btn select-salon" href="#"
                                                               data-href="{{ $salon->product_url }}"
                                                               data-event-salon="{{ $salon->post_name }}"
                                                               data-event-type="{{ $salon->salon_city_rate_slug }}">
                                                                <span><?php _e('Choisir cet évenement', THEME_TD) ?></span>
                                                            </a>
                                                        @endif
                                                    @else
                                                        <button class="btn select-salon salon-change-btn"
                                                                data-href="{{ $salon->product_url }}"
                                                                data-event-salon="{{ $salon->post_name }}"
                                                                data-event-type="{{ $salon->salon_city_rate_slug }}">
                                                            <span><?php _e('Choisir cet évenement', THEME_TD) ?></span>
                                                        </button>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                    @if(!empty(checkSalonUserFavoris($salon->ID)))
                                        <a class="star agenda-{{ $salon->ID }} active agenda-btn-favoris agenda-delete-favoris "
                                           href="#"
                                           data-salon="agenda-{{ $salon->ID }}"
                                           data-secu="{{ wp_create_nonce('salon-agenda-' . $salon->ID) }}"
                                           title="<?php _e('Supprimer de mes favoris', THEME_TD) ?>" data-uk-tooltip>
                                            <i class="icon icon-product-star-2"></i>
                                        </a>
                                    @elseif(!empty(get_current_user_id()))
                                        <a class="star agenda-{{ $salon->ID }} agenda-btn-favoris agenda-add-favoris"
                                           href="#"
                                           data-salon="agenda-{{ $salon->ID }}"
                                           data-secu="{{ wp_create_nonce('salon-agenda-' . $salon->ID) }}"
                                           title="<?php _e('Ajouter à mes favoris', THEME_TD) ?>" data-uk-tooltip>
                                            <i class="icon icon-product-star-1"></i>
                                        </a>
                                    @else
                                        <a class="star agenda-add-favoris"
                                           rel="nofollow"
                                           href="{{ wc_get_account_endpoint_url('edit-account') }}"
                                           data-salon="agenda-{{ $salon->ID }}"
                                           data-secu="{{ wp_create_nonce('salon-agenda-' . $salon->ID) }}"
                                           title="<?php _e('Ajouter à mes favoris', THEME_TD) ?>" data-uk-tooltip>
                                            <i class="icon icon-product-star-1"></i>
                                        </a>
                                    @endif
                                </div>

                                <div class="description">
                                    {!! $salon->post_content !!}
                                </div>

                                @if(isOverSalonLimitDate($salon->ID))
                                    @php $closed_salon_msg = get_field('salon_closed_msg', $salon->ID) @endphp
                                    @if(!empty($closed_salon_msg))
                                        <div class="message">{!! $closed_salon_msg !!}</div>
                                    @else
                                        @php $closed_msg = get_field('event_closed_msg', 'option') @endphp
                                        @if(!empty($closed_msg))
                                            <div class="message">{!! $closed_msg !!}</div>
                                        @endif
                                    @endif
                                @else
                                    @if(property_exists($salon, 'salon_msg') && !empty($salon->salon_msg))
                                        <div class="message">{!! $salon->salon_msg !!}</div>
                                    @endif
                                @endif

                            </div>

                        </div><!-- .block-body -->
                    </div><!-- .block-content -->
                </div><!-- .block-calendar__event -->

                @if(!empty($salon->salon_gallery))
                    @foreach($salon->salon_gallery as $img)
                        <div class="block block-articles__preview uk-width-1-3@m">
                            <div class="block-content match-1" href="{{ $img['url'] }}" title="{{ $img['title'] }}">
                                <div class="block-header img-container img-middle">
                                    {!! wp_get_attachment_image($img['ID'], 'agenda-lightbox-thumbnail', false, ['width' => '285', 'height' => '218'] ) !!}
                                </div><!-- .block-header -->
                            </div><!-- .block-content -->
                        </div><!-- .block-articles__preview -->
                    @endforeach
                @endif

            </div><!-- .col -->
        </div><!-- .row -->
    </div>
@endif
