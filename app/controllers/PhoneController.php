<?php

use Faxbox\Repositories\User\UserInterface as Users;
use Faxbox\Repositories\Permission\PermissionRepository as Permissions;
use Faxbox\Repositories\Phone\PhoneInterface;
use Faxbox\Service\Form\Phone\PhoneForm;
use Faxbox\Repositories\Fax\FaxInterface;

class PhoneController extends BaseController {

    protected $phones;
    protected $phoneForm;
    protected $faxes;
    protected $users;
    
    public function __construct(
        PhoneInterface $phones,
        PhoneForm $phoneForm,
        FaxInterface $faxes,
        Users $users
    ) {
        parent::__construct();

        $this->users = $users;
        $this->phones = $phones;
        $this->faxes = $faxes;
        $this->phoneForm = $phoneForm;

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

    }

    public function store()
    {

    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $phone = $this->phones->byId($id);
        $this->view('phones.edit', compact('phone'));
    }

    public function update($id)
    {
        $data = [
            'id' => $id,
            'description' => Input::get('description')
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
                           ->withErrors($this->groupForm->errors());
        }
    }

    public function delete()
    {

    }

}
