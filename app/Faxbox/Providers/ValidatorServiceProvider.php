<?php namespace Faxbox\Providers;

use Illuminate\Support\ServiceProvider;
use Faxbox\Service\Validation\CustomLaravelValidator;

class ValidatorServiceProvider extends ServiceProvider {

    public function register(){}

    public function boot()
    {
        $this->app->validator->resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomLaravelValidator($translator, $data, $rules, $messages);
        });
    }

}