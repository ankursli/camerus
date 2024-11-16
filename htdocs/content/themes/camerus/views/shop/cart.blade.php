@extends('layouts.main')

@section('og')

  @include('common.og')

@endsection

@section('content')
  @loop

  <style>
    .uk-grid-small .woocommerce.uk-first-column {
      width: 100%;
    }
  </style>

  <main id="main">
    <div data-uk-spinner="ratio: 2"></div>

    @include('shop.global.breadcrumb')

    <div id="layout" class="layout container-fluid">
      <div class="layout-body inner">
        <div class="row">

          @include('common.shop.process-menu-checkout')

          <div class="col-sm-6">

            <div class="uk-grid-small" data-uk-grid>
              <!-- blocks -->

                <?php $current_salon = getEventSalonObjectInSession(); ?>

              @if(!empty($current_salon) && !isEventSalonSession())
                <div class="block block-rte__default uk-width-1-1">
                  <div class="block-content">
                    <div class="block-body rte">
                      <p><?php _e('Veuillez trouver ci-dessous votre panier pour', THEME_TD) ?>
                        <em class="event-name"> {!! $current_salon->post_title !!}</em>
                      </p>
                    </div><!-- .block-body -->
                  </div><!-- .block-content -->
                </div><!-- .block-rte__default -->
              @endif

              {!! Loop::content() !!}

              <div class="block block-spacer uk-width-1-1 uk hidden-xs">
                <div class="block-content">
                  <div class="block-body"></div><!-- .block-body -->
                </div><!-- .block-content -->
              </div><!-- .block-spacer -->

              <!-- end: blocks -->


            </div><!-- .col -->

          </div>

          @include('common.shop.cart.cart-sidebar')

        </div><!-- .row -->
      </div><!-- .layout-body -->
    </div><!-- #layout -->

    @include('components.page.reinsurances')

    @include('components.page.footer-social')

  </main><!-- #main -->
  @endloop
@endsection
