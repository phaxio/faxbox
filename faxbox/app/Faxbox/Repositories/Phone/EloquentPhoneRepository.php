<?php namespace Faxbox\Repositories\Phone;

use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Repositories\Permission\PermissionInterface;
use Faxbox\Repositories\User\UserInterface;
use Phaxio;
use Faxbox\Phone;

class EloquentPhoneRepository extends EloquentAbstractRepository implements PhoneInterface {
    
    public function __construct(Phone $phone, UserInterface $users)
    {
        $this->phones = $phone;
//        $this->users = $users;
//        $this->permissions = $permissions;
    }
    
    public function all()
    {
        return $this->phones->all()->toArray();
    }

    /**
     * Gets all phone numbers the user has access to.
     *
     * @param integer $userId
     *
     * @return array An array of phone numbers.
     */
    public function findByUserId($userId)
    {
        $user = $this->users->byId($userId);
        
        if($user->isSuperUser())
        {
            return $this->all();
        } 
        else
        {
            $permissions = $user->getMergedPermissions();
            $allowedPhoneIds = $this->permissions->allowedResourceIds('view',
                'Faxbox\Repositories\Phone\PhoneInterface',
                $permissions);

            $phones = $this->phones->whereIn('id', $allowedPhoneIds);

            return $phones->orderBy('created_at', 'DESC')->get()->toArray();
        }
    }
} 