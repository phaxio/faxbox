<?php

use Faxbox\Repositories\User\UserInterface as Users;
use Faxbox\Repositories\Fax\FaxInterface;
use Faxbox\Service\Form\Fax\FaxForm;
use Symfony\Component\HttpFoundation\File\File;
use Faxbox\Service\Form\File\FileForm;

class FaxController extends BaseController {

    protected $cc;
    protected $users;
    protected $faxes;
    protected $faxForm;
    protected $fileForm;

    public function __construct(
        FaxInterface $faxes,
        Users $users,
        FaxForm $faxForm,
        FileForm $fileForm
    ) {
        parent::__construct();

        $this->users   = $users;
        $this->faxes   = $faxes;
        $this->faxForm = $faxForm;
        $this->fileForm = $fileForm;

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
        
        
        if(isset($data['fileNames']) && $data['fileNames'])
        {
            foreach ($data['fileNames'] as &$file)
            {
                $file = new File(base_path('userdata/docs/' . $file));
            }
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
        $input['files'] = Input::file('files');

        $result = $this->fileForm->save($input);
        
        if ($this->fileForm->errors())
        {
            return Response::json($this->fileForm->errors(), 400);
        }
        
        return $result;
    }

    public function download($id, $type = 'l')
    {
        $result = $this->faxes->download($id, $type);
        
        // If they want a pdf we need to set the proper header
        $headers = $type == 'p' ?
            ['Content-Type' => 'application/pdf', "Content-Disposition" => "attachment; filename=fax-$id.pdf"] :
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
