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
        // Bây giờ Laravel đã biết Promotion là model nào
        Promotion::observe(PromotionObserver::class);
    }
}
