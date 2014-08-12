<?php namespace Faxbox\Repositories\Number;

use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Number;

class EloquentNumberRepository extends EloquentAbstractRepository implements NumberInterface, NumberRepository {

    public function __construct(Number $recipient)
    {
        $this->model = $recipient;
    }
    
    public function newInstance()
    {
        return $this->model->newInstance();
    }
    
} 