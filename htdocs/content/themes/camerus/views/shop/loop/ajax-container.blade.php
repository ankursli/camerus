<?php global $product; ?>
@if(!empty($products))
    @foreach($products as $product)
        @include('shop.content-product_filtered', ['product' => $product])
    @endforeach
@else
    @if(!empty($paged_load_more))

    @else
        @php(do_action('woocommerce_no_products_found'))
    @endif
@endif

@include('common.pagination-ajax')