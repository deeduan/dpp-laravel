<?php

namespace App\Providers;

use App\Http\Middleware\AfterApiJsonResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 注册响应结束终止中间件
        $this->app->singleton(AfterApiJsonResponse::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 设置索引长度 因为这里使用的是mysql 5.6
        Schema::defaultStringLength(191);
    }
}
