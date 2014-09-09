<?php namespace Faxbox\Service\Form\Fax;

use Faxbox\Service\Validation\AbstractLaravelValidator;

class FaxFormLaravelValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'toPhoneCountry' => 'country',
        'fullNumber' => 'required|phone',
        'fileNames' => 'required'
    );

    /**
     * Custom Validation Messages
     *
     * @var Array
     */
    protected $messages = array(
        'file_type' => 'Incorrect file type.',
        'fileNames.required' => 'You need at least 1 file to send a fax.'
    );
}