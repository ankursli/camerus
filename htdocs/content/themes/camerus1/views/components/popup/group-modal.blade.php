<?php

use Illuminate\Support\Facades\Route;

global $product;
?>

@include('components.popup.modal-event')
@include('components.popup.modal-change-salon')
@include('components.popup.modal-success-order')
@include('components.popup.modal-warning')
@include('components.popup.modal-event-warning')
@include('components.popup.modal-category-zip-download')

@if(!empty($product) || str_contains(url()->current(), '/showroom/') || str_contains(url()->current(), '/styleroom/'))
    @include('components.popup.modal-sketch')
@endif

@if(Route::currentRouteName() == 'dotation-list' || Route::currentRouteName() == 'dotation-list-en')
    @include('components.popup.modal-change-reed-user')
@endif

@if(!empty($product) && $product instanceof WC_Product && $product->is_type('dotation'))
    @include('components.popup.modal-product')
@endif

@if(is_home() || is_front_page())
    @include('components.popup.modal-custom-message')
@endif