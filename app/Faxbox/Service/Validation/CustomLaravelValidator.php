<?php namespace Faxbox\Service\Validation;

use Illuminate\Validation\Validator;
use libphonenumber\PhoneNumberUtil;
use Mailgun\Connection\Exceptions\GenericHTTPError;
use Mailgun\Mailgun;
use Symfony\Component\HttpFoundation\File\File as File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    /**
     * @param $attribute
     * @param $value UploadedFile|File
     * @param $parameters
     */
    public function validateFileType($attribute, $value, $parameters)
    {
        $allowed = \Config::get('faxbox.supportedFiles');
        $allowedMimes = array_column($allowed, 'mime');
        $allowedExts = array_column($allowed, 'ext');

        if(is_string($value))
        {
            $file = \App::make('Faxbox\Repositories\File\FileInterface');
            $value = new File($file->getFilePath($value));
        }
        
        if($value instanceof File)
        {
            $mime = $value->getMimeType();
        }else if($value instanceof UploadedFile)
        {
            $mime = $value->getClientMimeType();    
        }else
        {
            return false;
        }
        
        $ext = $value->getExtension() ?: $value->guessExtension();
        
        return in_array($mime, $allowedMimes) && in_array($ext, $allowedExts);
    }
    
    public function validatePhone($attribute, $value, $parameters)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $number = $phoneUtil->parse($value, null);
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }

        return $phoneUtil->isValidNumber($number);
    }
    
    public function validateCountry($attribute, $value, $parameters)
    {
        $allowed = \Config::get('faxbox.phone');
        $allowed = array_column($allowed, 'short');
        
        return in_array(strtolower($value), $allowed);
        
    }
    
    public function validateCheckOldPassword($attribute, $value, $parameters)
    {
        return \Sentry::getUser()->checkPassword(\Input::get('old_password', null));
    }
    
    public function validateMailgun($attribute, $value, $parameters)
    {
        $mgClient = new Mailgun($value);

        try
        {
            $result = $mgClient->get("domains");
        } catch(\Mailgun\Connection\Exceptions\InvalidCredentials $e)
        {
            return false;
        } catch(GenericHTTPError $e)
        {
            return false;
        }
        
        return true;
    }

    /**
     * Magically adds validation methods. Normally the Laravel Validation methods
     * only support single values to be validated like 'numeric', 'alpha', etc.
     * Here we copy those methods to work also for arrays, so we can validate
     * if a value is OR an array contains only 'numeric', 'alpha', etc. values.
     *
     * $rules = array(
     *     'row_id' => 'required|integerOrArray', // "row_id" must be an integer OR an array containing only integer values
     *     'type'   => 'inOrArray:foo,bar' // "type" must be 'foo' or 'bar' OR an array containing nothing but those values
     * );
     *
     * @param string $method Name of the validation to perform e.g. 'numeric', 'alpha', etc.
     * @param array $parameters Contains the value to be validated, as well as additional validation information e.g. min:?, max:?, etc.
     */
    public function __call($method, $parameters)
    {
        // Convert method name to its non-array counterpart (e.g. validateNumericArray converts to validateNumeric)
        if (substr($method, -7) === 'OrArray')
            $method = substr($method, 0, -7);

        // Call original method when we are dealing with a single value only, instead of an array
        if (! is_array($parameters[1]))
            return call_user_func_array([$this, $method], $parameters);

        $success = true;
        foreach ($parameters[1] as $value) {
            $parameters[1] = $value;
            $success &= call_user_func_array([$this, $method], $parameters);
        }

        return $success;

    }

    /**
     * All ...OrArray validation functions can use their non-array error message counterparts
     *
     * @param mixed $attribute The value under validation
     * @param string $rule Validation rule
     */
    protected function getMessage($attribute, $rule)
    {

        if (substr($rule, -7) === 'OrArray')
            $rule = substr($rule, 0, -7);

        return parent::getMessage($attribute, $rule);

    }
}