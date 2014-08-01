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
    
    public function all()
    {
        return $this->model->with(['recipient', 'phone', 'user'])->orderBy('created_at', 'DESC')->get()->toArray();
    }

    /**
     * Gets all the sent and received faxes that a user has access to.
     * 
     * @param integer $userId
     *
     * @return array An array of faxes including the recipient, phone number, and user
     */
    public function findByUserId($userId)
    {
        $permissions = $this->users->byId($userId)->getMergedPermissions();
        $allowedPhoneIds = $this->users->allowedResourceIds('view', 'Faxbox\Repositories\Phone\PhoneInterface', $permissions);

        $faxes = $this->model
            ->with(['recipient', 'phone', 'user'])
            ->where('user_id', '=', $userId);
        
        if(count($allowedPhoneIds))
            $faxes->orWhereIn('phone_id', $allowedPhoneIds);
        
        return $faxes->orderBy('created_at', 'DESC')->get()->toArray();
    }
}