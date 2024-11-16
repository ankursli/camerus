@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')
    @loop
    <main id="main">
        <div data-uk-spinner="ratio: 2"></div>

        @include('shop.global.breadcrumb')

        <div id="layout" class="layout container-fluid">
            <div class="layout-body inner">
                <div class="row">

                <!--===========menu proccess============= -->
                @include('common.shop.process-menu-checkout')
                <!--===========menu proccess============= -->

                <!-- ======================== CHEKOUT PROCESS ======================== -->
                @include('shop.checkout.form-checkout')
                <!-- ========================= CHEKOUT PROCESS ======================== -->

                <!--===========menu proccess============= -->
                @include('common.shop.cart.cart-sidebar')
                <!--===========menu proccess============= -->

                </div><!-- .row -->
            </div><!-- .layout-body -->
        </div><!-- #layout -->

        @include('components.page.reinsurances')

        @include('components.page.footer-social')

    </main><!-- #main -->
    @endloop
@endsection

