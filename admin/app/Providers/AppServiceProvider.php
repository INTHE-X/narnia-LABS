<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // cafe24 서버 레벨 환경변수가 .env를 덮어쓰는 경우를 대비해
        // URL을 직접 하드코딩하여 강제 고정
        \Illuminate\Support\Facades\URL::forceRootUrl('https://narnia.ai');
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
