<?php

namespace App\Providers;

use App\Contracts\Encoder;
use App\Contracts\UrlShortner;
use App\Services\Base62Encoder;
use App\Services\UrlShortnerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Encoder::class, Base62Encoder::class);
        $this->app->singleton(UrlShortner::class, UrlShortnerService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
