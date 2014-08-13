<?php

use Faxbox\Repositories\Setting\SettingInterface;
use Mailgun\Mailgun;

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
        
        if($data['mail']['driver'] == 'mailgun')
        {
            Event::fire('update.mailgun.route');
        }
        
        $this->settings->writeArray($data);

        Session::flash('success', "Mail settings successfully updated");
        return Redirect::action('SettingController@editMail');
    }

    public function delete()
    {

    }
    
}
