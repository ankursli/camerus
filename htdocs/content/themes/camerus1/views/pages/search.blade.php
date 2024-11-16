@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')

    <main id="main" {!! post_class() !!}>

        @include('shop.global.breadcrumb')

        <div id="layout" class="layout container-fluid">
            <div class="layout-body inner">
                <div class="row">


                    <aside class="col-sm-3 col-sm-offset-1">
                        <!-- blocks -->

                        <div class="uk-grid-small" data-uk-grid>

                            @if(function_exists('dynamic_sidebar'))
                                @php(dynamic_sidebar('sidebar-shop'))
                            @endif

                        </div>

                        <!-- end: blocks -->
                    </aside>

                    <div class="col-lg-6 col-md-7 col-sm-8">

                        <div class="uk-grid-small" data-uk-grid>
                            <!-- blocks -->

                            @if(have_posts())

                                @while(have_posts())
                                    @php(the_post())
                                    @template('parts.content', 'search')
                                @endwhile

                                {!! get_the_posts_navigation() !!}
                            @else
                                @template('parts.content', 'none')
                            @endif

                        <!-- end: blocks -->
                        </div>


                    </div><!-- .col -->

                </div>
            </div><!-- .layout-body -->
        </div><!-- #layout -->

        @include('components.page.footer-social')

    </main>

@endsection