<?php

use Faxbox\Repositories\Setting\SettingInterface;
use Faxbox\Service\Form\Register\RegisterForm;

class InstallController extends BaseController {

    protected $settings;
    protected $config;
    protected $registerForm;

    /**
     * Constructor
     */
    public function __construct(SettingInterface $settings, RegisterForm $registerForm)
    {
        parent::__construct();

        $this->settings = $settings;
        $this->registerForm = $registerForm;
        $this->beforeFilter('checkInstalled', ['only' => ['store', 'index']]);
    }


    public function index()
    {
        // Get the base url. We do it this way cause Request::getBaseUrl() 
        // doesn't seem to work in all cases.
        $fullUrl = Request::fullUrl();
        $url = substr($fullUrl, 0, strpos($fullUrl, '/install'));
        
        $local = true;
        if(!isUsingLocalStorage())
        {
            $local = false;
            $required = [
                'database_database',
                'database_default',
                'database_host',
                'database_username',
                'database_password',
            ];
            
            $envErrors = [];
            foreach($required as $key)
            {
                if(!isset($_ENV[$key]))
                {
                    $envErrors[] = "The key <strong>$key</strong> must be set in your environment variables";
                }else
                {
                    $dotKey = str_replace('_', '.', $key);
                    $env[$dotKey] = $_ENV[$key];
                }
            }

            if($envErrors) return View::make('install.envErrors', compact('envErrors'));

        }
        
        return View::make('install.index', compact('url', 'env', 'local'));
    }


    public function store()
    {
        $data = Input::all();
        Input::flash();

        // Preliminary checks
        if( !($this->checkVersion()->getData()->status &&
            $this->checkExtension('mcrypt')->getData()->status &&
            $this->checkExtension('intl')->getData()->status &&
            $this->checkPermissions()->getData()->status)
        )
        {
            Session::flash('error', 'There was a problem. Make sure all of the server checks are showing green to continue.');
            return Redirect::action('InstallController@index');
        }

        // General checks
        if (!$data['services']['phaxio']['public'])
        {
            Session::flash('error',
                'Your Phaxio api keys are required. Please get them from <a href="http://www.phaxio.com/apiSettings" target="_blank">your account</a> to continue.');

            return Redirect::action('InstallController@index')
                           ->withErrors(['services.phaxio.public' => 'Your public and secret key are required.']);
        }

        if (!$data['services']['phaxio']['secret'])
        {
            Session::flash('error',
                'Your Phaxio api keys are required. Please get them from <a href="http://www.phaxio.com/apiSettings" target="_blank">your account</a> to continue.');

            return Redirect::action('InstallController@index')
                           ->withErrors(['services.phaxio.secret' => 'Your public and secret key are required.']);
        }

        if (!$data['app']['url'])
        {
            Session::flash('error', 'The Site URL is required.');

            return Redirect::action('InstallController@index')
                           ->withErrors(['app.url' => 'The Site URL is required']);
        }

        // We're assuming correct details for people inputting them in ENV
        if(isUsingLocalStorage())
        {
            $dbresult = $this->checkDBCredentials($data)->getData();
            if ($dbresult->message)
            {
                Session::flash('error',
                    'Your database credentials are incorrect:<br>' . $dbresult->message);

                return Redirect::action('InstallController@index');
            }

            // reformat data
            $db['database'] = $data['database'];
            $db = array_dot($db);

            // write our DB config
            foreach ($db as $key => $value)
            {
                if (!$value) continue;
                $this->settings->write($key, $value, true);
            }
        }

        // Run our migrations
        $artisan = base_path('artisan');
        exec("php $artisan migrate --package=cartalyst/sentry --force");
        exec("php $artisan migrate --force");

        // Create our superuser
        $data['admin']['permissions']['superuser'] = 1;
        $data['admin']['activate']                 = true;
        $result                                    = $this->registerForm->save($data['admin']);

        // Did the user get created? If not return with error
        if(!$result['success'])
        {
            Session::flash('error', trans('install.generalerror'));
            return Redirect::action('InstallController@index')->withErrors($this->registerForm->errors());
        }

        // Write these settings to the database
        $this->settings->write('app.key', Str::random(32));
        $this->settings->write('app.url', $data['app']['url']);
        $this->settings->write( 
            'services.phaxio.public',
            $data['services']['phaxio']['public']
        );
        $this->settings->write( 'services.phaxio.secret',
            $data['services']['phaxio']['secret']);

        // sensible mail settings
        $this->settings->write('mail.driver', 'sendmail');
        $this->settings->write( 
            'mail.from.address',
            'admin@' . parse_url($data['app']['url'])['host']
        );
        $from = $data['name'] ?: $data['admin']['first_name'] . ' ' . $data['admin']['last_name'];// use site name or admin's first/last name
        $this->settings->write('mail.from.name', $from);

        $this->settings->write('faxbox.name', $data['name']);
        $this->settings->write('faxbox.installed', 1);
        
        // installed=true is a workaround to make the success message display. Since we
        // effectively change the app.key during install, laravel will make a 
        // new session for the user. But it's currently writing to the old
        // session, so we can't use Session::flash() here.
        return Redirect::route('login', ['installed' => 'true']);
    }

    public function checkVersion()
    {
        return Response::json([
            'status' => phpversion() >= 5.4
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
        $dirs[] = storage_path('cache');
        $dirs[] = storage_path('logs');
        $dirs[] = storage_path('meta');
        $dirs[] = storage_path('sessions');
        $dirs[] = storage_path('views');
        $dirs[] = base_path('userdata');

        $dirs = array_diff($dirs, ['..', '.', '.gitignore']);

        $result['status'] = true;
        $result['message'] = [];

        foreach($dirs as $dir)
        {
            if(!is_writable($dir))
            {
                $result['status'] = false;
                $result['message'][] = "Could not make $dir writable. Please make it writable by entering this in the command line:<br><b>chmod -R 777 $dir</b>";
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
            
            $dbh = null;
            
            return Response::json([
                'status' => true,
                'message' => ''
            ]);

        } catch (PDOException $e) {
            return Response::json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

}