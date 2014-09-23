<?php namespace Faxbox\Providers;

use Faxbox\Repositories\Setting\ConfigRepository;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('Faxbox\Repositories\Setting\ConfigRepository', function($app)
        {
            $loader = $app->getConfigLoader();
            return new ConfigRepository($loader, $app['env']);
        });

        $this->app['config'] = $this->app->share(function($app)
        {
            return $app->make('Faxbox\Repositories\Setting\ConfigRepository');
        });
    }
}