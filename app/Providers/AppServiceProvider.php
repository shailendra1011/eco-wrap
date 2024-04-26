<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $file = app_path('Helpers/ApiResponse.php');
        if (file_exists($file)) {
            require_once $file;
        }
        $file = app_path('Helpers/Helper.php');
        if (file_exists($file)) {
            require_once $file;
        }

        $constants = ('config/Constants.php');
        if (file_exists($constants)) {
            require_once $constants;
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->singleton(StripeClient::class, function () {
        //     return new StripeClient(config('stripe.secret'));
        // });
    }
}
