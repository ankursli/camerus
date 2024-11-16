<?php

use App\Hooks\Salon;
use Illuminate\Support\Facades\Route;

/**
 * Theme routes.
 *
 * The routes defined inside your theme override any similar routes
 * defined on the application global scope.
 */
Route::any('singular', ['salon',
    function ($post) {
        $salon = Salon::getSalon(['post__in' => [$post->ID], 'post_status' => ['publish', 'private']]);
        setDateTimeLocalFormat();
        return view('pages.agenda-single', ['salons' => $salon]);
    }
]);

Route::any('singular', function () {
    return view('blog.single');
});

Route::any('post', function () {
    return view('blog.single');
});

Route::any('page', function () {
    return view('pages.default');
});

Route::any('category', function ($page) {
    $args = [];

    $args['queried_object_id'] = $page->queried_object_id;

    return view('blog.category', $args);
});

Route::any('tag', function ($page, $query) {
    $args = [];
    return view('pages.tax-list', $args);
});

Route::any('search', function ($page, $query) {
    $args = [];
    return view('pages.search', $args);
});

Route::get('404', function () {
    $args = [];
    return view('errors.404', $args);
});