<?php namespace Faxbox\Repositories\Phone;

use Faxbox\Repositories\EloquentAbstractRepository;
use Phaxio;
use Faxbox\Phone;

class EloquentPhoneRepository extends EloquentAbstractRepository implements PhoneInterface {
    
    public function __construct(Phone $phone)
    {
        $this->model = $phone;
    }
    
    public function all()
    {
        return $this->model->all()->toArray();
    }
} 