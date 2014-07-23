<?php

namespace Faxbox\Providers;

use Illuminate\Support\ServiceProvider;
use Faxbox\Service\Form\Login\LoginForm;
use Faxbox\Service\Form\Login\LoginFormLaravelValidator;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app->bind('Faxbox\Service\Form\Login\LoginForm', function($app)
        {
            return new LoginForm(
                new LoginFormLaravelValidator( $app['validator'] ),
                $app->make('Faxbox\Repositories\Session\SessionInterface')
            );
        });

    }
}
