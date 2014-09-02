<?php

namespace Faxbox\Providers;

use Illuminate\Support\ServiceProvider;
use Faxbox\Service\Navigation\Builder;

class NavigationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['navigation.builder'] = $this->app->share(function ($app) {
            return new Builder($app['config'], $app->make('Faxbox\Repositories\User\UserInterface'));
        });
    }
}
