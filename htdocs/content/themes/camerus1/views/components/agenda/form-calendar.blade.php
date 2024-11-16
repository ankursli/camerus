<?php
use Illuminate\Support\Facades\Request;
global $post;

$q_city = Request::input(SLUG_EVENT_CITY_QUERY);
?>

<form id="salon_filter_form" class="inner" action="POST">
    <input type="hidden" name="_wpnonce_security" value="<?php echo wp_create_nonce('filter-salon') ?>">
    <input type="hidden" name="source_page" value="{{ is_home() || is_front_page() ? 'home' : 'other' }}">
    @csrf
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">

            <div class="block block-calendar__title">
                <div class="block-content">
                    <div class="block-header">
                        <i class="icon icon-lounge-calendar"></i>
                        <?php _e('L’agenda des salons', THEME_TD) ?>
                    </div><!-- .block-header -->
                    <div class="block-body">
                        <?php _e('Pour découvrir nos mobiliers en location, choisissez votre salon.', THEME_TD) ?>
                    </div><!-- .block-body -->
                </div><!-- .block-content -->
            </div><!-- .block-calendar__title -->

            <div class="block block-calendar__select">
                <div class="block-content">
                    <div class="block-header">
                        @if(!empty($months) && is_array($months))
                            <select name="calendar_date" id="calendar_date" class="select">
                                <option value="" selected><?php _e('Tous', THEME_TD) ?></option>
                                @foreach($months as $date)
                                    @if($loop->first)
                                        <option value="{{ $date->format('Y-m') }}">{!! ucfirst($date->formatLocalized('%B %Y')) !!}</option>
                                    @else
                                        <option value="{{ $date->format('Y-m') }}">{!! ucfirst($date->formatLocalized('%B %Y')) !!}</option>
                                    @endif
                                @endforeach
                            </select>
                        @endif

                        @if(!empty($cities) && is_array($cities))
                            <select name="calendar_place" id="calendar_place" class="select">
                                <option value=""
                                        onclick="window.location.reload();" selected><?php _e('Toutes', THEME_TD) ?></option>
                                @foreach($cities as $city => $city_date)
                                    @if($city_date->slug === $q_city)
                                        <option value="{{ $city_date->slug }}">{!! $city_date->name !!}</option>
                                    @else
                                        <option value="{{ $city_date->slug }}">{!! $city_date->name !!}</option>
                                    @endif
                                @endforeach
                            </select>
                        @endif
                    </div><!-- .block-header -->
                    <div class="block-body">
                        <a href="#modal-event" title="<?php _e("Votre salon n'est pas dans l'agenda ?", THEME_TD) ?>"
                           data-uk-toggle>
                            <span class="trs-n hidden-xs"><?php _e("Votre salon n'est pas dans l'agenda ?", THEME_TD) ?></span>
                            <i class="icon icon-preview-arrow-right hidden-xs"></i>
                            <i class="icon icon-topbar-search visible-xs"></i>
                        </a>
                    </div><!-- .block-body -->
                    <?php
                    if (is_front_page()) {
                        $href = "agenda/#print";
                    } else {
                        $href = 'javascript:window.print()';
                    }
                    $href = '#';
                    ?>
                    <div class="block-footer">
                        <a href="<?php echo $href; ?>" class="btn print-to-pdf"
                           title="<?php _e("IMPRIMER L'AGENDA", THEME_TD) ?>">
                            <i class="icon icon-schedule-print"></i>
                            <span class="hidden-xs"><?php _e("IMPRIMER L'AGENDA", THEME_TD) ?></span>
                        </a>
                    </div><!-- .block-footer -->
                </div><!-- .block-content -->
            </div><!-- .block-calendar__select -->

        </div><!-- .col -->
    </div>
</form>