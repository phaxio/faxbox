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
    ];

    public function fax()
    {
        return $this->hasMany('Faxbox\Fax');
    }
}