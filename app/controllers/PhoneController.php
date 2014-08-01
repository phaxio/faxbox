<?php

use Faxbox\Repositories\User\UserInterface as Users;
use Faxbox\Repositories\Permission\PermissionRepository as Permissions;
use Faxbox\Repositories\Phone\PhoneInterface;
use Faxbox\Repositories\Fax\FaxInterface;

class PhoneController extends BaseController {

    public function __construct(
        PhoneInterface $phones,
        FaxInterface $faxes,
        Users $users
    ) {
        parent::__construct();

        $this->users = $users;
        $this->phones = $phones;
        $this->faxes = $faxes;

        // get the ID, not pretty but works for now.
        $id = Request::segment(2) ?: null;

        $resource = 'Faxbox\Repositories\Phone\PhoneInterface';
        $admin   = Permissions::name($resource, 'admin', $id);
        $view     = Permissions::name($resource, 'view', $id);

        $this->beforeFilter('auth');
        
        $this->beforeFilter('accessResource:' . $view, ['only' => ['show']]);
        $this->beforeFilter('accessResource:' . $admin, ['only' => ['delete']]);
        $this->beforeFilter('accessResource:purchase_numbers', ['only' => ['index', 'create', 'store', 'edit', 'update']]);
    }

    public function index()
    {

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

    }

    public function update($id)
    {

    }

    public function delete()
    {

    }

}
