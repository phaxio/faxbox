<?php

use Faxbox\Repositories\Setting\SettingInterface;

class SettingController extends BaseController {

    protected $settings;
    
	public function __construct(SettingInterface $settings)
	{
        parent::__construct();
        
        $this->settings = $settings;

        $this->beforeFilter('auth');
        $this->beforeFilter('hasAccess:superuser');
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

    public function show()
    {

    }

    public function editMail()
    {
        $settings = $this->settings->get([
            'mail.driver',
            'mail.port',
            'mail.host',
            'mail.username',
            'mail.password',
            'mail.from.address',
            'mail.from.name',
            'services.mailgun.domain',
            'services.mailgun.secret'
        ]);
        $this->view('settings.mail', compact('settings'));
    }

    public function updateMail()
    {
        $data = Input::all();
        
         /* TODO: fix this up for security. shouldn't let admin just write 
         anything to the DB. but for now its ok since it's only the admin 
         being allowed to do this anyways */
        $this->settings->writeArray($data);

        Session::flash('success', "Mail settings successfully updated");
        return Redirect::action('SettingController@editMail');
    }

    public function delete()
    {

    }

}
