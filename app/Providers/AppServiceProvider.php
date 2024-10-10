<?php

namespace App\Providers;

use App\Payment\Gateways\TapPayGateway;
use App\Payment\PaymentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('payment.tabby', function () {
            return new PaymentService(new TapPayGateway());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
