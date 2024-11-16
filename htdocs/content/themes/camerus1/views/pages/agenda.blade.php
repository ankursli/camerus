@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')

    <main id="main">
        <div data-uk-spinner="ratio: 2"></div>

        <div id="heading" class="section container-fluid hidden-xs">
            <div class="section-body">

                <div class="block block-banner__featured">
                    <div class="block-content">
                        <div class="block-header inner">

                            <div class="row">
                                <div class="col-xs-10 col-xs-offset-1">
                                    <h1 class="title hide"><?php _e('L’agenda des salons', THEME_TD) ?></h1>
                                </div><!-- .col -->
                            </div><!-- .row -->

                        </div><!-- .block-header -->
                        <div class="block-body">
                            <img src="{{ get_the_post_thumbnail_url(get_the_ID(), 'full') }}"
                                 width="" height="" class="" alt=""
                                 srcset="{{ get_the_post_thumbnail_url(get_the_ID(), 'full') }}"
                                 data-uk-parallax="y:+1200"
                            />
                        </div><!-- .block-body -->
                        <div class="block-footer"></div><!-- .block-footer -->
                    </div><!-- .block-content -->
                </div><!-- .block-banner__featured -->

            </div><!-- .section-body -->
        </div><!-- #heading -->

        <div id="calendar" class="section container-fluid">
            <div uk-spinner></div>
            <div class="section-header">

                @include('components.agenda.form-calendar')

            </div><!-- .section-header -->
            <div class="section-body">

                <div class="inner">
                    <div class="row">

                        <div class="col-sm-10 col-sm-offset-1">

                            <div class="block block-calendar__slider uk">
                                <div class="block-agenda-listing block-container uk-position-relative uk trs-n"
                                     data-uk-height-match=".ticket .ticket-body">

                                    @include('components.agenda.ticket-calendar')

                                    <div class="block-footer">
                                        <div class="circle">
                                            <strong><?php _e('DRAG', THEME_TD) ?></strong>
                                            <div class="icons">
                                                <i class="icon icon-btn-arrow-left"></i>
                                                <i class="icon icon-btn-arrow-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .block-calendar__slider -->

                        </div><!-- .col -->

                    </div>
                </div><!-- .inner -->

            </div><!-- .section-body -->
            <div class="section-footer uk-switcher">

                @include('components.agenda.block-calendar-list')

            </div><!-- .section-footer -->
        </div><!-- #calendar -->

        @include('components.page.reinsurances')

        @include('components.page.footer-social')

    </main><!-- #main -->

@endsection
