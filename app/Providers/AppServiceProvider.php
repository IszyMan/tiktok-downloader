<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\DownloaderProviderInterface;
use App\Providers\TikWMProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       $this->app->bind(
            DownloaderProviderInterface::class,
            TikWMProvider::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
