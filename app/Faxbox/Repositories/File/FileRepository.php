<?php namespace Faxbox\Repositories\File;

use File;
use Illuminate\Support\Str;
use Faxbox\Repositories\EloquentAbstractRepository;

class FileRepository extends EloquentAbstractRepository implements FileInterface{

    protected $file;
    protected $str;

    public function __construct(File $file, Str $str) {
        $this->file = $file;
        $this->str = $str;
    }

    /**
     * @param $files \Symfony\Component\HttpFoundation\File\UploadedFile|array
     *
     * @return array
     */
    public function store($files)
    {
        $names = [];
        
        foreach ($files['files'] as &$file)
        {
            $names[] = $name = $this->str->random('32') . "." . $file->guessExtension();
            $file->move(storage_path('docs'), $name);
        }
        
        return $names;
    }
}