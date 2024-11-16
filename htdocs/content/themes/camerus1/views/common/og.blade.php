@php
    $ID = get_the_ID();
    $open_graph = get_open_graph($ID);
@endphp

<meta name="og:url" property="og:url" content="{{ get_permalink($ID) }}"/>
<meta name="og:type" property="og:type" content="website"/>
<meta name="og:title" property="og:title" content="{!! $open_graph['metatitle'] !!}"/>
<meta name="og:description" property="og:description" content="{{ $open_graph['metadesc'] }}"/>
<meta name="og:image" property="og:image" content="{{ $open_graph['post_thumb'] }}"/>
<meta name="og:image:alt" property="og:image:alt" content="{{ $open_graph['metatitle'] }}"/>