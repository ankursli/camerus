<?php
$logo_member = get_field('site_member_logo', 'option');
?>
<footer id="footer">

    <div id="foot" class="section container-fluid">
        <div class="section-body inner hidden-xs">

            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">

                    <div class="uk-grid-small" data-uk-grid>

                        <div class="col uk-width-1-5">

                            <div class="block block-foot__menu">
                                <div class="block-content">

                                    {!! do_action('wp_site_footer_menu_1') !!}

                                </div><!-- .block-content -->
                            </div><!-- .block-foot__menu -->

                        </div><!-- .col -->

                        <div class="col uk-width-3-5">

                            {!! do_action('wp_site_footer_menu_2') !!}

                        </div><!-- .col -->

                        <div class="col uk-width-1-5">

                            <div class="block block-foot__logo">
                                <a class="block-content" href="{{ home_url() }}" title="{{ bloginfo('description') }}">
                                    <div class="block-body">
                                        <img src="{{ get_template_directory_uri() . '/dist/images/footer-logo.png' }}"
                                             width="142" height="31" class="" alt="{{ bloginfo('description') }}"/>
                                    </div><!-- .block-body -->
                                </a><!-- .block-content -->
                            </div><!-- .block-foot__logo -->

                            @if(!empty($logo_member) && is_array($logo_member))
                                <div class="block block-foot__members">
                                    <div class="block-content">
                                        <strong class="block-header"><?php _e('Membre', THEME_TD) ?></strong>
                                        <!-- .block-header -->
                                        <ul class="block-body">
                                            @foreach($logo_member as $member)
                                                @if(array_key_exists('site_member_logo_image', $member))
                                                    <li>
                                                        <a href="{{ !empty($member['site_member_logo_link']) ? $member['site_member_logo_link'] : '#' }}">
                                                            <img src="{{ wp_get_attachment_url($member['site_member_logo_image']['ID'], 'full') }}"
                                                                 width="99" height="33" class="" alt="member camerus"
                                                                 srcset="{{ wp_get_attachment_url($member['site_member_logo_image']['ID'], 'full') }}"/>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul><!-- .block-body -->
                                    </div><!-- .block-content -->
                                </div><!-- .block-foot__members -->
                            @endif

                            <div class="block block-foot__address">
                                <div class="block-content">
                                    <address class="block-body">
                                        {!! get_field('app_address', 'option') !!}<br>
                                        Tel : {{ get_field('app_tel', 'option') }}<br>
                                        Fax : {{ get_field('app_fax', 'option') }}<br>
                                    </address><!-- .block-body -->
                                </div><!-- .block-content -->
                            </div><!-- .block-foot__address -->

                        </div><!-- .col -->

                    </div>

                </div><!-- .col -->
            </div>

        </div><!-- .section-body -->
        <div class="section-footer inner">

            <div class="block block-foot__copyright">
                <div class="block-content">
                    <div class="block-body text-center">
                        <h3 style="font-size: 12px; color: #9d9d9d; margin-bottom: 20px;">
                            {!! get_field('app_footer_copyright', 'option') ?? sprintf(esc_html__('© Tous droits réservés '.SITE_MAIN_SYS_NAME.' %1$s', THEME_TD), date('Y')) !!}
                        </h3>
                    </div><!-- .block-body -->
                </div><!-- .block-content -->
            </div><!-- .block-foot__copyright -->

        </div><!-- .section-footer -->

    </div><!-- #foot -->

    <div class="scroller">
        <button type="submit" class="btn btn-bgc_1 btn-c_w btn-mih_32 btn-scroller">
            <span class="icon icon-btn-arrow-right"></span>
        </button>
    </div>

</footer><!-- #footer -->