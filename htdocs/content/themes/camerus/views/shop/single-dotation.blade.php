@extends('layouts.main')

@section('og')
    @include('common.og')
@endsection

@section('content')

    @php(do_action('woocommerce_before_main_content'))

    @loop

    @php(do_action('woocommerce_before_single_product'))

    <div id="reed" class="layout container-fluid block-product__list">
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
                            <h1 class="block-header">{!!  Loop::title() !!}</h1><!-- .block-header -->
                            <div class="block-body rte">
                                @if($dotation_stock < 1)
                                    <p style="font-size: 18px;"><?php _e('Le choix de la dotation n’est pas possible. Merci de vous rapprocher de nos services', THEME_TD); ?></p>
                                    <p style="font-size: 18px;"><?php _e('Veuillez', THEME_TD); ?> <a href="{{ get_permalink(91) }}"
                                                                             target="_blank">

                                                <?php _e('nous contacter ici', THEME_TD); ?></a>
                                    </p>
                                @endif
                                <p class="hide">
                                    <span><?php _e('Disponible', THEME_TD); ?> : {{ $dotation_stock }}</span></p>
                                <p><?php echo $product->get_short_description(); ?></p>
                                <p><?php echo $product->get_description(); ?></p>
                            </div><!-- .block-body -->
                            <div class="block-footer"></div><!-- .block-footer -->
                        </div><!-- .block-content -->
                    </div><!-- .block-contact__text -->

                </div>

            </div>
        </div><!-- #layout -->
    </div>

    <div id="layout" class="layout container-fluid">
        <div class="layout-body inner">

            <div class="row">
                <div class="col-sm-4 col-sm-offset-1 col-xs-8 col-xs-offset-2">
                    <div class="img-container-reeds">
                        <?php if ($product->get_image_id()): ?>
                        <figure class="img-container img-middle">
                                <?php echo wp_get_attachment_image($product->get_image_id(), 'single-product-thumbnail', false, ['width' => '460', 'height' => '460']) ?>
                        </figure>
                        <?php endif; ?>
                    </div>
                </div><!-- .col -->

                <div class="col-sm-5 col-sm-offset-1 productlist__item-collection dotation-products col-xs-12">
                    <div data-uk-spinner></div>
                    <div class="uk-grid-small dotation-pr-list" data-uk-grid>

                        @if(!empty($dotation_items) && is_array($dotation_items))
                            @foreach($dotation_items as $d_item)
                                    <?php
                                    $d = $d_item['dotation_item'];
                                    $dotation_sku = get_post_meta($d->ID, '_sku', true);
                                    $quantity = $d_item['dotation_number'];
                                    ?>

                                <div class="block block-productlist__item uk-width-1-1 uk">
                                    <a class="block-content dotation-item" href="#"
                                       data-p_id="{{ $d->ID }}"
                                       title="{{ $d->post_title }}">
                                        <div class="uk-grid uk-grid-small">

                                            <div class="block-aside uk-width-1-3">
                                                <div class="img-container img-middle">
                                                    {!! get_the_post_thumbnail($d->ID, 'thumbnail', ['width' => '69', 'height' => '91']) !!}
                                                </div>
                                            </div><!-- .block-header -->
                                            <div class="block-body uk-width-2-3">
                                                <div class="top">
                                                    <div class="left">
                                                        <h2 class="title">
                                                            {!! $d->post_title !!}
                                                        </h2>
                                                    </div>
                                                    <div class="right">
                                                        <strong>
                                                                <?php _e('Quantité', THEME_TD) ?>: X{{ $quantity }}
                                                        </strong>
                                                    </div>
                                                </div><!-- .block-body -->
                                            </div><!-- .block-body -->
                                        </div>
                                    </a><!-- .block-content -->
                                </div><!-- .block-productlist__item -->

                            @endforeach
                        @endif

                        @if(!empty($dotation_add_items) && is_array($dotation_add_items))

                            <div class="block block-rte__default uk-width-1-1 uk dotation-add-title">
                                <div class="block-content">
                                    <div class="block-body rte">
                                        <p>
                                            <strong><u><?php _e('Mes mobiliers complémentaires', THEME_TD); ?></u></strong>
                                        </p>
                                    </div><!-- .block-body -->
                                </div><!-- .block-content -->
                            </div><!-- .block-rte__default -->

                        @endif

                        @if(!empty($dotation_add_items_session) && is_array($dotation_add_items_session))
                            @foreach($dotation_add_items_session as $d_item_session)
                                <div class="block block-productlist__item uk-width-1-1 uk dt-pr-item-{{ $d_item_session['product_id'] }}">
                                    <div class="block-content dotation-item dotation-add-item"
                                         data-p_id="{{ $d_item_session['product_id'] }}">
                                        <input type="hidden"
                                               class="dotation_add_product_input"
                                               data-pr-q="{{ $d_item_session['quantity'] }}"
                                               name="dotation_added_product[]"
                                               value="{{ $d_item_session['product_id'] }}:{{ $d_item_session['quantity'] }}">
                                        <div class="uk-grid uk-grid-small">
                                            <div class="block-aside uk-width-1-3">
                                                <div class="img-container img-middle"
                                                     title="{{ get_the_title($d_item_session['product_id']) }}">
                                                    {!! get_the_post_thumbnail($d_item_session['product_id'], 'thumbnail', ['width' => '69', 'height' => '91']) !!}
                                                </div>
                                            </div><!-- .block-header -->
                                            <div class="block-body uk-width-2-3">
                                                <div class="top">
                                                    <div class="left">
                                                        <h2 class="title">
                                                            {!! get_the_title($d_item_session['product_id']) !!}
                                                        </h2>
                                                    </div>
                                                    <div class="right">
                                                        <strong>
                                                                <?php _e('Quantité', THEME_TD); ?>:
                                                            X{{ $d_item_session['quantity'] }}
                                                        </strong>
                                                    </div>
                                                </div><!-- .block-body -->
                                                <div class="bottom">
                                                    <div>
                                                    </div>
                                                    <div>
                                                        <div class="cta-container">
                                                            <button type="button" style="z-index: 500;"
                                                                    class="btn btn-c_line btn-bdc_line btn-tt_u btn-remove dt-btn-remove"
                                                                    data-prid="{{ $d_item_session['product_id'] }}">
                                                                <span class="visible-xs">x</span>
                                                                <span class="hidden-xs"><?php _e('Supprimer', THEME_TD); ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .block-body -->
                                        </div>
                                    </div><!-- .block-content -->
                                </div>
                            @endforeach
                        @endif

                    </div>

                </div>

                @if(!empty($dotation_add_items) && is_array($dotation_add_items))

                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">

                            <div class="uk-grid-small uk-flex-center" data-uk-grid>
                                <div class="block block-rte__default">
                                    <div class="block-content">
                                        <div class="block-body rte uk-text-center">
                                            {!! $dotation_add_text !!}
                                        </div><!-- .block-body -->
                                    </div><!-- .block-content -->
                                </div><!-- .block-rte__default -->
                            </div>

                            <div class="uk-grid-small block-product__list dotation-item-comp" data-uk-grid>
                                <div uk-spinner></div>
                                <!-- blocks -->

                                <input type="hidden" id="dotation_add_limit" value="{{ $dotation_add_limit }}">
                                <input type="hidden" id="dotation_id" value="{{ $product->get_id() }}">

                                <div class="block block-rte__default uk-width-1-1">
                                    <div class="block-content">
                                        <div class="block-body rte">
                                            <p>
                                                <strong><?php _e('Les mobiliers proposés en compléments', THEME_TD) ?></strong>
                                            </p>
                                        </div><!-- .block-body -->
                                    </div><!-- .block-content -->
                                </div><!-- .block-rte__default -->


                                @foreach($dotation_add_items as $d_item)
                                        <?php
                                        $d_post = $d_item['dotation_add_item'];
                                        $dotation_item = wc_get_product($d_post->ID);
                                        $terms = get_the_terms($d_post->ID, 'product_cat');
                                        $product_cat = '';
                                        foreach ($terms as $term) {
                                            $product_cat = $term->name;
                                            break;
                                        }
                                        ?>
                                    <div class="block block-product__link uk-width-1-4@m uk-width-1-2@s dt-product-{{ $d_post->ID }}">
                                        <div class="block-content">
                                            <a class="block-header" href="#"
                                               title="<?php _e('Réf', THEME_TD); ?>. {{ $dotation_item->get_sku() }}">
                                                <span class="ref match-1"><?php _e('Réf', THEME_TD); ?>. {{ $dotation_item->get_sku() }} -
                                                    <span class="category">{{ $product_cat }}</span></span>
                                                <figure class="img-container img-middle">
                                                    {!! get_the_post_thumbnail($dotation_item->get_id(), 'medium', ['width' => '378', 'height' => '400']) !!}
                                                </figure>
                                            </a><!-- .block-header -->
                                            <div class="block-body">
                                                <h3 class="title">{!! $dotation_item->get_title() !!}</h3>
                                            </div><!-- .block-body -->
                                            <div class="block-footer">
                                                <div class="num-spinner uk-flex">
                                                      <span class="btn-container uk-flex uk-flex-column">
                                                        <button onclick="ui.ns.increment('+',this)" type="button"
                                                                class="btn">
                                                            <i class="icon icon-product-arrow-up"></i>
                                                        </button>
                                                        <button onclick="ui.ns.increment('-',this)" type="button"
                                                                class="btn">
                                                            <i class="icon icon-product-arrow-down"></i>
                                                        </button>
                                                      </span>
                                                    <input type="number"
                                                           class="dt-quantity dt-quantity-{{ $d_post->ID }}" value="1">
                                                </div>
                                                <button class="btn dt-btn-add"
                                                        data-dtp-title="{{ $dotation_item->get_title() }}"
                                                        data-dtp-img="{{ get_the_post_thumbnail_url($dotation_item->get_id(), 'thumbnail') }}"
                                                        data-dtp="dt-product-{{ $d_post->ID }}"
                                                        data-dtq="dt-quantity-{{ $d_post->ID }}"
                                                        data-dtid="{{ $d_post->ID }}">
                                                    <span><?php _e('Ajouter a ma dotation', THEME_TD); ?></span>
                                                </button>
                                            </div><!-- .block-footer -->
                                        </div><!-- .block-content -->
                                    </div><!-- .block-product__link -->
                                @endforeach

                                <!-- end: blocks -->
                            </div>
                        </div>
                    </div>

                @endif

                <div class="row uk-margin-medium-bottom">

                    <div class="col-sm-10 col-sm-offset-1">

                        <div class="uk-grid uk-flex-between@m uk-flex-center@s uk-flex-center">
                            <div class="uk-margin-small-bottom">
                                @if(cmrs_find_product_in_cart($product->get_id()) > 0)
                                    <a href="{{ get_permalink( wc_get_page_id( 'shop' ) ) }}"
                                       class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-add"><?php _e('Accéder à notre catalogue', THEME_TD); ?></a>
                                @endif
                            </div>
                            <div class="uk-margin-small-bottom">
                                @if(cmrs_find_product_in_cart($product->get_id()) > 0)
                                    <a href="{{ get_permalink( wc_get_page_id( 'cart' ) )}}"
                                       class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-add"><?php _e('Continuer vers mon panier', THEME_TD); ?></a>
                                @else
                                    <a href="{{ $product->add_to_cart_url() }}"
                                       @if($dotation_stock < 1) title="<?php _e('Stock épuiser', THEME_TD) ?>"
                                       disabled="disabled" style="pointer-events: none"
                                       @endif
                                       class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-add">
                                        @if($dotation_stock < 1)
                                                <?php _e('Rupture de stock', THEME_TD); ?>
                                        @else
                                                <?php _e('Confirmer ma selection', THEME_TD); ?>
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>


            </div><!-- .layout-body -->
        </div><!-- #layout -->

        @php(do_action('woocommerce_after_single_product'))
    </div>

    @endloop

    @include('components.page.footer-social')

    @php(do_action('woocommerce_after_main_content'))

@endsection()