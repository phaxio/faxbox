<?php namespace Faxbox\Service\Form\MailSettings;

use Faxbox\Service\Validation\AbstractLaravelValidator;

class MailFormLaravelValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'mail.from.address' => 'required',
        'mail.from.name' => 'required',
        'mail.driver' => 'required|in:smtp,mail,sendmail,mailgun',
        'services.mailgun.secret' => 'sometimes|required|mailgun'
    );

    /**
     * Custom Validation Messages
     *
     * @var Array
     */
    protected $messages = array(
        'file_type' => 'Incorrect file type.',
        'fileNames.required' => 'You need at least 1 file to send a fax.',
        'mailgun' => 'Your Mailgun API credentials are incorrect',
        'mail.from.address.required' => "The from address is required",
        'services.mailgun.secret' => 'The Mailgun api key is required.'
    );
    
}