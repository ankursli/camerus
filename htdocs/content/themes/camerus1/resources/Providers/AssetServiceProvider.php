<?php

namespace Theme\Providers;

use Illuminate\Support\ServiceProvider;
use Themosis\Core\ThemeManager;
use Themosis\Support\Facades\Asset;

class AssetServiceProvider extends ServiceProvider
{
    /**
     * Theme Assets
     *
     * Here we define the loaded assets from our previously defined
     * "dist" directory. Assets sources are located under the root "assets"
     * directory and are then compiled, thanks to Laravel Mix, to the "dist"
     * folder.
     *
     * @see https://laravel-mix.com/
     */
    public function register()
    {
        /** @var ThemeManager $theme */
        $theme = $this->app->make('wp.theme');
        $version = 'theme-2023-11-07-003';

        Asset::add('vendor_styles', 'styles/vendor.css', [], $theme->getHeader('version'))->to('front');
        Asset::add('main_styles', 'styles/main.css', ['vendor_styles'], $theme->getHeader('version'))->to('front');
        Asset::add('theme_styles', 'css/' . $version . '.css', ['main_styles'], $theme->getHeader('version'))->to('front');
        Asset::add('theme_woo', 'css/woocommerce-' . $version . '.css', ['theme_styles'], $theme->getHeader('version'))->to('front');

        Asset::add('admin_styles', 'css/admin.css', [], $theme->getHeader('version'))->to('admin');
        Asset::add('admin_bt_styles', 'css/admin_bootstrap.css', [], $theme->getHeader('version'))->to('admin');

        Asset::add('modernizr_js', 'scripts/vendor/modernizr.js', [], $theme->getHeader('version'), false)->to('front');
        Asset::add('vendor_js', 'scripts/vendor.js', ['jquery-core'], $theme->getHeader('version'))->to('front');
        Asset::add('main_js', 'scripts/main.js', ['vendor_js'], $theme->getHeader('version'))->to('front');
        Asset::add('theme_js', 'js/' . $version . '.min.js', ['main_js'], $theme->getHeader('version'))->to('front');
        Asset::add('sharer_js', 'js/sharer.min.js', ['main_js'], $theme->getHeader('version'))->to('front');

        Asset::add('cmrs_admin_js', 'js/admin.min.js', ['jquery', 'jquery-core'], $theme->getHeader('version'))->to('admin');
    }
}
