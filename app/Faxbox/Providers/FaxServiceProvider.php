<?php

namespace Faxbox\Providers;

use Faxbox\External\Api\Response;
use Illuminate\Support\ServiceProvider;
use Phaxio\Phaxio;
use Faxbox\External\Api\PhaxioApi;

class FaxServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $settings = $app->make('Faxbox\Repositories\Setting\SettingInterface');
        
        $app->bind('Faxbox\External\Api\FaxInterface', function($app) use ($settings)
        {
            $phaxio = new Phaxio(
                $settings->get('services.phaxio.public'),
                $settings->get('services.phaxio.secret')
            );
            return new PhaxioApi($phaxio, new Response());
        });
    }
}
