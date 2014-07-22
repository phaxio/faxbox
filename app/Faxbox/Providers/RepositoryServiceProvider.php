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
        $this->app->bind(
            'Faxbox\Repositories\UserRepositoryInterface',
            'Faxbox\Repositories\Eloquent\UserRepository'
        );
    }
}
