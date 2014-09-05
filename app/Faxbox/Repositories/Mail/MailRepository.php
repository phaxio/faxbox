<?php namespace Faxbox\Repositories\Mail;

use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Repositories\Setting\SettingInterface;
use Mailgun\Connection\Exceptions\MissingRequiredParameters;
use Mailgun\Mailgun;

class MailRepository extends EloquentAbstractRepository implements MailInterface{


    protected $settings;

    public function __construct(SettingInterface $settings) 
    {
        $this->settings = $settings;
    }

    public function store($input)
    {
        $this->settings->writeArray($input);

        $domain = explode('@', $input['mail']['from']['address'])[1];
        if( $input['mail']['driver'] == 'mailgun' 
            && $routeId = $this->updateMailgun($input['services']['mailgun']['secret'], $domain)
        )
        {
            $this->settings->write('services.mailgun.routeId', $routeId, true);
        }
    }
    
    private function updateMailgun($api, $domain)
    {
        # Instantiate the client.
        $mgClient = new Mailgun($api);

        $notifyUrl = $this->settings->get('faxbox.notify.send') . "/\\g<phone>";
        
        if($id = $this->settings->get('services.mailgun.routeId')){
            $id = "/".$id;
            $method = "put";
        } else
        {
            $id = "";
            $method = "post";
        }

        # Issue the call to the client.
        try
        {
            $result = $mgClient->$method("routes" . $id,
                [
                    'priority'    => 0,
                    'expression'  => "match_recipient(\"(?P<phone>.*?)@$domain\")",
                    'action'      => ["forward(\"" . $notifyUrl . '")', 'stop()'],
                    'description' => 'Faxbox Send Fax Route',
                ]);
        } catch(\Mailgun\Connection\Exceptions\InvalidCredentials $e)
        {
            return false;
        } catch(MissingRequiredParameters $e)
        {
            return false;
        }

        $id = substr($id, 1);
        
        return $id ?: $result->http_response_body->route->id;
    }
    
}