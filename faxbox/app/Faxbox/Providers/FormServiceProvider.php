<?php

namespace Faxbox\Providers;

use Faxbox\Service\Form\Group\GroupForm;
use Faxbox\Service\Form\Group\GroupFormLaravelValidator;
use Illuminate\Support\ServiceProvider;
use Faxbox\Service\Form\Login\LoginForm;
use Faxbox\Service\Form\Login\LoginFormLaravelValidator;
use Faxbox\Service\Form\Register\RegisterForm;
use Faxbox\Service\Form\Register\RegisterFormLaravelValidator;
use Faxbox\Service\Form\ChangePassword\ChangePasswordForm;
use Faxbox\Service\Form\ChangePassword\ChangePasswordFormLaravelValidator;
use Faxbox\Service\Form\ForgotPassword\ForgotPasswordForm;
use Faxbox\Service\Form\ForgotPassword\ForgotPasswordFormLaravelValidator;
use Faxbox\Service\Form\ResendActivation\ResendActivationForm;
use Faxbox\Service\Form\ResendActivation\ResendActivationFormLaravelValidator;
use Faxbox\Service\Form\User\UserForm;
use Faxbox\Service\Form\User\UserFormLaravelValidator;
use Faxbox\Service\Form\ResetPassword\ResetPasswordForm;
use Faxbox\Service\Form\ResetPassword\ResetPasswordFormLaravelValidator;

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

        $app->bind('Faxbox\Service\Form\Register\RegisterForm', function($app)
        {
            return new RegisterForm(
                new RegisterFormLaravelValidator( $app['validator'] ),
                $app->make('Faxbox\Repositories\User\UserInterface')
            );
        });

        $app->bind('Faxbox\Service\Form\ChangePassword\ChangePasswordForm', function($app)
        {
            return new ChangePasswordForm(
                new ChangePasswordFormLaravelValidator( $app['validator'] ),
                $app->make('Faxbox\Repositories\User\UserInterface')
            );
        });

        $app->bind('Faxbox\Service\Form\ForgotPassword\ForgotPasswordForm', function($app)
        {
            return new ForgotPasswordForm(
                new ForgotPasswordFormLaravelValidator( $app['validator'] ),
                $app->make('Faxbox\Repositories\User\UserInterface')
            );
        });

        $app->bind('Faxbox\Service\Form\ResetPassword\ResetPasswordForm', function($app)
        {
            return new ResetPasswordForm(
                new ResetPasswordFormLaravelValidator( $app['validator'] ),
                $app->make('Faxbox\Repositories\User\UserInterface')
            );
        });

        $app->bind('Faxbox\Service\Form\ResendActivation\ResendActivationForm', function($app)
        {
            return new ResendActivationForm(
                new ResendActivationFormLaravelValidator( $app['validator'] ),
                $app->make('Faxbox\Repositories\User\UserInterface')
            );
        });

        $app->bind('Faxbox\Service\Form\User\UserForm', function($app)
        {
            return new UserForm(
                new UserFormLaravelValidator( $app['validator'] ),
                $app->make('Faxbox\Repositories\User\UserInterface')
            );
        });

        $app->bind('Faxbox\Service\Form\Group\GroupForm', function($app)
        {
            return new GroupForm(
                new GroupFormLaravelValidator( $app['validator'] ),
                $app->make('Faxbox\Repositories\Group\GroupInterface'),
                $app->make('Faxbox\Repositories\Permission\PermissionInterface')
            );
        });

    }
}
