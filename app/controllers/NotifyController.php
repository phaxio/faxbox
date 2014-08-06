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

        $input = json_decode($input);

        // Call back to the api to retrieve the data to make sure this is legit
        $response = $this->api->status($input->id);

        $fax = $response->getData();

        if ($response->isSuccess())
        {
            $data['id']           = $fax['tags']['id'];
            $data['phaxio_id']    = $fax['id'];
            $data['pages']        = $fax['num_pages'];
            $data['direction']    = $fax['direction'];
            $data['completed_at'] = date('Y-m-d H:i:s', $fax['completed_at']);
            $data['in_progress']  = false;

            if ($fax['status'] === 'success')
            {

                $data['sent'] = true;

            } else if (isset($fax['recipients'][0]->error_type))
            {

                $data['sent']    = false;
                $data['message'] = $this->getErrorMessage(
                    $fax['recipients'][0]['error_type'],
                    $fax['recipients'][0]['error_code']
                );

            } else if (isset($fax['error_type']))
            {

                $data['sent']    = false;
                $data['message'] = $this->getErrorMessage(
                    $fax['error_type'],
                    $fax['error_code']
                );

            } else
            {
                $data['sent'] = false;
            }

            $this->faxes->update($data);

            if ($data['sent'])
            {

                // send event

            }
        } else
        {
            // this fax doesn't exist on the remote server, someone is doing something fishy
            return Response::make(null, 403);
        }

    }

    private function getErrorMessage($type, $code)
    {
        $error = '';

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
        }

        return $error;
    }

}