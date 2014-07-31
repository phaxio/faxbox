<?php namespace Faxbox\Service\Form\User;

use Faxbox\Service\Validation\AbstractLaravelValidator;

class UserFormLaravelValidator extends AbstractLaravelValidator {
	
	/**
	 * Validation rules
	 *
	 * @var Array 
	 */
	protected $rules = array(
		'first_name' => 'alpha|required',
        'last_name' => 'alpha|required',
        'email' => 'required|unique:users,:current|email',
        //'password' => 'required|confirmed|min:6',
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