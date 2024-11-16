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
                        <div class="block-container uk-position-relative uk trs-n"
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

    <div class="section-footer uk-switcher"></div>
</div><!-- #calendar -->

<div class="col-sm-12 home-btn-event"></div>