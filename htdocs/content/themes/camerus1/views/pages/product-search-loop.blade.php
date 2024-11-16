<?php
use Illuminate\Support\Facades\Input;

global $product;
?>

@if(!empty($products))
    @foreach($products as $product)
        <?php $the_product = $product?>
        @include('shop.content-product_search')
    @endforeach
@else
    @if(empty(request()->get('view')))
        @php(do_action('woocommerce_no_products_found'))
    @endif
@endif

@include('common.pagination-ajax')