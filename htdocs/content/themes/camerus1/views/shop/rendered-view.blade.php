<?php

use Illuminate\Support\Facades\View;

global $product, $post, $current_user;


echo View::make($GLOBALS['shop_account_tmp'])->render();
?>