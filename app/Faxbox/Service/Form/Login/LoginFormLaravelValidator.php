<?php namespace Faxbox\Service\Form\Login;

use Faxbox\Service\Validation\AbstractLaravelValidator;

class LoginFormLaravelValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'email' => 'required|min:4|max:255|email',
        'password' => 'required|min:6'
    );

    /**
     * Custom Validation Messages
     *
     * @var Array
     */
    protected $messages = array(
        //'email.required' => 'An email address is required.'
    );
}