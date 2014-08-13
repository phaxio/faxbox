<?php namespace Faxbox;

use Eloquent;
use Symfony\Component\HttpFoundation\File\File;

class Fax extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'faxes';
    
    protected $fillable = [
        'phaxio_id',
        'direction',
        'pages',
        'sent',
        'in_progress',
        'files',
        'status',
        'message',
        'completed_at'
    ];
    
    public function getFilesAttribute($value)
    {
        $files = unserialize($value);
        
        if(is_array($files))
        {
            foreach ($files as &$file)
            {
                $file = storage_path('docs/' . $file);
            }
        }
        
        return $files;
    }

    public function setFilesAttribute($files)
    {
        foreach($files as &$file)
        {
            if($file instanceof File){
                $file = $file->getFilename();
            } else{
                $file = str_replace(storage_path('docs/'), '', $file);
            }
            
        }

        $this->attributes['files'] = serialize($files);
    }

    public function user()
    {
        return $this->belongsTo('Faxbox\User');
    }
    
    public function number()
    {
        return $this->hasOne('Faxbox\Number');
    }
    
    public function phone()
    {
        return $this->belongsTo('Faxbox\Phone')->withTrashed();
    }
}