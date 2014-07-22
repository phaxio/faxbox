<?php
namespace Faxbox;

use Eloquent;

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
}