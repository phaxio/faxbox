<?php

use Faxbox\Repositories\Setting\SettingInterface;
use Faxbox\Service\Form\MailSettings\MailForm;

class SettingController extends BaseController {

    protected $settings;
    protected $mailForm;

    public function __construct(SettingInterface $settings, MailForm $mailForm)
	{
        parent::__construct();
        
        $this->settings = $settings;
        $this->mailForm = $mailForm;

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
        
        $result = $this->mailForm->save($data);
        
        if ($err = $this->mailForm->errors())
        {
            Session::flash('error', 'There was a problem updating your settings');

            return Redirect::action('SettingController@editMail')
                           ->withInput()
                           ->withErrors($err);
        }
        
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
            $logo->move(base_path('userdata/public/images'), $name);
            
            $this->settings->write('faxbox.logo', $name, true);
        }
        
        
        $this->settings->writeArray($data, true);

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
