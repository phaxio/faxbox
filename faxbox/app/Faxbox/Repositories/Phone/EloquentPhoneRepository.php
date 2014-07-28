<?php namespace Faxbox\Repositories\Phone;

use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Repositories\User\UserInterface;
use Phaxio;
use Faxbox\Phone;

class EloquentPhoneRepository extends EloquentAbstractRepository implements PhoneInterface {
    
    public function __construct(Phone $phone, UserInterface $users)
    {
        $this->model = $phone;
        $this->users = $users;
    }
    
    public function all()
    {
        $ids = $this->users->resourceIds('Faxbox\Repositoires\Phone\PhoneInterface');
        dd($ids);
        return $this->model->all()->toArray();
    }
} 