@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')

    <main id="main">
        <div data-uk-spinner="ratio: 2"></div>

        <div id="layout" class="layout container-fluid">
            <div class="layout-body inner">
                <div class="row">

                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="uk-grid-small" data-uk-grid>

                            <div class="block block-content__404 uk-width-1-1">
                                <div class="block-content">
                                    <div class="block-header">
                                        <img src="{{ get_template_directory_uri() . '/dist/images/content__404-img1.jpg' }}"
                                             width="683" height="617" class="" alt="<?php _e('Erreur 404', THEME_TD) ?>"
                                             srcset="{{ get_template_directory_uri() . '/dist/images/content__404-img1.jpg' }}"/>
                                    </div><!-- .block-header -->
                                    <div class="block-body">
                                        <h1 class="title"><?php _e('Erreur 404', THEME_TD) ?></h1>
                                        <div class="summary">
                                            <p><?php _e('La page que vous cherchez est introuvable.', THEME_TD) ?>
                                                <br><?php _e("Nous vous proposons de revenir à l'accueil.", THEME_TD) ?>
                                            </p>
                                        </div>
                                        <a href="{{ home_url() }}" title="<?php _e('Retour à l’accueil', THEME_TD) ?>"
                                           class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12">
                                            <span><?php _e('Retour à l’accueil', THEME_TD) ?></span>
                                        </a>
                                    </div><!-- .block-body -->
                                    <div class="block-footer"></div><!-- .block-footer -->
                                </div><!-- .block-content -->
                            </div><!-- .block-content__404 -->

                        </div><!-- .col -->

                    </div>
                </div><!-- .layout-body -->
            </div><!-- #layout -->

            @include('components.page.footer-social')

        </div><!-- #layout -->

    </main><!-- #main -->

@endsection
