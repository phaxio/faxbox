<?php namespace Faxbox\Service\Validation;

use Illuminate\Validation\Validator;

class CustomLaravelValidator extends Validator {

    public function validatePermissionAvailable($attribute, $value, $parameters)
    {
        $permissions = \Permission::allIds();
        return in_array(array_keys($value), $permissions);
    }
}