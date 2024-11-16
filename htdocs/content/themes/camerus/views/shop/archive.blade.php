@extends('layouts.main')

@section('og')
    @include('common.og')
@endsection

@section('content')

    <h1 class="hide">{{ woocommerce_page_title() }}</h1>

    @php(do_action('woocommerce_before_main_content'))

    @include('shop.global.breadcrumb')

    @if(!empty(getEventSalonCitySlugInSession()))
        @if(str_contains(url()->current(), '/showroom/'))
            @include('shop.room.event-style-banner')
        @endif
        @if(str_contains(url()->current(), '/styleroom/'))
            @include('shop.room.event-style-product-banner')
        @endif
    @endif

    <div id="layout" class="layout container-fluid">
        <div class="layout-body inner">
            <div class="row">


                <aside class="col-sm-3 col-sm-offset-1">
                    <!-- blocks -->

                    <div class="uk-grid-small product-sidebar-list" data-uk-grid>

                        @if(function_exists('dynamic_sidebar'))
                            @php(dynamic_sidebar('sidebar-shop'))
                        @endif

                    </div>

                    <!-- end: blocks -->
                </aside>

                <div class="col-lg-6 col-md-7 col-sm-8">

                    <div class="uk-grid-small block-product__list product-loop" data-uk-grid>
                        <div uk-spinner></div>
                        <!-- blocks -->

                        @if(woocommerce_product_loop())
                            @php(do_action('woocommerce_before_shop_loop'))

                            {!! woocommerce_product_loop_start(false) !!}

                            @if(wc_get_loop_prop('total'))

                                @while(have_posts())

                                    @php(the_post())
                                    @php(do_action('woocommerce_shop_loop'))

                                    @if(!empty(getEventSalonCitySlugInSession()))
                                        @include('shop.content-product_filtered')
                                    @else
                                        @php(wc_get_template_part('content', 'product'))
                                    @endif

                                @endwhile

                            @endif

                            {!! woocommerce_product_loop_end(false) !!}

                            @php(do_action('woocommerce_after_shop_loop'))
                        @else
                            @php(do_action('woocommerce_no_products_found'))
                        @endif

                        <div class="archive-desc mb-5">
                            <?php if (is_tax()) : ?>
                                <?php the_archive_description('<div class="taxonomy-description">', '</div>'); ?>
                            <?php else : ?>
                            <div class="taxonomy-description">
                                    <?php get_post_field('post_excerpt', get_the_ID()); ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- end: blocks -->
                    </div>


                </div><!-- .col -->

            </div>
        </div><!-- .layout-body -->
    </div><!-- #layout -->

    @include('components.page.footer-social')

    @php(do_action('woocommerce_after_main_content'))

@endsection