<?php
use Illuminate\Support\Facades\Route;
?>
<header id="header">

    <div id="topbar" class="section container-fluid" data-uk-height-match="target:#topbar.section .section-body .block">
        <div class="section-body inner">

            <div class="row">

                <div class="col-md-2 col-sm-3 col-md-offset-1 col-md-offset-0 col-left">

                    <div class="block block-topbar__logo">
                        <div class="block-content">
                            <a class="block-body" href="{{ home_url() }}">
                                <img src="{{ get_template_directory_uri() . '/dist/images/header-logo.png' }}"
                                     width="300" height="64" class="" alt=""
                                     srcset="{{ get_template_directory_uri() . '/dist/images/header-logo.png' }}"/>
                            </a><!-- .block-body -->
                        </div><!-- .block-content -->
                    </div><!-- .block-topbar__logo -->

                </div><!-- .col -->

                <div class="col-md-8 col-sm-9 col-right uk-flex uk-flex-middle@l uk-flex-right@m">

                    {!! do_action('custom_wpml_language_switcher') !!}

                    <div class="block block-topbar__primary uk hidden-xs">
                        <div class="block-content uk-navbar-container uk-navbar-transparent" data-uk-navbar>
                            {!! do_action('wp_site_header_menu') !!}
                        </div><!-- .block-content -->
                    </div><!-- .block-topbar__primary -->

                    <div class="block block-topbar__tools uk v-none">
                        <div class="block-content uk-navbar-container uk-navbar-transparent" data-uk-navbar>
                            <ul class="block-body uk-navbar-nav">
                                <li class="search">
                                    <a href="#" title="<?php _e('Rechercher', THEME_TD) ?>" rel="nofollow">
                                        <i class="icon icon-topbar-search"></i>
                                    </a>

                                    @if(Route::currentRouteName() != 'search-product')
                                        <div class="container-fluid o-hide"
                                             data-uk-dropdown="boundary: #boundary;boundary-align:true;pos:bottom">
                                            <div class="card card-tools__search">
                                                <div class="inner">
                                                    <div class="row">
                                                        <div class="col-sm-10 col-sm-offset-1">
                                                            @include('common.product-search-form')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </li>

                                <li class="account">
                                    <a href="#" title="Connexion" rel="nofollow">
                                        <i class="icon icon-topbar-account"></i>
                                    </a>
                                </li>

                                <li class="cart <?php if (is_cart()) : echo ' uk-active'; endif; ?>">
                                    <a href="{{ wc_get_cart_url() }}" title="Panier" rel="nofollow">
                                        <i class="icon icon-topbar-cart"></i>
                                    </a>
                                    <div data-uk-dropdown="boundary: .block-topbar__tools;boundary-align:true;pos:bottom-right"
                                         class="o-hide">
                                        <div class="card card-tools__cart">
                                            <form class="card-content" method="post" action="#">
                                                <div class="card-header">
                                                    <?php _e('Mon panier', THEME_TD) ?>
                                                </div><!-- .card-header -->
                                                <div class="card-body mini-cart-container">

                                                    <span><?php _e('Chargement', THEME_TD) ?>...</span>

                                                </div><!-- .card-body -->
                                                <div class="card-footer">
                                                    <a href="{{ wc_get_cart_url() }}"
                                                       title="<?php _e('Voir mon panier', THEME_TD) ?>"
                                                       class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-to-cart">
                                                        <span><?php _e('Voir mon panier', THEME_TD) ?></span>
                                                    </a>
                                                </div><!-- .card-footer -->
                                            </form><!-- .card-content -->
                                        </div><!-- .card-tools__cart -->
                                    </div>
                                </li>
                            </ul><!-- .block-body -->
                        </div><!-- .block-content -->
                    </div><!-- .block-topbar__tools -->

                    <div class="block block-topbar__secondary uk">
                        <div class="block-content uk-navbar-container uk-navbar-transparent" data-uk-navbar>
                            <ul class="block-body uk-navbar-nav">
                                <li class="hidden-xs <?php if (is_page(ID_LIST_POST)) : echo ' uk-active'; endif; ?>">
                                    <a href="{{ get_permalink(ID_LIST_POST) }}"
                                       title="<?php _e('Blog', THEME_TD) ?>"><?php _e('Blog', THEME_TD) ?></a>
                                </li>
                                <li class="visible-xs">
                                    <a title="Blog" href="#offcanvas-topbar__primary" data-uk-toggle>
                                        <i class="icon icon-topbar-burger"></i>
                                    </a>
                                </li>
                            </ul><!-- .block-body -->
                        </div><!-- .block-content -->
                    </div><!-- .block-topbar__secondary -->

                </div><!-- .col -->

            </div><!-- .row -->

        </div><!-- .section-body -->
    </div><!-- #topbar -->

</header><!-- #header -->