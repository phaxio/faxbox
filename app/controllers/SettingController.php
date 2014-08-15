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
    
    public function editAppearance()
    {
        $settings = $this->settings->get([
            'faxbox.name',
            'faxbox.logo',
            'faxbox.colors.sidebar',
            'faxbox.colors.link',
            'faxbox.colors.text',
            'faxbox.colors.background',
        ]);
        
        $this->view('settings.appearance', compact('settings'));
    }

    public function updateAppearance()
    {
        $data = Input::get();
        
        if(Input::hasFile('logo'))
        {
            $logo = Input::file('logo');
            $ext = $logo->getClientOriginalExtension();
            $name = 'logo.'.$ext;
            $logo->move(public_path('images'), $name);
            
            $this->settings->write('faxbox.logo', $name);
        }
        
        
        $this->settings->writeArray($data);

        Session::flash('success', "Appearance settings successfully updated");
        return Redirect::action('SettingController@editAppearance');
    }

    public function editFaxApi()
    {
        $settings = $this->settings->get([
            'services.phaxio.public',
            'services.phaxio.secret'
        ]);

        $this->view('settings.api', compact('settings'));
    }

    public function updateFaxApi()
    {
        $data = Input::all();

        $this->settings->writeArray($data);

        Session::flash('success', "API settings successfully updated");
        return Redirect::action('SettingController@editFaxApi');
    }

    public function delete()
    {

    }
    
}
