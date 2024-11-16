@extends('layouts.main')

@section('og')
    @include('common.og')
@endsection

@section('content')

    @php(do_action('woocommerce_before_main_content'))

    @include('shop.global.breadcrumb')

    @loop

    @php(do_action('woocommerce_before_single_product'))

    <div id="product" {{ wc_product_class('section container-fluid') }}>
        <div class="section-body inner">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">

                    <div class="uk-grid-small" data-uk-grid>

                        <div class="col-left uk-width-3-5@m">

                            <div class="uk-grid-small" data-uk-grid>
                                <div class="uk-width-5-6@m uk-width-3-4@s">

                                    <div class="block block-product__menu">
                                        <div class="block-content">
                                            <ul class="block-body">
                                                @if(!empty($pdf_file))
                                                    <li>
                                                        <a href="{{ $pdf_file }}"
                                                           title="<?php _e('Téléchargement Fiche produit PDF') ?>"
                                                           data-uk-tooltip="title:Téléchargement<br> Fiche produit PDF;pos:right"
                                                           rel="nofollow"
                                                           target="_blank">
                                                            <i class="icon icon-product-down-pdf"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(!empty($three_d_file) && isset($three_d_file['url']))
                                                    <li>
                                                        <a href="{{ $three_d_file['url'] }}"
                                                           title="<?php _e('Téléchargement Modèle 3D', THEME_TD) ?>"
                                                           data-uk-tooltip="title:Téléchargement<br>Modèle 3D;pos:right"
                                                           download>
                                                            <i class="icon icon-product-down-3d"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(!empty($three_d_link))
                                                    <li>
                                                        <a href="#modal-sketch" data-uk-toggle
                                                           data-src="{{ $three_d_link }}"
                                                           title="<?php _e('Vue 3D', THEME_TD) ?>"
                                                           data-uk-tooltip="title:<?php _e('Vue 3D', THEME_TD) ?>;pos:right">
                                                            <i class="icon icon-product-3d"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul><!-- .block-body -->
                                        </div><!-- .block-content -->
                                    </div><!-- .block-product__menu -->

                                    @php(do_action('woocommerce_before_single_product_summary'))

                                    @if(!empty($tags) && is_array($tags))

                                        <div class="block block-product__labeling">
                                            <div class="block-content">
                                                <ul class="block-body">
                                                    @foreach($tags as $tag)
                                                        <li>
                                                            {!! wp_get_attachment_image($tag->tag_icon['ID'], 'full', true, ['width' =>  '17', 'height' =>  '17', 'title' => $tag->name, 'data-uk-tooltip' => null]) !!}
                                                        </li>
                                                    @endforeach
                                                </ul><!-- .block-body -->
                                            </div><!-- .block-content -->
                                        </div><!-- .block-product__labeling -->

                                    @endif

                                </div>
                                <div class="uk-width-1-6@m uk-width-1-4@s">


                                    <div class="block block-product__nav">
                                        <div class="block-content">
                                            <div class="block-body slider">

                                                <?php do_action('woocommerce_product_thumbnails'); ?>

                                            </div><!-- .block-body -->

                                            @if(!empty($schema_img) && isset($schema_img['url']))
                                                <div class="show-in-lightbox-schema">
                                                    <a href="{{ $schema_img['url'] }}" class="schema-block">
                                                        <figure class="img-container img-middle">
                                                            <?php echo wp_get_attachment_image($schema_img['ID'], 'thumbnail', false, ['width' => '2177', 'height' => '2855']) ?>
                                                        </figure>
                                                    </a>
                                                </div>
                                            @endif

                                        </div><!-- .block-content -->
                                    </div><!-- .block-product__nav -->


                                </div>
                            </div>

                        </div>
                        <div class="col-right uk-width-2-5@m">

                            <div class="uk-grid-small" data-uk-grid>
                                <div class="uk-width-3-4@m">

                                    @php(do_action('woocommerce_single_product_summary'))

                                </div>
                            </div>

                        </div>

                    </div>

                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .section-body -->
    </div><!-- #product -->

    <div id="layout" class="layout container-fluid">
        <div class="layout-body inner">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">

                    <div class="uk-grid-small" data-uk-grid>

                        <!-- blocks -->

                        <div class="block block-product__sugestions uk-width-3-5@m uk-flex-last@s uk-flex-first@m">

                            @php(do_action('woocommerce_after_single_product_summary'))

                        </div><!-- .block-product__sugestions -->

                        @if(!empty($gallery_expo && is_array($gallery_expo)))
                            <div class="block block-product__slideshow uk-width-2-5@m uk-flex-first@s uk-flex-last@m">
                                <div class="row">

                                    <div class="col-md-9 col-md-offset-3">

                                        <div class="block-content" data-uk-slideshow="ratio:284:173">
                                            <div class="block-header"></div><!-- .block-header -->
                                            <div class="block-body uk-slideshow-items show-in-lightbox-slide">

                                                @foreach($gallery_expo as $ge)
                                                    @if(isset($ge['url']))
                                                        <a class="" href="{{ $ge['url'] }}">
                                                            {!! wp_get_attachment_image($ge['ID'], 'full', false, ['class' => 'uk-cover', 'width' => '1632', 'height' => '1224']) !!}
                                                        </a>
                                                    @endif
                                                @endforeach

                                            </div><!-- .block-body -->
                                            <div class="block-footer">
                                                <button class="btn" data-uk-slideshow-item="previous" rel="nofollow">
                                                    <i class="icon icon-product-arrow-left"></i>
                                                </button>
                                                <button class="btn" data-uk-slideshow-item="next" rel="nofollow">
                                                    <i class="icon icon-product-arrow-right"></i>
                                                </button>
                                            </div><!-- .block-footer -->
                                        </div><!-- .block-content -->

                                    </div><!-- .col -->

                                </div>
                            </div><!-- .block-product__slideshow uk-width- -->
                            <!-- end: blocks -->
                        @endif

                    </div>

                </div><!-- .col -->
            </div>
        </div><!-- .layout-body -->
    </div><!-- #layout -->

    @php(do_action('woocommerce_after_single_product'))

    @endloop

    @include('components.page.footer-social')

    @php(do_action('woocommerce_after_main_content'))

@endsection()