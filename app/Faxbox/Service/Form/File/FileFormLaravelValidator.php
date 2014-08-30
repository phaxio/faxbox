<?php namespace Faxbox\Service\Form\File;

use Faxbox\Service\Validation\AbstractLaravelValidator;

class FileFormLaravelValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'files' => 'required|fileTypeOrArray'
    );

    /**
     * Custom Validation Messages
     *
     * @var Array
     */
    protected $messages = array(
        'file_type' => 'Incorrect file type.'
    );
}