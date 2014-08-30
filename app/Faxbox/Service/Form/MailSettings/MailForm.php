<?php namespace Faxbox\Service\Form\MailSettings;

use Faxbox\Repositories\Mail\MailInterface;
use Faxbox\Service\Validation\ValidableInterface;

class MailForm {

    /**
     * Form Data
     *
     * @var array
     */
    protected $data;

    /**
     * Validator
     *
     * @var \Faxbox\Service\Validation\ValidableInterface
     */
    protected $validator;

    /**
     * Fax Repository
     *
     * @var \Faxbox\Repositories\Mail\MailInterface
     */
    protected $mail;

    public function __construct(ValidableInterface $validator, MailInterface $mail)
    {
        $this->validator = $validator;
        $this->mail = $mail;

    }

    /**
     * Create a new fax
     *
     * @return integer
     */
    public function save(array $input)
    {
        if( ! $this->valid($input) )
        {
            return false;
        }

        return $this->mail->store($input);
    }

    /**
     * Return any validation errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * Test if form validator passes
     *
     * @return boolean
     */
    protected function valid(array $input)
    {
        // not pretty, but we have to put it here until php will allow closures in property defaults
//        $v = $this->validator->resolve();
//
//        $v->sometimes('mail.host', 'required|max:500', function($input)
//        {
//            return $input->games >= 100;
//        });
        
        return $this->validator->with($input)->passes();

    }


}