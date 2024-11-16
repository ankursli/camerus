<?php
if(function_exists('get_field'))
{
    $bottom_elements = get_field('site_bottom_bloc', 'option');
}
?>
@if(!empty($bottom_elements) && is_array($bottom_elements))
    <div id="reinsurances" class="section container-fluid">
        <div class="section-body inner">
            <div class="col-md-8 col-sm-10 col-md-offset-2 col-sm-offset-1">

                <div class="reinsurances-container uk-flex uk-flex-between uk-flex-top">

                    @foreach($bottom_elements as $element)

                        <div class="block block-reinsurances__reinsurance uk">
                            <div class="block-content">
                                <div class="block-header match-1 uk-flex uk-flex-center uk-flex-middle">
                                    @if(array_key_exists('site_bottom_bloc_image', $element))
                                        <img src="{{ $element['site_bottom_bloc_image']['url'] }}"
                                             width="50" height="35"
                                             class="" alt="{{ SITE_MAIN_SYS_NAME }} reinsurance"
                                             srcset="{{ $element['site_bottom_bloc_image']['url'] }}"/>
                                    @endif
                                </div><!-- .block-header -->
                                <div class="block-body">
                                    <strong>{!! $element['site_bottom_bloc_title'] !!}</strong>
                                </div><!-- .block-body -->
                                <div class="block-footer uk-flex uk-flex-center uk-flex-middle">
                                    <p>{!! $element['site_bottom_bloc_text'] !!}</p>
                                </div><!-- .block-footer -->
                            </div><!-- .block-content -->
                        </div><!-- .block-section__block -->

                    @endforeach

                </div>

            </div><!-- .col -->
        </div><!-- .section-body -->
    </div><!-- #reinsurances -->
@else
    <div id="reinsurances" class="section container-fluid">
        <div class="section-body inner">
            <div class="col-md-8 col-sm-10 col-md-offset-2 col-sm-offset-1">

                <div class="reinsurances-container uk-flex uk-flex-between uk-flex-top">

                    <div class="block block-reinsurances__reinsurance uk">
                        <div class="block-content">
                            <div class="block-header match-1 uk-flex uk-flex-center uk-flex-middle">
                                <img src="{{ get_template_directory_uri() . '/dist/images/reinsurances__reinsurance-logistique.png' }}"
                                     width="50" height="35"
                                     class="" alt="Une logistique premium"
                                     srcset="{{ get_template_directory_uri() . '/dist/images/reinsurances__reinsurance-logistique.png' }}"/>
                            </div><!-- .block-header -->
                            <div class="block-body">
                                <strong>Une logistique<br>premium</strong>
                            </div><!-- .block-body -->
                            <div class="block-footer uk-flex uk-flex-center uk-flex-middle">
                                <p>Vos mobiliers au bon endroit<br>et au bon moment partout en France.</p>
                            </div><!-- .block-footer -->
                        </div><!-- .block-content -->
                    </div><!-- .block-section__block -->

                    <div class="block block-reinsurances__reinsurance uk">
                        <div class="block-content">
                            <div class="block-header match-1 uk-flex uk-flex-center uk-flex-middle">
                                <img src="{{ get_template_directory_uri() . '/dist/images/reinsurances__reinsurance-paiement.png' }}"
                                     width="50" height="35" class=""
                                     alt="Une logistique premium"
                                     srcset="{{ get_template_directory_uri() . '/dist/images/reinsurances__reinsurance-paiement.png' }}"/>
                            </div><!-- .block-header -->
                            <div class="block-body">
                                <strong>Paiement<br>100% sécurisé</strong>
                            </div><!-- .block-body -->
                            <div class="block-footer uk-flex uk-flex-center uk-flex-middle">
                                <img src="{{ get_template_directory_uri() . 'dist/images/reinsurances__reinsurance-payment.png' }}"
                                     width="193" height="24" class=""
                                     alt=""
                                     srcset="{{ get_template_directory_uri() . 'dist/images/reinsurances__reinsurance-payment.png' }}"/>
                            </div><!-- .block-footer -->
                        </div><!-- .block-content -->
                    </div><!-- .block-section__block -->

                    <div class="block block-reinsurances__reinsurance uk">
                        <div class="block-content">
                            <div class="block-header match-1 uk-flex uk-flex-center uk-flex-middle">
                                <img src="{{ get_template_directory_uri() . '/dist/images/reinsurances__reinsurance-service-client.png' }}"
                                     width="50" height="35"
                                     class="" alt="Une logistique premium"
                                     srcset="{{ get_template_directory_uri() . '/dist/images/reinsurances__reinsurance-service-client.png' }}"/>
                            </div><!-- .block-header -->
                            <div class="block-body">
                                <strong>Service client<br>a votre écoute</strong>
                            </div><!-- .block-body -->
                            <div class="block-footer uk-flex uk-flex-center uk-flex-middle">
                                <p>Du lundi au vendredi de 9h30 à
                                    18h30<br>au @if (function_exists('get_field'))
    {{ get_field('app_tel_customer_service', 'option') }}
@endif
 et par email. </p>
                            </div><!-- .block-footer -->
                        </div><!-- .block-content -->
                    </div><!-- .block-section__block -->

                </div>

            </div><!-- .col -->
        </div><!-- .section-body -->
    </div><!-- #reinsurances -->
@endif