<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
         // Repositories
        // $this->app->bind(\App\Repositories\ProductRepository::class, \App\Repositories\ProductRepository::class);
        // $this->app->bind(\App\Repositories\SalesRepository::class, \App\Repositories\SalesRepository::class);

        // // Services
        // $this->app->bind(\App\Services\ProductService::class, \App\Services\ProductService::class);
        // $this->app->bind(\App\Services\SalesService::class, \App\Services\SalesService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('local')) { // only in local/dev
        DB::listen(function ($query) {
            Log::info("SQL Executed: " . $query->sql, $query->bindings);
        });
    }
    }
}
