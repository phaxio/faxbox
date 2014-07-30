<?php namespace Faxbox\Service\Form\ResetPassword;

use Faxbox\Service\Validation\AbstractLaravelValidator;

class ResetPasswordFormLaravelValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'id' => 'required|numeric',
        'code' => 'required',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required'
    );

    /**
     * Custom Validation Messages
     *
     * @var Array
     */
    protected $messages = array(
        'password.required' => 'You must enter a new password.',
        'password.min' => 'Your new password must be at least 6 characters long.',
        'password_confirmation.required' => 'You must confirm your new password.'
    );
}