<?php
namespace Faxbox;

use Eloquent;

class Fax extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'faxes';
    
    protected $fillable = [
        'phaxio_id',
        'recipient_id',
        'direction',
        'pages',
        'sent',
        'in_progress'
    ];

    public function user()
    {
        return $this->belongsTo('Faxbox\User');
    }
    
    public function recipients()
    {
        return $this->hasOne('Faxbox\Recipient');
    }
}