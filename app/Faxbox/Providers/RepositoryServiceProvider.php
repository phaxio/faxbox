<?php

namespace Faxbox\Providers;

use Faxbox\Repositories\Session\SentrySession;
use Illuminate\Support\ServiceProvider;

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
            'Faxbox\Repositories\FaxRepositoryInterface',
            'Faxbox\Repositories\Fax\EloquentFaxRepository'
        );

        $app->bind(
            'Faxbox\Repositories\GroupRepositoryInterface',
            'Faxbox\Repositories\Group\SentryGroup'
        );

        $app->bind(
            'Faxbox\Repositories\RecipientRepositoryInterface',
            'Faxbox\Repositories\Recipient\EloquentRecipientRepository'
        );

        $app->bind(
            'Faxbox\Repositories\SessionInterface',
            'Faxbox\Repositories\Session\SentrySession'
        );

        $app->bind(
            'Faxbox\Repositories\SettingRepositoryInterface',
            'Faxbox\Repositories\Setting\EloquentSettingRepository'
        );

        $app->bind(
            'Faxbox\Repositories\UserInterface',
            'Faxbox\Repositories\User\SentryUser'
        );
    }
}
