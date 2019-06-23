<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Providers\LumenServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        # 注册jwt认证
        $this->app->register(LumenServiceProvider::class);
    }

    public function boot()
    {
        # 注册创建用户观察者
        User::observe(UserObserver::class);
    }
}
