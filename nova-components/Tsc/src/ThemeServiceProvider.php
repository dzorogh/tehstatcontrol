<?php

namespace Dzorogh\Tsc;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::booted(function () {
            Nova::theme(asset('/dzorogh/tsc/theme.css'));
        });

        $this->publishes([
            __DIR__.'/../resources/css' => public_path('dzorogh/tsc'),
        ], 'public');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
