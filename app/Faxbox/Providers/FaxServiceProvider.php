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

        $app->bind('Faxbox\External\Api\FaxInterface', function($app)
        {
            $phaxio = new Phaxio(
                // todo move this to db
                \Config::get('faxbox.api.public'),
                \Config::get('faxbox.api.secret')
            );
            return new PhaxioApi($phaxio, new Response());
        });
    }
}
