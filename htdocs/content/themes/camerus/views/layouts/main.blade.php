<!doctype html>
<html {!! get_language_attributes() !!}>
<head>
    <meta charset="{{ get_bloginfo('charset') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <link rel="prefetch" href="{{ get_template_directory_uri() . '/dist/fonts/icon.eot' }}" as="font" 
          crossorigin>
    <link rel="prefetch" href="{{ get_template_directory_uri() . '/dist/fonts/icon.woff' }}" as="font" 
          crossorigin>
    <link rel="prefetch" href="{{ get_template_directory_uri() . '/dist/fonts/icon.woff2' }}" as="font" 
          crossorigin>
    <link rel="prefetch"
          href="{{ get_template_directory_uri() . '/dist/fonts/bootstrap/glyphicons-halflings-regular.eot' }}"
          as="font" crossorigin>
    <link rel="prefetch"
          href="{{ get_template_directory_uri() . '/dist/fonts/bootstrap/glyphicons-halflings-regular.woff' }}"
          as="font" crossorigin>
    <link rel="prefetch"
          href="{{ get_template_directory_uri() . '/dist/fonts/bootstrap/glyphicons-halflings-regular.woff2' }}"
          as="font" crossorigin>

    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="preload"
          href="https://fonts.googleapis.com/css?family=Overpass:300,400,500,600,700,800,900"
          as="style"
          onload="this.onload=null; this.rel='stylesheet';">
    <link rel="preload"
          href="https://fonts.googleapis.com/css?family=Assistant:400,700"
          as="style"
          onload="this.onload=null; this.rel='stylesheet';">

    <link rel="apple-touch-icon" href="{{ get_template_directory_uri() . '/dist/apple-touch-icon.png' }}">
    <link rel="apple-touch-icon" sizes="512x512"
          href="{{ get_template_directory_uri() . '/dist/android-chrome-512x512.png' }}">
    <link rel="apple-touch-icon" sizes="192x192"
          href="{{ get_template_directory_uri() . '/dist/android-chrome-192x192.png' }}">
    <link rel="apple-touch-icon" sizes="180x180"
          href="{{ get_template_directory_uri() . '/dist/apple-touch-icon.png' }}">
    <link rel="apple-touch-icon" sizes="150x150" href="{{ get_template_directory_uri() . '/dist/mstile-150x150.png' }}">
    <link rel="icon" type="image/png" sizes="32x32"
          href="{{ get_template_directory_uri() . '/dist/favicon-32x32.png' }}">
    <link rel="icon" type="image/png" sizes="16x16"
          href="{{ get_template_directory_uri() . '/dist/favicon-16x16.png' }}">
    <link rel="icon" href="{{ get_template_directory_uri() . '/dist/favicon.ico' }}"/>
    <link rel="manifest" href="{{ get_template_directory_uri() . '/dist/site.webmanifest' }}">
    <link rel="mask-icon" href="{{ get_template_directory_uri() . '/dist/safari-pinned-tab.svg' }}" color="#bccf02">
    <meta name="msapplication-TileColor" content="#bccf02">
    @head
    @yield('og')
    <meta name="og:site_name" property="og:site_name" content="{{ get_bloginfo('name') }}"/>
    <meta name="og:locale" property="og:locale" content="{{ get_bloginfo('language') }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body @php(body_class())>

<!--[if lt IE 10]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

<style>
    #loader {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        z-index: 1050;
    }

    #loader circle {
        stroke: #ff560d;
    }

    body #loader + #page {
        opacity: 1;
    }

    body #loader.loaded + #page {
        opacity: 1;
    }

    #loader + #page #main {
        opacity: 0;
        z-index: 1;
    }

    #loader.loaded + #page #main {
        opacity: 1;
    }

    #loader .spinner,
    #loader [data-uk-spinner] {
        -webkit-animation-name: clockwise;
        animation-name: clockwise;
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
        -webkit-animation-timing-function: linear;
        animation-timing-function: linear;
        -webkit-animation-iteration-count: infinite;
        animation-iteration-count: infinite;
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 32px;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        color: #ff560d;
    }
</style>

<div id="loader" class="tag-bgc_b">
    <div data-uk-spinner="ratio: 2" class="uk-icon uk-spinner">
        <svg width="60" height="60" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg" style="position: absolute;">
            <circle fill="none" stroke="#000" cx="15" cy="15" r="14" style="stroke-width: 0.5px;"></circle>
        </svg>
    </div>
</div>

<div id="page">
    @include('common.header')
    @yield('content')
    @include('common.footer')
</div>

@include('components.popup.group-modal')

@footer

</body>
</html>
