<?php

use Faxbox\Repositories\User\UserInterface as Users;
use Faxbox\Repositories\Permission\PermissionRepository as Permissions;
use Faxbox\Repositories\Phone\PhoneInterface;
use Faxbox\Service\Form\Phone\PhoneForm;
use Faxbox\Repositories\Fax\FaxInterface;
use Faxbox\Repositories\Group\GroupInterface;

class PhoneController extends BaseController {

    protected $phones;
    protected $phoneForm;
    protected $faxes;
    protected $users;
    
    public function __construct(
        PhoneInterface $phones,
        PhoneForm $phoneForm,
        FaxInterface $faxes,
        Users $users,
        GroupInterface $groups
    ) {
        parent::__construct();

        $this->users = $users;
        $this->phones = $phones;
        $this->faxes = $faxes;
        $this->phoneForm = $phoneForm;
        $this->groups = $groups;

        $id = Route::input('phones');

        $resource = 'Faxbox\Repositories\Phone\PhoneInterface';
        $admin    = Permissions::name($resource, 'admin', $id);

        $this->beforeFilter('auth');

        $this->beforeFilter('accessResource:' . $admin, ['only' => ['delete']]);
        $this->beforeFilter('accessResource:purchase_numbers', ['except' => ['delete']]);
    }

    public function index()
    {
        $user = Sentry::getUser();

        $phones = $this->phones->findByUserId($user->getId());

        $this->view('phones.list', compact('phones'));
    }

    public function create()
    {
        $groups = $this->groups->all();
        $area = $this->phones->getAvailableAreaCodes();
        $this->view('phones.create', compact('groups', 'area'));
    }

    public function store()
    {
        $data = Input::all();

        // Form Processing
        $result = $this->phoneForm->save($data);

        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            return Redirect::action('PhoneController@index');

        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::action('PhoneController@create')
                           ->withInput()
                           ->withErrors($this->phoneForm->errors());
        }
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $permission = Permission::name('Faxbox\Repositories\Phone\PhoneInterface', 'view', $id);
        $groups = $this->groups->allWithChecked($permission);
        
        $phone = $this->phones->byId($id);
        
        $this->view('phones.edit', compact('phone', 'groups'));
    }

    public function update($id)
    {
        $data = [
            'id' => $id,
            'description' => Input::get('description'),
            'groups' => Input::get('groups')
        ];

        // Form Processing
        $result = $this->phoneForm->update($data);

        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            return Redirect::action('PhoneController@index');

        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::action('PhoneController@edit')
                           ->withInput()
                           ->withErrors($this->phoneForm->errors());
        }
    }

    public function delete()
    {

    }

}
