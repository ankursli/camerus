<?php

namespace App\Metabox;

class ProductDataManager
{
    public function index($args)
    {
        $view_args = [];
        $product_id = get_the_ID();
        $product = wc_get_product($product_id);
        $view_args['product_sku'] = $product->get_sku();

        return view('metabox.product-custom-sku', $view_args);
    }
}