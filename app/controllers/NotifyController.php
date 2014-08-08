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

    public function __construct(
        FaxInterface $faxes,
        FaxApi $api,
        Users $users,
        FaxForm $faxForm
    ) {
        $this->faxes   = $faxes;
        $this->api     = $api;
        $this->users   = $users;
        $this->faxForm = $faxForm;
    }

    public function fax()
    {

        $input = Input::get('fax');

        $fax = json_decode($input, true);

        if ($fax['is_test'] && \App::environment() == 'production') return;

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

//        } else
//        {
//            // this fax doesn't exist on the remote server, someone is doing something fishy
//            return Response::make(null, 403);
//        }
    }
    
    public function phone()
    {
        
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