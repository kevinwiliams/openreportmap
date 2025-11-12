<?php

namespace App\Providers;

use App\Listeners\StoreMediaExifData;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAdded;
use Illuminate\Support\ServiceProvider;

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
        RateLimiter::for('report-submissions', function (Request $request) {
            return Limit::perMinutes(15, 10)->by($request->ip());
        });

        Event::listen(MediaHasBeenAdded::class, StoreMediaExifData::class);
    }
}
