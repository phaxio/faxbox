<?php namespace Faxbox\Repositories\Fax;

use Faxbox\Fax;
use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Repositories\Phone\PhoneInterface;
use Faxbox\Repositories\Recipient\RecipientInterface;
use Faxbox\Repositories\User\UserInterface;
use Faxbox\External\Api\FaxInterface as FaxApi;

class EloquentFaxRepository extends EloquentAbstractRepository implements FaxInterface {
    
    public function __construct(Fax $faxes, UserInterface $users, FaxApi $api, RecipientInterface $recipient, PhoneInterface $phone)
    {
        $this->model = $faxes;
        $this->users = $users;
        $this->api = $api;
        $this->recipient = $recipient;
        $this->phone = $phone;
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
    
    public function store($data)
    {
        $files = [];
        
        $data['number'] = $this->sanitizePhone($data['fullNumber']);
        $fax = $this->model->newInstance();
        
        $fax->user_id = $this->users->loggedInUserId();
        $fax->direction = $data['direction'];
        $fax->in_progress = true;
        $fax->files = $data['files'];
        $fax->save();

        if($data['direction'] == 'sent')
        {
            $recipient = $this->recipient->newInstance();
            $recipient->number = $data['number'];
            $recipient->country_code = strtoupper($data['toPhoneCountry']);
            
            $fax->recipient()->save($recipient);
        } else 
        {
            $phone = $this->phone->findByNumber($data['number']);
            $fax->phone()->associate($phone);
        }
        
        $fax->save();
        
        // Send it off to phaxio
        $apiResult = $this->api->sendFax($fax->recipient->number, $fax->files);
        
        $result['success'] = $apiResult->isSuccess();
        $result['message'] = $apiResult->getMessage();
        
        return $result;
    }

    private function sanitizePhone($number)
    {
        return preg_replace("/[^0-9]/", "", $number);
    }
}