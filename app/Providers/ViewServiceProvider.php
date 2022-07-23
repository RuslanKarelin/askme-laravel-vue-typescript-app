<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\StatisticComposer;
use App\Helpers\PageHelper;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            'themes.askme.partials.right-sidebar',
            StatisticComposer::class
        );

        View::composer('*', function ($view) {
            $view->with('pageHelper', app(PageHelper::class));
        });
    }
}
