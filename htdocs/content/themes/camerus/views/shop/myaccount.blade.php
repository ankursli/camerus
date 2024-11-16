@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')
    @loop
    <main id="main">

        @include('shop.global.breadcrumb')

        <div id="layout" class="layout container-fluid">
            <div class="layout-body inner">
                <div class="row">

                    {!! Loop::content() !!}

                </div><!-- .row -->
            </div><!-- .layout-body -->
        </div><!-- #layout -->

        @include('components.page.reinsurances')
        @include('components.page.footer-social')
    </main>
    @endloop
@endsection