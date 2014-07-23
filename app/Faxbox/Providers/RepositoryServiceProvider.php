<?php

namespace Faxbox\Providers;

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
            'Faxbox\Repositories\Fax\FaxInterface',
            'Faxbox\Repositories\Fax\EloquentFaxRepository'
        );

        $app->bind(
            'Faxbox\Repositories\Group\GroupInterface',
            'Faxbox\Repositories\Group\SentryGroup'
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
            'Faxbox\Repositories\Session\SessionInterface',
            'Faxbox\Repositories\Session\SentrySession'
        );

        $app->bind(
            'Faxbox\Repositories\Setting\SettingInterface',
            'Faxbox\Repositories\Setting\EloquentSettingRepository'
        );

        $app->bind(
            'Faxbox\Repositories\User\UserInterface',
            'Faxbox\Repositories\User\SentryUser'
        );
    }
}
