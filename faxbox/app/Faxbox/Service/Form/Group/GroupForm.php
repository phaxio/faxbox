<?php namespace Faxbox\Service\Form\Group;

use Faxbox\Repositories\Permission\PermissionInterface;
use Faxbox\Service\Validation\ValidableInterface;
use Faxbox\Repositories\Group\GroupInterface;

class GroupForm {

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
     * @var \Faxbox\Repositories\Group\GroupInterface
     */
    protected $group;

    /**
     * Permission Repository
     *
     * @var \Faxbox\Repositories\Permission\PermissionInterface
     */
    protected $permission;

    public function __construct(ValidableInterface $validator, GroupInterface $group, PermissionInterface $permission)
    {
        $this->validator = $validator;
        $this->group = $group;
        $this->permission = $permission;

    }

    /**
     * Create a new session
     *
     * @return integer
     */
    public function save(array $input)
    {
        if( ! $this->valid($input) )
        {
            return false;
        }

        return $this->group->store($input);
    }
    
    public function update(array $input)
    {
        $this->validator->setCurrent($input['id']);
        
        if( ! $this->valid($input) )
        {
            return false;
        }

        return $this->group->update($input);
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