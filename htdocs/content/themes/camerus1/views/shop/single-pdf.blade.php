@extends('layouts.main-pdf')

@section('og')
    @include('common.og')
@endsection

@section('content')

    <?php
    global $product;

    $prices = [];
    $metas = get_field('product_options', $product->get_id());
    $related_products = getRelatedCustomProduct($ID);
    $colors = getProductColors($ID);
    $variations = $product->get_available_variations();
    if (!empty($variations) && is_array($variations)) {
        foreach ($variations as $variation) {
            $v_id = $variation['variation_id'];
            if (array_key_exists('attributes', $variation) && array_key_exists('attribute_pa_city', $variation['attributes'])
                && $variation['attributes']['attribute_pa_city'] !== 'event'
            ) {
                $prices[] = [
                    'city'  => ucfirst($variation['attributes']['attribute_pa_city']),
                    'price' => $variation['display_price'].get_woocommerce_currency_symbol(),
                ];
            }
        }
    }
    ?>

    @loop

    <main id="main">

        <div class="pdf-notice">
            <div class="alert-info onload"><?php _e('Chargement de votre pdf en cours', THEME_TD) ?> ...</div>
            <div class="alert-success success hide"><?php _e('Votre PDF a été chargé', THEME_TD) ?></div>
        </div>

        <div id="pdf" class="section container-fluid">
            <div id="pdf-inner" class="inner" style="padding: 40px 30px; max-width: 755px; width: 755px; height:1070px">

                <div class="section-header">
                    <div class="uk-grid-small" data-uk-grid>

                        <div class="block block-pdf__logo uk-width-1-3">
                            <div class="block-content">

                                <a class="block-body" href="<?php echo home_url() ?>" title="<?php echo get_the_title($ID); ?>">
                                    <img src="<?php echo get_template_directory_uri() ?>/dist/images/Logo_Cameru-450px.png" class="skip-webp skip-lazy" width="" height="" alt=""
                                         srcset="<?php echo get_template_directory_uri() ?>/dist/images/Logo_Cameru-450px.png">
                                </a>

                            </div><!-- .block-content -->
                        </div><!-- .block-pdf__logo -->

                    <!--div class="block block-pdf__title uk-width-3-3" hidden>
                            <div class="block-content">

                                <div class="block-body">
                                    <?php _e('La fiche produit', THEME_TD) ?>
                            </div>

                        </div>
                    </div--><!-- .block-pdf__title -->

                    </div>
                </div><!-- .section-header -->
                <div class="section-body uk-grid uk-grid-small uk-child-width-1-2 uk-flex-center">

                    <div class="col-left">
                        <div class="block block-product__title">
                            <div class="block-content">
                                <div class="block-header"><?php _e('Réf', THEME_TD) ?>. <?php echo $product->get_sku(); ?></div><!-- .block-header -->
                                <h1 class="block-body product-title"><?php echo get_the_title($ID); ?></h1><!-- .block-body -->
                            </div><!-- .block-content -->
                        </div><!-- .block-product__title -->
                        <div class="block block-product__characteristics uk">
                            <form class="block-content" action="#">
                                <div class="block-body uk-grid uk-grid-small uk-flex-middle">
                                    <div class="uk-width-2-2 uk-flex uk-flex-middle">

                                    </div>
                                </div><!-- .block-body -->
                            </form><!-- .block-content -->
                        </div><!-- .block-product__characteristics -->
                        <div class="block block-product__description">
                            <div class="block-content">
                                <div class="block-body">
                                    <strong class="title"><?php _e('Description', THEME_TD) ?></strong>
                                    <div class="summary">
                                        <p>: </p>
                                        <p><?php echo $product->get_short_description(); ?></p>
                                        <p><?php echo $product->get_description(); ?></p>
                                        <p><?php _e('Existe en') ?> {{ count($colors) }} <?php _e('coloris') ?>.
                                            @if(!empty($colors) && is_array($colors))
                                                <span class="pallet">
                                                @foreach($colors as $color)
                                                        <?php $color_picker = get_field('pa_color_picker', 'pa_color_'.$color->term_id); ?>

                                                        <i style="background: {{ $color_picker }}"></i>
                                                    @endforeach
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div><!-- .block-body -->
                            </div><!-- .block-content -->
                        </div><!-- .block-product__description -->
                        <div class="block block-product__attributes uk">
                            <div class="block-content">
                                <dl class="block-body">
                                    <?php if(!empty($metas) && is_array($metas)) : ?>
                                    <?php foreach ($metas as $meta) :?>
                                    <dt><?php echo esc_attr($meta['product_options_title']) ?></dt>
                                    <dd><?php echo esc_attr($meta['product_options_desc']) ?></dd>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </dl><!-- .block-body --><!-- .block-body -->
                            </div><!-- .block-content -->
                        </div><!-- .block-product__attributes -->
                        @if(!empty($tags) && is_array($tags))

                            <div class="block block-product__labeling">
                                <div class="block-content">
                                    <ul class="block-body">
                                        @foreach($tags as $tag)
                                            <li>
                                                {!! wp_get_attachment_image($tag->tag_icon['ID'], 'full', true, ['width' =>  '17', 'height' =>  '17', 'title' => $tag->name, 'data-uk-tooltip' => null, 'class' => 'skip-webp skip-lazy']) !!}
                                            </li>
                                        @endforeach
                                    </ul><!-- .block-body -->
                                </div><!-- .block-content -->
                            </div><!-- .block-product__labeling -->

                        @endif
                    </div><!-- .col-left -->

                    <div class="col-right">

                        <div class="block block-product__slider">
                            <div class="block-content">
                                <div class="block-body slider">

                                    <figure class="img-container img-middle">
                                        <?php if (!empty($schema_img)) : ?>
                                        <?php echo wp_get_attachment_image($schema_img['ID'], 'full', false, ['width' => '306', 'height' => '239', 'class' => 'skip-webp skip-lazy']) ?>
                                        <?php endif; ?>
                                    </figure>

                                </div><!-- .block-body -->
                            </div><!-- .block-content -->
                        </div><!-- .block-product__slider -->

                    </div><!-- .col-right -->

                </div><!-- .section-body -->
                <div class="section-footer">
                    <div class="uk-grid-small uk-child-width-1-1" data-uk-grid>

                        <div class="block block-pdf__product">
                            <div class="block-content">

                                <div class="block-body uk-flex uk-grid-small">
                                    <figure class="img-container">
                                        <?php echo wp_get_attachment_image($product->get_image_id(), 'full', false, ['width' => '2177', 'height' => '2855', 'class' => 'skip-webp skip-lazy']) ?>
                                    </figure>

                                </div><!-- .block-content -->

                            </div><!-- .block-pdf__product -->

                        </div>

                        @if(!empty($related_products) && is_array($related_products))
                            <div class="block block-product__sugestions uk">
                                <div class="block-content">
                                    <p class="block-header"><?php _e('Vous aimerez aussi', THEME_TD); ?></p><!-- .block-header -->
                                    <div class="block-body uk-grid uk-grid-small uk-child-width-1-4" data-uk-grid>

                                        @foreach($related_products as $related_product)
                                            <div class="card card-suggestions__product">
                                                <div class="card-content">
                                                    <a class="card-header" href="#" title="<?php _e('Réf', THEME_TD); ?>. {{ $related_product->get_sku() }}">
                                                        <span class="ref match-1"><?php _e('Réf', THEME_TD); ?>. {{ $related_product->get_sku() }}</span>
                                                        <figure class="img-container img-middle">
                                                            <span>{!! wp_get_attachment_image($related_product->get_image_id(), 'full', true, ['width' =>  '378', 'height' =>  '400', 'title' => $related_product->get_title(), 'class' => 'skip-webp skip-lazy']) !!}</span>
                                                        </figure>
                                                    </a><!-- .card-header -->
                                                    <div class="card-body">
                                                        <span class="place">{!! $related_product->get_title() !!}</span>
                                                    </div><!-- .card-body -->
                                                </div><!-- .card-content -->
                                            </div><!-- .card-suggestions__product -->
                                        @endforeach

                                    </div><!-- .block-body -->
                                </div><!-- .block-content -->
                            </div><!-- .block-product__sugestions -->
                        @endif

                    </div>

                </div><!-- .section-footer -->

            </div>

        </div><!-- #pdf -->

    </main><!-- #main -->

    @endloop

@endsection()