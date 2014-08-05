<?php
namespace Faxbox;

use Eloquent;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Recipient extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recipients';
    
    protected $fillable = ['number', 'name', 'country_code'];

    public function fax()
    {
        return $this->belongsTo('Faxbox\Fax');
    }

    public function getNumberAttribute($value)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $number = $phoneUtil->parse($value, $this->country_code);
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
        return $phoneUtil->format($number, PhoneNumberFormat::E164);
    }
}