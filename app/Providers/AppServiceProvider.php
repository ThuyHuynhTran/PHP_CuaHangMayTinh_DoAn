<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Promotion; // THÊM DÒNG NÀY ĐỂ IMPORT MODEL
use App\Observers\PromotionObserver; // Bạn cũng cần import cả Observer

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
    Promotion::observe(PromotionObserver::class);
}
}
