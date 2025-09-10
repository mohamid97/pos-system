<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Observers\CategoryObserver;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {

       RateLimiter::for('pos', function ($request) {
            return [
                Limit::perMinute(100)->by(optional($request->user())->id ?: $request->ip()),
            ];
        });


        
        Category::observe(CategoryObserver::class);

    }

    
    
}