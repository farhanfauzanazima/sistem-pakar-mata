<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CertaintyFactorService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Daftarkan CertaintyFactorService sebagai singleton
        $this->app->singleton(CertaintyFactorService::class, function () {
            return new CertaintyFactorService();
        });
    }

    public function boot(): void
    {
        //
    }
}