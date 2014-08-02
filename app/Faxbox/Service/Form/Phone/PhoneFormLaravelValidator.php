<?php namespace Faxbox\Service\Form\Phone;

use Faxbox\Service\Validation\AbstractLaravelValidator;

class PhoneFormLaravelValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'description' => 'required|max:255',
        'number' => 'numeric|unique:phones,:current'
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