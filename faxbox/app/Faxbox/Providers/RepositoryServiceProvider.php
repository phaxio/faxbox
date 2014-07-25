<?php

namespace Faxbox\Providers;

use Illuminate\Support\ServiceProvider;
use Faxbox\Repositories\User\SentryUser;
use Faxbox\Repositories\Session\SentrySession;
use Faxbox\Repositories\Group\SentryGroup;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app; 
        
        
        $app->bind(
            'Faxbox\Repositories\Fax\FaxInterface',
            'Faxbox\Repositories\Fax\EloquentFaxRepository'
        );

        $app->bind(
            'Faxbox\Repositories\Phone\PhoneInterface',
            'Faxbox\Repositories\Phone\EloquentPhoneRepository'
        );

        $app->bind(
            'Faxbox\Repositories\Recipient\RecipientInterface',
            'Faxbox\Repositories\Recipient\EloquentRecipientRepository'
        );

        $app->bind(
            'Faxbox\Repositories\Setting\SettingInterface',
            'Faxbox\Repositories\Setting\EloquentSettingRepository'
        );

        $app->singleton(
            'Faxbox\Repositories\Permission\PermissionInterface',
            'Faxbox\Repositories\Permission\PermissionRepository'
        );
        
        // Bind the Session Repository
        $app->bind('Faxbox\Repositories\Session\SessionInterface', function($app)
        {
            return new SentrySession(
                $app['sentry']
            );
        });

        // Bind the Group Repository
        $app->bind('Faxbox\Repositories\Group\GroupInterface', function($app)
        {
            return new SentryGroup(
                $app['sentry']
            );
        });
        
        // Bind the User Repository
        $app->bind('Faxbox\Repositories\User\UserInterface', function($app)
        {
            return new SentryUser(
                $app['sentry']
            );
        });
    }
}
