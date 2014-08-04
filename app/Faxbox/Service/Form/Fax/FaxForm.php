<?php namespace Faxbox\Service\Form\Fax;

use Faxbox\Repositories\Fax\FaxInterface;
use Faxbox\Service\Validation\ValidableInterface;

class FaxForm {

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
     * Session Repository
     *
     * @var \Faxbox\Repositories\Fax\FaxInterface
     */
    protected $fax;

    public function __construct(ValidableInterface $validator, FaxInterface $fax)
    {
        $this->validator = $validator;
        $this->fax = $fax;

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

        return $this->fax->store($input);
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

        return $this->validator->with($input)->passes();

    }


}