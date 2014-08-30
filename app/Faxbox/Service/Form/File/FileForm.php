<?php namespace Faxbox\Service\Form\File;

use Faxbox\Repositories\File\FileInterface;
use Faxbox\Service\Validation\ValidableInterface;

class FileForm {

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
     * File Repository
     *
     * @var \Faxbox\Repositories\File\FileInterface
     */
    protected $file;

    public function __construct(ValidableInterface $validator, FileInterface $file)
    {
        $this->validator = $validator;
        $this->file = $file;

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

        return $this->file->store($input);
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