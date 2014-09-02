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
        'email' => 'required|unique:users,email,:current|email',
        'sent_notification' => 'in:never,failed,always',
        'received_notification' => 'in:never,groups,mine,always',
        'old_password' => '',
        'password' => 'min:6|confirmed|checkOldPassword',
	);

	/**
	 * Custom Validation Messages
	 *
	 * @var Array 
	 */
	protected $messages = array(
		'matches' => 'That is not your current password',
        'check_old_password' => 'Your current password is incorrect',
        'old_required_for_update' => 'You need to enter your old password in order to update it.'
	);
}