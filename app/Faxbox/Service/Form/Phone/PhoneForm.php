<?php namespace Faxbox\Service\Form\Phone;

use Faxbox\Repositories\Permission\PermissionInterface;
use Faxbox\Service\Validation\ValidableInterface;
use Faxbox\Repositories\Phone\PhoneInterface;

class PhoneForm {

    /**
     * Form Data
     *
     * @var array
     */
    protected $data;

    /**
     * Validator
     *
     * @var \Faxbox\Service\Form\ValidableInterface
     */
    protected $validator;

    /**
     * Group Repository
     *
     * @var \Faxbox\Repositories\Phone\PhoneInterface
     */
    protected $phone;

    /**
     * Permission Repository
     *
     * @var \Faxbox\Repositories\Permission\PermissionInterface
     */
    protected $permission;

    public function __construct(ValidableInterface $validator, PhoneInterface $phone, PermissionInterface $permission)
    {
        $this->validator = $validator;
        $this->phone = $phone;
        $this->permission = $permission;

    }

    /**
     * Create a new phone
     *
     * @return integer
     */
    public function save(array $input)
    {
        if( ! $this->valid($input) )
        {
            return false;
        }

        return $this->phone->store($input);
    }

    public function update(array $input)
    {
        $this->validator->setCurrent($input['id']);

        if( ! $this->valid($input) )
        {
            return false;
        }

        return $this->phone->update($input);
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