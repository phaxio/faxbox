<?php namespace Faxbox\Service\Form\User;

use Faxbox\Service\Validation\AbstractLaravelValidator;

class UserFormLaravelValidator extends AbstractLaravelValidator {
	
	/**
	 * Validation rules
	 *
	 * @var Array 
	 */
	protected $rules = array(
		'firstName' => 'alpha|required',
        'lastName' => 'alpha|required',
        'email' => 'required|unique:email,:current',
        'password' => 'required|confirmed|min:6',
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