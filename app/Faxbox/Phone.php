<?php
namespace Faxbox;

use Eloquent;

class Phone extends Eloquent {

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
        return $this->belongsTo('Faxbox\Fax');
    }
}