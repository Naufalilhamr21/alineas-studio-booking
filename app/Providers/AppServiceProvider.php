<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // // Paksa HTTPS jika diakses lewat Ngrok (agar form dan asset aman)
        // if (str_contains(request()->getHost(), 'ngrok-free.dev')) {
        //     URL::forceScheme('https');
        // }

        Model::preventLazyLoading(! app()->isProduction());
        
        // Paksa HTTPS jika di server production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
