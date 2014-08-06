<?php namespace Faxbox\Repositories\Fax;

use Faxbox\Fax;
use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Repositories\Phone\PhoneInterface;
use Faxbox\Repositories\Recipient\RecipientInterface;
use Faxbox\Repositories\User\UserInterface;
use Faxbox\External\Api\FaxInterface as FaxApi;

class EloquentFaxRepository extends EloquentAbstractRepository implements FaxInterface {

    public function __construct(
        Fax $faxes,
        UserInterface $users,
        FaxApi $api,
        RecipientInterface $recipient,
        PhoneInterface $phone
    ) {
        $this->model     = $faxes;
        $this->users     = $users;
        $this->api       = $api;
        $this->recipient = $recipient;
        $this->phone     = $phone;
    }

    public function all()
    {
        return $this->model
            ->with(['recipient', 'phone', 'user'])
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();
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
            ->with(['recipient', 'phone', 'user'])
            ->where('user_id', '=', $userId);

        if (count($allowedPhoneIds))
            $faxes->orWhereIn('phone_id', $allowedPhoneIds);

        return $faxes->orderBy('created_at', 'DESC')->get()->toArray();
    }

    public function byId($id)
    {
        $fax = $this->model
            ->with(['recipient', 'phone', 'user'])
            ->findOrFail($id);

        $userId = $this->users->loggedInUserId();
        
        $this->canAccess($fax, $userId);
        
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
        if ( in_array($fax->phone_id, $allowedPhoneIds) || 
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
            $recipient               = $this->recipient->newInstance();
            $recipient->number       = $data['number'];
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