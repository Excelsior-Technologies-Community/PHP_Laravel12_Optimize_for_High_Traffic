<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Disable query log in production to save memory
        if (app()->isProduction()) {
            DB::disableQueryLog();
        }
    }
}
