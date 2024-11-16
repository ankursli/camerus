<?php

namespace App\Providers;

use App\Library\Services\RentOrderManager;
use Illuminate\Support\ServiceProvider;

class RentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Library\Services\Contracts\SendRentServiceInterface', function ($app) {
            return new RentOrderManager();
        });
    }
}
