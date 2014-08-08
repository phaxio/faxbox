<?php namespace Faxbox\Service\Validation;

use Illuminate\Validation\Factory;

abstract class AbstractLaravelValidator	implements ValidableInterface {

    /**
     * Validator
     *
     * @var \Illuminate\Validation\Factory
     */
    protected $validator;

    /**
     * Validation data key => value array
     *
     * @var Array
     */
    protected $data = array();

    /**
     * Validation errors
     *
     * @var Array
     */
    protected $errors = array();

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array();
    
    protected $currentId = null;

    /**
     * Custom Validation Messages
     *
     * @var Array
     */
    protected $messages = array();

    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Set data to validate
     *
     * @return \Faxbox\Service\Validation\AbstractLaravelValidator
     */
    public function with(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Validation passes or fails
     *
     * @return boolean
     */
    public function passes()
    {
        //for updates and unique validation, ignore the current id or other use
        //first you must set the currentId
//        if($this->currentId) {
            foreach ($this->rules as $key => &$value) {
                $value = str_replace(':current', $this->currentId ?: "", $value);
            }
//        }
        
        $validator = $this->validator->make($this->data, $this->rules, $this->messages);

        if ($validator->fails() )
        {
            $this->errors = $validator->messages();
            return false;
        }


        return true;
    }
    
    public function setCurrent($id)
    {
        $this->currentId = $id;
        return $this;
    }

    /**
     * Return errors, if any
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

}