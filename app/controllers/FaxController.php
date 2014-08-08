<?php

use Faxbox\Repositories\User\UserInterface as Users;
use Faxbox\Repositories\Fax\FaxInterface;
use Faxbox\Service\Form\Fax\FaxForm;
use Symfony\Component\HttpFoundation\File\File;

class FaxController extends BaseController {

    protected $cc;

    public function __construct(
        FaxInterface $faxes,
        Users $users,
        FaxForm $faxForm
    ) {
        parent::__construct();

        $this->users   = $users;
        $this->faxes   = $faxes;
        $this->faxForm = $faxForm;

        $this->beforeFilter('auth');
        $this->beforeFilter('hasAccess:send_fax',
            ['only' => ['store', 'create', 'upload']]);

        $cc       = @getenv(GEOIP_COUNTRY_CODE) ? @getenv(GEOIP_COUNTRY_CODE) : 'us';
        $this->cc = strtolower($cc);
    }

    public function index()
    {
        // todo this should be moved into the repo
        $user = Sentry::getUser();

        $faxes = $this->faxes->findByUserId($user->getId());

        $this->view('fax.list', compact('faxes'));
    }

    public function create()
    {
        $countries = $this->getCountries();

        $types    = Config::get('faxbox.supportedFiles');
        $exts     = array_column($types, 'ext');
        $accepted = implode(',', array_column($types, 'mime'));

        $this->view('fax.create', compact('countries', 'exts', 'accepted'));
    }

    public function store()
    {
        $data = Input::all();
        
        // todo validate files keys exists
        foreach ($data['fileNames'] as &$file)
        {
            $file = new File(storage_path('docs/' . $file));
        }

        $data['direction'] = 'sent';

        $result = $this->faxForm->save($data);
        
        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            return Redirect::action('FaxController@index');

        } else
        {
            Session::flash('error', $result['message']);
            
            return Redirect::action('FaxController@create')
                           ->withInput()
                           ->withErrors($this->faxForm->errors());
        }

    }

    public function show($id)
    {
        $fax = $this->faxes->byId($id);
        $this->view('fax.show', compact('fax'));
    }

    public function upload()
    {
        //todo validate before moving
        $names = [];

        foreach (Input::file('files') as $file)
        {
            // create a unique name and move it
            $names[] = $name = Str::random('32') . "." . $file->getClientOriginalExtension();
            $file->move(storage_path('docs'), $name);
        }

        return $names;
    }

    public function download($id, $type = 'l')
    {
        $result = $this->faxes->download($id, $type);

        // If they want a pdf we need to set the proper header
        $headers = $type == 'p' ?
            ['Content-Type' => 'application/pdf'] :
            ['Content-Type' => 'image/jpeg'];

        return \Response::make($result, 200, $headers);
    }

    /**
     * Gets the list of supported countries and sorts them according to users geo-located IP address.
     *
     * @return array|mixed
     */
    private function getCountries()
    {
        $countries = Config::get('faxbox.phone');
        uasort($countries, [$this, 'sortCountries']);

        $oldCC =    Input::old('toPhoneCountry') ?
                    Input::old('toPhoneCountry') :
                    Input::get('c', '');

        foreach ($countries as $k => $v)
        {

            if ($v['short'] == $oldCC) $bumpKey = $k;

            if ($this->cc == $v['short'])
            {
                $temp              = [$k => $countries[$k]];
                $temp[$k]['style'] = 'class="bumped"';
                
                unset($countries[$k]);
                
                $countries = $temp + $countries;
            } else
            {
                $countries[$k]['style'] = '';
            }
        }

        if (isset($bumpKey))
        {
            $countries = [$bumpKey => $countries[$bumpKey]] + $countries;
        }

        $countries = array_values($countries);

        return $countries;
    }

    private function sortCountries($a, $b)
    {
        $a = trim($a['name']);
        $b = trim($b['name']);

        return strcmp($a, $b);
    }

}
