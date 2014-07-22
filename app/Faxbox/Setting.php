<?php
namespace Faxbox;

use Eloquent;

class Setting extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';
    
    protected $fillable = ['name', 'value'];
}