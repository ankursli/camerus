@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')
    <?php global $product; ?>

    <main id="main" {!! post_class() !!}>

        <div id="primary" class="section container-fluid">
            <div class="section-body inner">

                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">

                        <div class="block block-section__breadcrumb uk">
                            <div class="block-content">
                                <ul class="block-body uk-breadcrumb">
                                    <li>
                                        <a href="{{ home_url() }}" title="<?php _e('Accueil', THEME_TD) ?>"><?php _e('Accueil', THEME_TD) ?></a>
                                    </li>
                                    <li>
                                        <span><?php _e('Recherche produit', THEME_TD) ?></span>
                                    </li>
                                </ul><!-- .block-body -->
                            </div><!-- .block-content -->
                        </div><!-- .block-section__breadcrumb -->

                    </div><!-- .col -->
                </div><!-- .row -->

            </div><!-- .section-body -->
        </div><!-- #primary -->

        <div id="layout" class="layout container-fluid search-product-list">
            <div class="layout-body inner">
                <div class="row">


                    <aside class="col-sm-3 col-sm-offset-1">
                        <!-- blocks -->

                        <div class="uk-grid-small product-sidebar-list" data-uk-grid>

                            @include('widgets.product-salon', ['term_salon'=> $salon, 'view_link' => true])

                        </div>

                        <!-- end: blocks -->
                    </aside>

                    <div class="col-sm-7 custom-palette-arrangement">

                        <div class="card card-tools__search">

                            @include('common.product-search-form')

                        </div><!-- .card-tools__search -->

                        @if(!empty($orderby_items))
                            <div class="block block-product__filter uk-width-1-1">
                                <div class="block-content">
                                    <form id="custom-product-ordering-form" class="block-body" method="POST" action="{{ $search_url }}">
                                        @csrf
                                        <input type="hidden" name="old-query" value="1">
                                        <div class="form-group">
                                            <label for="product__filter-order"><?php _e('Trier par', THEME_TD); ?> </label>
                                            <select name="orderby" id="product__filter-order" class="select"
                                                    onchange="document.getElementById('custom-product-ordering-form').submit();">
                                                @foreach($orderby_items as $value => $title)
                                                    @if(!empty($orderby) && $orderby == $value)
                                                        <option value="{{ $value }}" selected>{!! $title !!}</option>
                                                    @else
                                                        <option value="{{ $value }}">{!! $title !!}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </form><!-- .block-body -->
                                </div><!-- .block-content -->
                            </div><!-- .block-product__filter -->
                        @endif

                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">

                        <input type="hidden" id="cmrs-search-prev-url" value="{{ $search_url }}">

                        <div class="uk-grid-small block-product__list" data-uk-grid>
                            <div uk-spinner></div>
                            <!-- blocks -->

                        @if(!empty($products))
                            @foreach($products as $product)
                                @include('shop.content-product_search', ['the_product' => $product])
                            @endforeach
                        @else
                            @php(do_action('woocommerce_no_products_found'))
                        @endif

                        @include('common.pagination-ajax')

                        <!-- end: blocks -->
                        </div>


                    </div><!-- .col -->
                </div>
            </div><!-- .layout-body -->
        </div><!-- #layout -->

        @include('components.page.footer-social')

    </main>

@endsection