@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')

    <main id="main">
        <div data-uk-spinner="ratio: 2"></div>

        <div id="reed" class="layout container-fluid">
            <div class="layout-body inner">
                <div class="row">

                    <aside class="col-sm-3 col-sm-offset-1 dotation-side-page">
                        <!-- blocks -->

                        <div class="uk-grid-small" data-uk-grid>

                            @include('widgets.product-salon')

                        </div>

                        <!-- end: blocks -->
                    </aside>

                    <div class="col-sm-6 col-sm-offset-1">
                        <div class="block block-contact__text">
                            <div class="block-content">
                                <h1 class="block-header"><?php _e('Sélectionner votre dotation', THEME_TD); ?></h1>
                                <!-- .block-header -->
                                <div class="block-footer" style="color: #ff560d; font-weight: 700; font-size: 16px;">
                                    <p>{!! $reed_info->Prenom !!} {!! $reed_info->Nom !!}</p>
                                    <p>{!! $reed_info->RaisonSociale !!}</p>
                                    <p>{!! $reed_info->NumStand !!}</p>
                                </div><!-- .block-footer -->
                            </div><!-- .block-content -->
                        </div><!-- .block-contact__text -->

                        <div class="block-content">
                            <div class="block-body">
                                @if($reed_token_used)
                                    <div class="alert-danger"><?php _e("Votre lien a déjà été utilisé pour une commande de dotation", THEME_TD); ?></div>
                                @else
                                    @if(empty($surface))
                                        <div class="alert-danger"><?php _e('Le choix de la dotation n’est pas possible sans le lien depuis la plateforme Reed',
                                                THEME_TD); ?></div>
                                    @else
                                        @if($salon_over_start)
                                            <div class="alert-warning"><?php _e('Le choix de la dotation n’est plus possible. La date de ce salon est déja dépassée',
                                                    THEME_TD); ?></div>
                                        @else
                                            @if($salon_over_limit)
                                                <div class="alert-info"> <?php _e('Le choix de la dotation n’est plus possible. Une dotation standard vous sera livrée sur votre stand',
                                                        THEME_TD); ?></div>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            </div><!-- .block-body -->
                        </div><!-- .block-content -->
                    </div>

                </div>
            </div><!-- #layout -->
        </div>

        <div id="layout" class="layout container-fluid">
            <div class="layout-body inner">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">

                        <div class="uk-grid-small block-product__list uk-margin-large-bottom" data-uk-grid>
                            <div uk-spinner></div>
                            <!-- blocks -->

                            @if(!$reed_token_used && !$salon_over_start && !$salon_over_limit)
                                @if(!empty($dotations) && is_array($dotations) && !empty($surface))
                                    @foreach($dotations as $dotation)
                                        <?php
                                        $product_dotation = wc_get_product($dotation->ID);
                                        ?>
                                        <div class="block block-product__link uk-width-1-3@m uk-width-1-2@s">
                                            <div class="block-content">
                                                <a class="block-header" href="{{ getDotationSingleProductUrl($dotation->ID) }}"
                                                   title="<?php _e('Réf', THEME_TD) ?>. {{
                                            $product_dotation->get_sku() }}">
                                                    <figure class="img-container img-middle">
                                                        {!! get_the_post_thumbnail($dotation->ID, 'full', ['width' => '378', 'height' => '400']) !!}
                                                    </figure>
                                                </a><!-- .block-header -->
                                                <div class="block-body">
                                                    <h3 class="title">{!! $dotation->post_title !!}</h3>
                                                </div><!-- .block-body -->
                                            </div><!-- .block-content -->
                                        </div><!-- .block-product__link -->
                                    @endforeach
                                @else
                                    <div class="block block-notifications uk-margin-large-top">
                                        <div class="block-content">
                                            <div class="block-body">
                                                <div class="alert-warning"> <?php _e('Les dotations ne sont pas disponibles pour la selection',
                                                        THEME_TD); ?></div>
                                            </div><!-- .block-body -->
                                        </div><!-- .block-content -->
                                    </div><!-- .block-notifications -->
                            @endif
                        @endif

                        <!-- end: blocks -->
                        </div>

                    </div><!-- .col -->
                </div>
            </div><!-- .layout-body -->
        </div><!-- #layout -->

        @include('components.page.footer-social')

    </main>

@endsection
