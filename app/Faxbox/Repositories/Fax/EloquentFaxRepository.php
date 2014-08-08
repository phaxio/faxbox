<?php namespace Faxbox\Repositories\Fax;

use Faxbox\Fax;
use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Repositories\Phone\PhoneInterface;
use Faxbox\Repositories\Number\NumberInterface;
use Faxbox\Repositories\User\UserInterface;
use Faxbox\External\Api\FaxInterface as FaxApi;

class EloquentFaxRepository extends EloquentAbstractRepository implements FaxInterface {

    protected $faxes;
    protected $users;
    protected $api;
    protected $number;
    protected $phone;

    public function __construct(
        Fax $faxes,
        UserInterface $users,
        FaxApi $api,
        NumberInterface $number,
        PhoneInterface $phone
    ) {
        $this->model  = $faxes;
        $this->users  = $users;
        $this->api    = $api;
        $this->number = $number;
        $this->phone  = $phone;
    }

    public function all()
    {
        return $this->model
            ->with(['number', 'phone', 'user'])
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();
    }

    /**
     * Gets all the sent and received faxes that a user has access to.
     *
     * @param integer $userId
     *
     * @return array An array of faxes including the number, phone number, and user
     */
    public function findByUserId($userId)
    {
        // admin can view any fax
        if ($this->users->isAdmin($userId))
            return $this->all();

        // check users permissions to see which faxes he has access to
        $permissions     = $this->users->byId($userId)->getMergedPermissions();
        $allowedPhoneIds = $this
            ->users
            ->allowedResourceIds(
                'view',
                'Faxbox\Repositories\Phone\PhoneInterface',
                $permissions
            );

        $faxes = $this->model
            ->with(['number', 'phone', 'user'])
            ->where('user_id', '=', $userId);

        if (count($allowedPhoneIds))
            $faxes->orWhereIn('phone_id', $allowedPhoneIds);

        return $faxes->orderBy('created_at', 'DESC')->get()->toArray();
    }

    public function byId($id, $checkAccess = true)
    {
        $fax = $this->model
            ->with(['number', 'phone', 'user'])
            ->findOrFail($id);

        if ($checkAccess)
        {
            $userId = $this->users->loggedInUserId();
            $this->canAccess($fax, $userId);
        }

        return $fax;
    }

    protected function canAccess(Fax $fax, $userId)
    {
        if ($this->users->isAdmin($userId)) return true;

        $permissions = $this->users->byId($userId)->getMergedPermissions();

        $allowedPhoneIds = $this
            ->users
            ->allowedResourceIds(
                'view',
                'Faxbox\Repositories\Phone\PhoneInterface',
                $permissions
            );

        // The user only has access if they can view faxes from the incoming 
        // number, or they sent the fax.
        if (in_array($fax->phone_id, $allowedPhoneIds) ||
            $fax->user_id == $userId
        ) return true;

        \App::abort('403', trans('user.unauthorized'));

    }

    public function download($id, $type = 'l')
    {
        $fax    = $this->byId($id);
        $result = $this->api->download($fax['phaxio_id'], $type);

        return $result;
    }

    public function store($data)
    {
        $files = [];

        $data['number'] = $this->sanitizePhone($data['fullNumber']);
        $fax            = $this->model->newInstance();

        $fax->user_id     = $this->users->loggedInUserId();
        $fax->direction   = $data['direction'];
        $fax->in_progress = true;
        $fax->files       = $data['files'];
        $fax->save();

        if ($data['direction'] == 'sent')
        {
            $number               = $this->number->newInstance();
            $number->number       = $data['number'];
            $number->country_code = strtoupper($data['toPhoneCountry']);

            $fax->number()->save($number);
        } else
        {
            $phone = $this->phone->findByNumber($data['number']);
            $fax->phone()->associate($phone);
        }

        $fax->save();

        // Send it off to phaxio
        $options   = [
            'tags'         => ['id' => $fax->id],
            'callback_url' => \Config::get('faxbox.notify.fax')
        ];
        $apiResult = $this->api->sendFax($fax->number->number,
            $fax->files,
            $options);

        $result['success'] = $apiResult->isSuccess();
        $result['message'] = $apiResult->getMessage();

        return $result;
    }

    public function update($data)
    {
        $fax = $this->model->findOrFail($data['id']);
        $fax->fill($data)->save();

        return $fax->toArray();
    }

    public function createReceived($data)
    {
        $fax = $this->model->newInstance();
        
        if(isset($data['phone']))
        {
            $data['phone'] = $this->sanitizePhone($data['phone']);
            $phone = $this->phone->findByNumber($data['phone'], true);
            $fax->phone()->associate($phone);
        }

        
        $fax->fill($data);
        $fax->save();

        if(isset($data['number']))
        {
            $data['number'] = $this->sanitizePhone($data['number']);
            $number = $this->number->newInstance();
            $number->number = $data['number'];
            $fax->number()->save($number);
        }
        
        return $fax->toArray();
    }

    private function sanitizePhone($number)
    {
        return preg_replace("/[^0-9]/", "", $number);
    }
}