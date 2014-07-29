<?php namespace Faxbox\Service\Form\Group;

use Faxbox\Service\Validation\AbstractLaravelValidator;

class GroupFormLaravelValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'name' => 'required|max:255|unique:groups,:current',
        'permissions' => 'permissionAvailable'
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