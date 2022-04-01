<?php

namespace App\Providers;

use App\Models\Product;
use App\Stats\AttributeValueQuery;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            'product' => Product::class,
            'brand' => AttributeValueQuery::class,
        ]);
    }
}
