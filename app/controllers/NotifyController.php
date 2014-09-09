<?php

use Faxbox\External\Api\FaxInterface as FaxApi;
use Faxbox\Repositories\Fax\FaxInterface;
use Faxbox\Repositories\User\UserInterface as Users;
use Faxbox\Service\Form\Fax\FaxForm;

class NotifyController extends BaseController {

    protected $faxes;
    protected $api;
    protected $users;
    protected $faxForm;
    protected $file;

    public function __construct(
        FaxInterface $faxes,
        FaxApi $api,
        Users $users,
        FaxForm $faxForm,
        \Faxbox\Repositories\File\FileInterface $file
    ) {
        $this->faxes   = $faxes;
        $this->api     = $api;
        $this->users   = $users;
        $this->faxForm = $faxForm;
        $this->file = $file;
    }

    public function fax()
    {

        $input = Input::get('fax');

        $fax = json_decode($input, true);

        //if ($fax['is_test'] && \App::environment() == 'production') return Response::make("", 200);

        // Call back to the api to retrieve the data to make sure this is legit
        // todo uncomment this once the phaxio bug is fixed.
//        $response = $this->api->status($input->id);
//        $fax = $response->getData();

        if (\Config::get('app.debug'))
        {
            //\Log::info(print_r($response, true));
            \Log::info(print_r($fax, true));
        }


//        if ($response->isSuccess())
//        {
        $data['id']           = isset($fax['tags']['id']) ? $fax['tags']['id'] : null;
        $data['phaxio_id']    = $fax['id'];
        $data['pages']        = $fax['num_pages'];
        $data['direction']    = $fax['direction'];
        $data['completed_at'] = isset($fax['completed_at']) ?
                                date('Y-m-d H:i:s', $fax['completed_at']) :
                                null;

        $data['in_progress'] = false;

        if ($fax['status'] === 'success')
        {
            $data['sent'] = true;

        } else
        {
            $data['sent']    = false;
            $data['message'] = $this->getErrorMessage($fax);

        }

        if ($data['direction'] == 'sent')
        {
            $faxItem = $this->faxes->update($data);
        } else
        {
            $data['number'] = $fax['from_number'];
            $data['phone']  = $fax['to_number'];
            
            $faxItem = $this->faxes->createReceived($data);
        }

        Event::fire('fax.processed', ['fax' => $faxItem]);
        
        return Response::make('', 200);

//        } else
//        {
//            // this fax doesn't exist on the remote server, someone is doing something fishy
//            return Response::make(null, 403);
//        }
    }

    public function sendFromEmail($number)
    {
        \Log::info(print_r(Input::all(), true));
        
        $input = Input::all();
        $data = [];

        $data['user_id'] = $this->users->getIdByLoginName($input['sender']);

        if($data['user_id'] === null)
            return Response::make("Unauthorized", 200); // mailgun will only shut up when we respond 200

        $data['fileNames'] = $this->file->store(Input::file());
        
        $data['direction'] = 'sent';
        $data['toPhoneCountry'] = '';
        $data['fullNumber'] = $number;
        
        $this->faxes->store($data);
        
        return \Response::make("", 200);
    }


    private function getErrorMessage($fax)
    {
        $error = $type = $code = '';

        if (isset($fax['recipients'][0]['error_type']))
        {
            $type = $fax['recipients'][0]['error_type'];
            $code = $fax['recipients'][0]['error_code'];

        } else if (isset($fax['error_type']))
        {
            $type = $fax['error_type'];
            $code = $fax['error_code'];
        }

        switch ($type)
        {
            case'documentConversionError':
                $error = "There was a problem with the type of document you supplied.";
                break;
            case'lineError':
                $error = "Phone Line Error - " . $code;
                break;
            case'faxError':
                $error = "Fax Communication Error - " . $code;
                break;
            case'fatalError':
                $error = "An error occurred with our system. Our administrators have been notified.";
                break;
            case'generalError':
                $error = "An error occurred with our system. Our administrators have been notified.";
                break;
            default:
                break;
        }

        return $error;
    }

}