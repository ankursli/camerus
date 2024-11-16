<?php

/**
 * Application routes.
 */

use Illuminate\Support\Facades\Route;

Route::any('checkout_pay_page', ['uses' => 'ShopController@indexOrderPay'])->name('checkout-pay-page');
Route::any('checkout', ['uses' => 'ShopController@indexPaiementCheckout'])->name('checkout-woocommerce-template');

Route::any('template', ['home-template', 'uses' => 'PageController@indexHome'])->name('home-template');
Route::any('template', ['blog-template', 'uses' => 'PageController@indexBlog'])->name('blog-template');
Route::any('template', ['agenda-template', 'uses' => 'PageController@indexAgenda'])->name('agenda-template');
Route::any('template', ['empty-template', 'uses' => 'PageController@indexEmpty'])->name('empty-template');
Route::any('template', ['download-template', 'uses' => 'PageController@indexDownload'])->name('download-template');
Route::any('template', ['pro-customer-sign', 'uses' => 'PageController@indexProCustomer'])->name('pro-customer-sign');

Route::any('template', ['cart-template', 'uses' => 'ProductController@indexCart'])->name('cart-template');
Route::any('template', ['checkout-paiement-template', 'uses' => 'ShopController@indexPaiementCheckout'])->name('checkout-paiement-template');
Route::any('template', ['my-account-template', 'uses' => 'ProductController@indexMyAccount'])->name('my-account-template');
Route::match(['get', 'post'], 'recherche-produit', ['uses' => 'ProductController@indexProductSearch'])->name('search-product');
Route::match(['get', 'post'], 'en/search-product', ['uses' => 'ProductController@indexProductSearch'])->name('search-product');
Route::match(['get', 'post'], 'dotations', ['uses' => 'ProductController@indexDotationList'])->name('dotation-list');
Route::match(['get', 'post'], 'en/dotations', ['uses' => 'ProductController@indexDotationList'])->name('dotation-list-en');
Route::get('reed/{base64?}', ['uses' => 'ProductController@indexReedUrl'])->name('reed-json');
Route::post('sso-reed', ['uses' => 'ProductController@indexSsoReedUrl'])->name('sso-reed-json');
Route::get('json/export-reed', ['uses' => 'ProductController@indexReedExport'])->name('reed-json-export')->middleware('reedExportAuth');
Route::get('3d-files/{slug}', ['uses' => 'PageController@index3DZipFile'])->name('3d-zip-file');
Route::get('en/3d-files/{slug}', ['uses' => 'PageController@index3DZipFile'])->name('3d-zip-file-en');
Route::get('agenda-pdf/{slug?}', ['uses' => 'PageController@indexAgendaPdf'])->name('agenda-pdf');
Route::get('en/agenda-pdf/{slug?}', ['uses' => 'PageController@indexAgendaPdf'])->name('agenda-pdf-en');
Route::get('load-3d-files/{slug?}', ['uses' => 'ProductController@indexLoad3DFiles'])->name('load-3d-files');
Route::get('en/load-3d-files/{slug?}', ['uses' => 'ProductController@indexLoad3DFiles'])->name('load-3d-files-en');
Route::get('exec-wp-cron-12978', ['uses' => 'PageController@indexWpCronExec'])->name('wp-cron-exec');

Route::any('template', ['showroom-template', 'uses' => 'ShopController@indexShowroom'])->name('showroom-template');
Route::any('template', ['styleroom-template', 'uses' => 'ShopController@indexStyleroom'])->name('styleroom-template');

Route::any('page', ['uses' => 'PageController@indexPage']);

Route::any('shop', function () {
    return view('shop.archive');
});

Route::any('product_category', function () {
    return view('shop.archive');
});

Route::any('product_tag', function () {
    return view('shop.archive');
});

Route::any('product', ['uses' => 'ProductController@indexSingle']);

Route::any('tax', ['media_category', 'uses' => 'PageController@indexDownload']);
