<?php

use Faxbox\Repositories\Session\SessionInterface;
use Faxbox\Service\Form\Login\LoginForm;
use Faxbox\Repositories\Setting\SettingInterface;
use October\Rain\Config\Repository;
use Faxbox\Service\Form\Register\RegisterForm;

class InstallController extends BaseController {

    protected $settings;
    protected $config;
    protected $registerForm;

    /**
     * Constructor
     */
    public function __construct(SettingInterface $settings, Repository $config, RegisterForm $registerForm)
    {
        parent::__construct();

        $this->settings = $settings;
        $this->config = $config;
        $this->registerForm = $registerForm;
        $this->beforeFilter('checkInstalled', ['only' => ['store', 'index']]);
    }

    
    public function index()
    {
        return View::make('install.index');
    }

    
    public function store()
    {
        $data = Input::all();
        Input::flash();
        
        if( !($this->checkVersion()->getData()->status &&
            $this->checkDBCredentials($data)->getData()->status &&
            $this->checkExtension('mcrypt')->getData()->status &&
            $this->checkExtension('intl')->getData()->status &&
            $this->checkPermissions()->getData()->status)
        )
        {
            Session::flash('error', 'There was a problem. Make sure all of the server checks are showing green to continue.');
            return Redirect::action('InstallController@index');
        }
        
        //setup our DB stuff
        $driver = $data['database']['default'];
        unset($data['database']['default']);

        // rearrange data
        $db['database']['connections'][$driver] = $data['database'];
        $db['database']['default']              = $driver;


        $db = array_dot($db);

        // write our DB config
        foreach ($db as $key => $value)
        {
            if (!$value) continue;
            $this->config->write($key, $value);
        }

        
        // Run our migrations
        exec('php artisan migrate --package=cartalyst/sentry --force');
        exec('php artisan migrate --force');

        // Create our user
        $data['admin']['permissions']['superuser'] = 1;
        $data['admin']['activate']                 = true;
        $result                                    = $this->registerForm->save($data['admin']);
        
        if(!$result['success'])
        {
            Session::set('error', trans('install.generalerror'));
            return Redirect::action('InstallController@index')->withErrors($this->registerForm->errors());
        }

        
        // write our other settings
        $this->settings->write('app.key', Str::random(32));
        $this->settings->write('app.url', $data['app']['url']);
        $this->settings->write('faxbox.name', $data['name']);
        $this->settings->write('services.phaxio.public', $data['services']['phaxio']['public']);
        $this->settings->write('services.phaxio.secret', $data['services']['phaxio']['secret']);
        $this->settings->write('faxbox.installed', true);
        
        Session::flash('success', "Faxbox successfully installed. Please Login below with the account you just created.");
        return Redirect::to('login');
    }

    public function checkVersion()
    {
        return Response::json([
            'status' => phpversion() > 5.4
        ]);
    }

    public function checkExtension($name = null)
    {
        $name = Input::get('ext-name') ?: $name;
        
        $status = true;
        
        if(extension_loaded($name) === false)
        {
            $status = false;
        }

        return Response::json([
            'status' => $status
        ]);
    }

    public function checkPermissions()
    {
        $dirs = [];
        
        $dirs[] = storage_path();
        $dirs[] = app_path('config');
        
        foreach(scandir(storage_path()) as $path)
        {
            $dirs[] = $path;
        }

        $dirs = array_diff($dirs, ['..', '.', '.gitignore']);
        
        $result['status'] = true;
        $result['message'] = [];

        foreach($dirs as $dir)
        {
            $dir = storage_path($dir);
            
            $writable = is_writable($dir);
            
            if(is_dir($dir) && !$writable && !chmod($dir, 0755))
            {
                $result['status'] = false;
                $result['message'][] = "Could not make $dir writable. Please make it writable by entering this in the command line:<br><b>chmod -R 755 $dir</b>";
            }
        }
        
        return Response::json($result);
    }
    
    public function checkDBCredentials($data = null)
    {
        $data = Input::all() ?: $data;

        try {
            if($data['database']['default'] == 'mysql'){
                $dbh = new PDO($data['database']['default'] . ':host=' . $data['database']['host'] . ';dbname=' . $data['database']['database'],
                    $data['database']['username'],
                    $data['database']['password'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );

            } else{
                $dbh = new PDO("sqlite:".$data['database']['database']);
            }

            return Response::json([
                'status' => true,
            ]);
                
        } catch (PDOException $e) {
            return Response::json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        
    }

}