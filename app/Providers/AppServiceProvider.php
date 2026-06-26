<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use App\Services\CertaintyFactorService;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CertaintyFactorService::class, function () {
            return new CertaintyFactorService();
        });
    }

    public function boot(): void
    {
        // Set locale Carbon ke Bahasa Indonesia
        Carbon::setLocale('id');

        // Force HTTPS di production (opsional)
        // if (app()->environment('production')) {
        //     URL::forceScheme('https');
        // }

        // Gunakan tampilan pagination Bootstrap 5
        Paginator::useBootstrapFive();
    }
}
