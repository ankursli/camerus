<!doctype html>
<html {!! get_language_attributes() !!}>
<head>
    <meta charset="{{ get_bloginfo('charset') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Assistant:400,700" rel="stylesheet">
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
    <link rel="mask-icon" href="{{ get_template_directory_uri() . '/dist/safari-pinned-tab.svg' }}" color="#ff560d">
    <meta name="msapplication-TileColor" content="#ff560d">
    @head
    @yield('og')
    <meta name="og:site_name" property="og:site_name" content="{{ get_bloginfo('name') }}"/>
    <meta name="og:locale" property="og:locale" content="{{ get_bloginfo('language') }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body @php(body_class('page-produit-pdf'))>

<!--[if lt IE 10]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

<div id="page">
    @yield('content')
</div>

@footer

</body>
</html>
