<?php

namespace Logit\Providers;

use Logit\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        view()->composer('layouts.topnav', function($view) {
            $notifications = Notification::where([
                ['user_id', Auth::id()],
                ['read', 0],
            ])->get();
            
            $count = Notification::where([
                ['user_id', Auth::id()],
                ['read', 0],
            ])->count();
            
            $view->with([
                'notifications' => $notifications,
                'notificationsCount' => $count,
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {   
        if (env('APP_ENV') === 'prod') {
            $this->app['request']->server->set('HTTPS', true);
        }
    }
}
