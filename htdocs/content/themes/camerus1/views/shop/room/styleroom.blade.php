@extends('layouts.main')

@section('content')

    <h1 class="hide">{{ get_the_title() }}</h1>

    <main id="main" role="main" class="site-main row styleroom">

        @include('shop.global.breadcrumb')

        @include('shop.room.event-style-banner')

        @if(!empty($styles))
            <section class="container-fluid styleroom-list">
                <div class="row">
                    <div class="container">
                        @foreach($styles as $style)
                            <a href="{{ $style['product_link'] }}" class="col-sm-4 style-item">
                                <div class="box-image">
                                    <div class="image-inside">
                                        <img src="{{ $style['thumbnail_image']['url'] }}"
                                             alt="{{ $style['thumbnail_image']['alt'] }}">
                                    </div>
                                </div>
                                <div class="box-text">
                                    <h3 class="item-title">{{ $style['style_title'] }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

@endsection