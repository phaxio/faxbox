<?php
namespace Faxbox;

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
        
        try {
            $number = $phoneUtil->parse($value, $this->country_code ?: "US");
        } catch (\libphonenumber\NumberParseException $e) {
            return $value;
        }
        return $phoneUtil->format($number, PhoneNumberFormat::INTERNATIONAL);
    }
}