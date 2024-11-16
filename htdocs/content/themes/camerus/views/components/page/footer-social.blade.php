<?php
   $social_list=array();
if(function_exists('get_field'))
{
    $social_list = get_field('app_social_list', 'option');
}
?>

@if(!($social_list))
    <div id="social" class="section container-fluid">
        <div class="section-body inner">
            <div class="row">

                <div class="col-sm-10 col-sm-offset-1">

                    <div class="block block-social__links">
                        <div class="block-content">

                            <ul class="block-body">
                                @foreach($social_list as $social)
                                    <li>
                                        @if(!empty($social['link']))
                                            <a href="{{ $social['link']['url'] }}"
                                               target="{{ $social['link']['target'] }}"
                                               title="{{ $social['link']['title'] }}">
                                                @if(!empty($social['type']))
                                                    @switch($social['type'])
                                                        @case('facebook')
                                                        <i class="icon icon-social-facebook"></i>
                                                        @break
                                                        @case('twitter')
                                                        <i class="icon icon-social-twitter"></i>
                                                        @break
                                                        @case('pinterest')
                                                        <i class="icon icon-social-pinterest"></i>
                                                        @break
                                                        @case('instagram')
                                                        <i class="icon icon-social-instagram"></i>
                                                        @break
                                                        @case('linkedin')
                                                        <i class="icon icon-social-linkedin"></i>
                                                        @break
                                                        @case('youtube')
                                                        <i class="icon icon-social-youtube"></i>
                                                        @break
                                                        @case('vimeo')
                                                        <i class="icon icon-social-vimeo"></i>
                                                        @break
                                                        @case('whatsapp')
                                                        <i class="icon icon-social-whatsapp"></i>
                                                        @break
                                                        @case('flickr')
                                                        <i class="icon icon-social-flickr"></i>
                                                        @break
                                                        @case('tumblr')
                                                        <i class="icon icon-social-tumblr"></i>
                                                        @break
                                                        @case('skype')
                                                        <i class="icon icon-social-skype"></i>
                                                        @break
                                                    @endswitch
                                                @endif
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul><!-- .block-body -->

                        </div><!-- .block-content -->
                    </div><!-- .block-social__links -->

                </div><!-- .col -->

            </div>
        </div><!-- .section-body -->
    </div><!-- #social -->
@endif