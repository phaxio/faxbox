<?php namespace Faxbox\Repositories\Phone;

use Faxbox\External\Api\FaxInterface;
use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Repositories\Group\GroupInterface;
use Faxbox\Repositories\Permission\PermissionInterface;
use Faxbox\Repositories\User\UserInterface;
use Phaxio;
use Faxbox\Phone;
use Faxbox\Repositories\Setting\SettingInterface;

class EloquentPhoneRepository extends EloquentAbstractRepository implements PhoneInterface {

    public function __construct(
        Phone $phone,
        UserInterface $users,
        PermissionInterface $permissions,
        GroupInterface $groups,
        FaxInterface $api,
        SettingInterface $setting
    ) {
        $this->phones      = $phone;
        $this->users       = $users;
        $this->permissions = $permissions;
        $this->groups      = $groups;
        $this->api         = $api;
        $this->setting = $setting;
    }

    public function all()
    {
        return $this->phones->orderBy('created_at', 'DESC')->get()->toArray();
    }

    public function byId($id)
    {
        return $this->phones->findOrFail($id)->toArray();
    }

    public function store($data)
    {
        $apiResult = $this->api->createPhone(
            $data['area'],
            $this->setting->get('faxbox.notify.fax')
        );

        $apiData = $apiResult->getData();

        if (!$apiResult->isSuccess())
        {
            $result['success'] = false;
            $result['message'] = $apiResult->getMessage();

            return $result;
        }

        $phone = $this->phones->newInstance();

        $phone->description  = $data['description'];
        $phone->number       = "1" . $apiData['number']; // need to add 1 in front since it comes back without it (and right now only usa numbers are supported for purchasing)
        $phone->city         = $apiData['city'];
        $phone->state        = $apiData['state'];
        $phone->country_code = "US";

        $result['success'] = $phone->save();

        if ($result['success'])
        {

            if (isset($data['groups']))
            {
                $permission = \Permission::name(
                    'Faxbox\Repositories\Phone\PhoneInterface',
                    'view',
                    $phone->id
                );

                foreach ($data['groups'] as $id => $access)
                {
                    $group = $this->groups->byId($id);

                    $group->permissions = [
                        $permission => $access
                    ];

                    $group->save();
                }
            }

            $admin = \Permission::name(
                'Faxbox\Repositories\Phone\PhoneInterface',
                'admin',
                $phone->id
            );

            // make the user who created this an admin for this phone number
            $user              = \Sentry::getUser(); //todo tsk tsk should do this via repo
            $user->permissions = [
                $admin => 1
            ];

            $user->save();

            $result['message'] = trans('phones.successcreate');

            return $result;
        }

        $result['message'] = trans('phones.failedcreate');

        return $result;
    }


    public function update($data)
    {
        $phone = $this->phones->findOrFail($data['id']);

        $phone->fill($data);

        $permission = \Permission::name('Faxbox\Repositories\Phone\PhoneInterface',
            'view',
            $data['id']);

        if (isset($data['groups']))
        {
            foreach ($data['groups'] as $id => $access)
            {
                $group = $this->groups->byId($id);

                $group->permissions = [
                    $permission => $access
                ];

                $group->save();
            }
        }

        if ($phone->save())
        {
            $result['success'] = true;
            $result['message'] = trans('phones.updated');
        } else
        {
            $result['success'] = false;
            $result['message'] = trans('phones.updateproblem');
        }

        return $result;
    }
    
    public function destroy($id)
    {
        $phone = $this->phones->findOrFail($id);
        $number = $this->sanitizePhone($phone->number);
        $apiResult = $this->api->deletePhone($number);
        
        if($apiResult->isSuccess())
        {
            $phone->delete();
            $result['success'] = true;
            $result['message'] = 'Successfully deleted ' . $phone->number;
        } else
        {
            $result['success'] = false;
            $result['message'] = $apiResult->getMessage();
        }
        
        return $result;
    }

    public function getAvailableAreaCodes()
    {
        $areas = $this->api->getAvailableAreaCodes()->getData();

        foreach ($areas as $key => &$value)
        {
            $value = $key . " - " . implode(' ', $value);
        }

        return $areas;
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

        if ($user->isSuperUser())
        {
            return $this->all();
        } else
        {
            $permissions     = $user->getMergedPermissions();
            $allowedPhoneIds = $this->permissions->allowedResourceIds('admin',
                'Faxbox\Repositories\Phone\PhoneInterface',
                $permissions);

            if (!$allowedPhoneIds) return []; // must be a bug in laravel passing empty array into IN statement

            $phones = $this->phones->whereIn('id', $allowedPhoneIds);

            return $phones->orderBy('created_at', 'DESC')->get()->toArray();
        }
    }

    public function findByNumber($number, $eloquent = false)
    {
        $phone = $this->phones->where('number', '=', $number)->first();

        if (!$eloquent) $phone = $phone->toArray();

        return $phone;
    }

    private function sanitizePhone($number)
    {
        return preg_replace("/[^0-9]/", "", $number);
    }
}