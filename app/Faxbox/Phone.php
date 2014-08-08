<?php
namespace Faxbox;

use Eloquent;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Phone extends Eloquent {

    use SoftDeletingTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'phones';
    
    protected $fillable = [
        'description',
        'number',
        'city',
        'state',
        'country_code'
    ];

    public function fax()
    {
        return $this->hasMany('Faxbox\Fax');
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