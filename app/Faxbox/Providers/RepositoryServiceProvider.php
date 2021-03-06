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
            'Faxbox\Repositories\Number\NumberInterface',
            'Faxbox\Repositories\Number\EloquentNumberRepository'
        );

        $app->bind(
            'Faxbox\Repositories\Setting\SettingInterface',
            'Faxbox\Repositories\Setting\LaravelConfigSettingRepository'
        );

        $this->app['setting'] = $this->app->share(function($app)
        {
            return \App::make('Faxbox\Repositories\Setting\SettingInterface');
        });

        $app->singleton(
            'Faxbox\Repositories\Permission\PermissionInterface',
            'Faxbox\Repositories\Permission\PermissionRepository'
        );

        $app->bind(
            'Faxbox\Repositories\File\FileInterface',
            'Faxbox\Repositories\File\FileRepository'
        );

        $app->bind(
            'Faxbox\Repositories\Mail\MailInterface',
            'Faxbox\Repositories\Mail\MailRepository'
        );

        $this->app['permission'] = $this->app->share(function($app)
        {
            return \App::make('Faxbox\Repositories\Permission\PermissionInterface');
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Permission', 'Faxbox\Facades\Permission');
        });
        
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
                $app['sentry'],
                \App::make('Faxbox\Repositories\Permission\PermissionInterface')
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
