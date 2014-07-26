<?php

use Faxbox\Repositories\User\UserInterface as Users;
use Faxbox\Repositories\Fax\FaxInterface;

class FaxController extends BaseController {

    public function __construct(FaxInterface $faxes, Users $users)
    {
        parent::__construct();

        $this->users = $users;
        $this->faxes = $faxes;
        
        $this->beforeFilter('auth');
        $this->beforeFilter('hasAccess:send_fax', ['only' => ['store', 'create']]);
    }

    public function index()
    {
        // todo this should be moved into the repo
        $user = Sentry::getUser();

        if ($this->users->isAdmin($user->getId()))
        {
            $faxes = $this->faxes->all();
        } else
        {
            $faxes = $this->faxes->findByUserId($user->getId());
        }

        $this->view('fax.list', $faxes);
    }
    
    public function all()
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
