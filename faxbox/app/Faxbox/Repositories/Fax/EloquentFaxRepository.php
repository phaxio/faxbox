<?php namespace Faxbox\Repositories\Fax;

use Faxbox\Fax;
use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Repositories\User\UserInterface;

class EloquentFaxRepository extends EloquentAbstractRepository implements FaxInterface {
    
    public function __construct(Fax $faxes, UserInterface $users)
    {
        $this->model = $faxes;
        $this->users = $users;
    }
    
    public function findByUserId($id)
    {
        return $this->model->with(['recipients', 'number'])->where('user_id', $id)->get()->toArray();
    }
}