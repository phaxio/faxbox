<?php namespace Faxbox\Service\Validation;

use Illuminate\Validation\Validator;

class CustomLaravelValidator extends Validator {

    /**
     * Make sure that this permission name is actually valid
     * 
     * @param $attribute
     * @param $value
     * @param $parameters
     *
     * @return bool
     */
    public function validatePermissionAvailable($attribute, $value, $parameters)
    {
        $permissions = \Permission::allIds();
        $result = array_diff(array_keys($value), $permissions);

        return (count($result) === 0);
    }
}