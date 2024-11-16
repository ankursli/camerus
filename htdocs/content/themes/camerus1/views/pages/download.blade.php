@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')

    <?php
    $term = get_queried_object();
    $download_page_id = getPageIDByTemplateName('download-template', true);
    ?>

    <main id="main" {!! post_class() !!}>

        {!! do_action('breadcrumb_navigation') !!}

        <div id="heading" class="layout container-fluid">
            <div class="layout-body inner">
                <div class="row">

                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="uk-grid-small" data-uk-grid>

                            <div class="block block-content__title uk-width-1-1">
                                <div class="block-content">
                                    <h1 class="block-header"><?php echo $page_title; ?></h1></h1><!-- .block-header -->
                                </div><!-- .block-content -->
                            </div><!-- .block-content__title -->

                        </div>
                    </div><!-- .col -->

                </div>
            </div><!-- .layout-body -->
        </div><!-- #heading -->

        <div id="banner" class="section container-fluid">
            <div class="section-header"></div><!-- .section-header -->
            <div class="section-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="block block-banner__featured">
                            <div class="block-content">
                                <div class="block-body">
                                    <img width="1200" height="354"
                                         src="{{ get_the_post_thumbnail_url($download_page_id, 'full') }}"
                                         alt="{{ get_the_title(get_the_ID()) }}" data-uk-parallax="y:+1200" style="transform: translateY(0px);">
                                </div><!-- .block-body -->
                                <div class="block-footer"></div><!-- .block-footer -->
                            </div><!-- .block-content -->
                        </div><!-- .block-banner__featured -->

                    </div><!-- .col -->
                </div><!-- .row -->


            </div><!-- .section-body -->
            <div class="section-footer"></div><!-- .section-footer -->
        </div><!-- #banner -->

        <div id="layout" class="layout container-fluid">
            <div class="layout-body inner">
                <div class="row">

                    <aside class="col-sm-2 col-sm-offset-1">
                        <!-- blocks -->

                        <div class="uk-grid-small" data-uk-grid>

                            <div class="block block-widget__categories">
                                <div class="block-content">

                                    @if(!empty($media_category) && is_array($media_category))
                                        <ul class="block-body">
                                            <li class="header {{ is_page(ID_LIST_MEDIA) ? 'active' : '' }}">
                                                <a href="{{ get_permalink(ID_LIST_MEDIA) }}"
                                                   title="<?php _e('Accueil Téléchargement', THEME_TD) ?>">
                                                    <i class="icon icon-preview-arrow-right"></i>
                                                    <span><?php _e('Accueil Téléchargement', THEME_TD) ?></span>
                                                </a>
                                                <button class="btn visible-xs"
                                                        data-uk-toggle="target:.block-widget__categories;cls:active">
                                                    <i class="icon icon-modal-close"></i>
                                                </button>
                                            </li>

                                            @foreach($media_category as $cat)
                                                <?php $view_type = get_field('media_cat_view_download', 'media_category_'.$cat->term_id) ?>
                                                @if(empty($view_type))
                                                    @if($term->term_id === $cat->term_id)
                                                        <li class="active">
                                                            <a href="{{ get_term_link($cat->term_id) }}"
                                                               title="{{ $cat->name }}">
                                                                <i class="icon icon-preview-arrow-right"></i>
                                                                <span>{!! $cat->name !!}</span>
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{ get_term_link($cat->term_id) }}"
                                                               title="{{ $cat->name }}">
                                                                <i class="icon icon-preview-arrow-right"></i>
                                                                <span>{!! $cat->name !!}</span>
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endforeach

                                        </ul><!-- .block-body -->
                                    @endif
                                </div><!-- .block-content -->
                                <div class="block-extra visible-xs">
                                    <button class="btn" data-uk-toggle="target:.block-product__filter;cls:active">
                                        <i class="icon icon-product__filter-sort"></i>
                                    </button>
                                </div>
                            </div><!-- .block-widget__categories -->

                        </div>

                        <!-- end: blocks -->
                    </aside>

                    @template('parts.content', 'download')

                </div>
            </div><!-- .layout-body -->
        </div><!-- #layout -->

        @include('components.page.footer-social')

    </main>

@endsection
