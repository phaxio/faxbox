<?php namespace Faxbox;

use Eloquent;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Number extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'numbers';
    
    protected $fillable = ['number', 'name', 'country_code'];

    public function fax()
    {
        return $this->belongsTo('Faxbox\Fax');
    }

    public function getNumberAttribute($value)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        
        $value = $this->clean($value);
        
        try {
            $number = $phoneUtil->parse($value, $this->country_code ?: "");
        } catch (\libphonenumber\NumberParseException $e) {
            return $value;
        }
        return $phoneUtil->format($number, PhoneNumberFormat::INTERNATIONAL);
    }

    private function clean($number){
        $startsWithPlus = substr($number, 0, 1) === '+';
        $number = preg_replace ('/[^\d]/', '', $number);

        if ($startsWithPlus){
            $number = '+' . $number;
        }
        else if (strlen($number) == 10){
            $number = '+1' . $number;
        }
        else {
            $number = '+' . $number;
        }

        return $number;
    }
}