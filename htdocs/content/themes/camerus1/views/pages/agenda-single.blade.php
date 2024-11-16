@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')

    @loop

    <?php $the_salon = reset($salons); ?>

    <main id="main">
        <div data-uk-spinner="ratio: 2"></div>

        <div id="heading" class="section container-fluid hidden-xs">
            <div class="section-body">

                <div class="block block-banner__featured">
                    <div class="block-content">
                        <div class="block-header inner">

                            <div class="row">
                                <div class="col-xs-10 col-xs-offset-1">
                                    <h1 class="title hide"><?php _e('Salon : ', THEME_TD) ?><b>{{ Loop::title() }}</b></h1>
                                </div><!-- .col -->
                            </div><!-- .row -->

                        </div><!-- .block-header -->
                        <div class="block-body">
                            @if(!empty($the_salon))
                                <img src="{{ $the_salon->banner_img }}"
                                     width="" height="" class="" alt=""
                                     srcset="{{ $the_salon->banner_img }}"
                                     data-uk-parallax="y:+1200"/>
                            @endif
                        </div><!-- .block-body -->
                        <div class="block-footer"></div><!-- .block-footer -->
                    </div><!-- .block-content -->
                </div><!-- .block-banner__featured -->

            </div><!-- .section-body -->
        </div><!-- #heading -->

        <div id="calendar" class="section container-fluid">
            <div class="section-header"></div><!-- .section-header -->
            <div class="section-body"></div><!-- .section-body -->
            <div class="section-footer">

                @include('components.agenda.block-calendar-list')

            </div><!-- .section-footer -->
        </div><!-- #calendar -->

        @include('components.page.reinsurances')

        @include('components.page.footer-social')

    </main><!-- #main -->

    @endloop

@endsection
