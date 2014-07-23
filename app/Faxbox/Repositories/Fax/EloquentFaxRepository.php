<?php namespace Faxbox\Repositories\Fax;

use Faxbox\Fax;
use Faxbox\Repositories\EloquentAbstractRepository;

class EloquentFaxRepository extends EloquentAbstractRepository implements FaxInterface {
    
    public function __construct(Fax $user)
    {
        $this->model = $user;
    }
    
}