<?php namespace Faxbox\External\Api;

use Phaxio\Phaxio;
use Phaxio\PhaxioOperationResult;

class PhaxioApi implements FaxInterface{

    protected $phaxio;
    protected $response;
    
    public function __construct(Phaxio $phaxio, Response $response)
    {
        $this->phaxio = $phaxio;
        $this->response = $response;
    }
    
    public function sendFax($to, $filenames = [], $options = [])
    {
        $response = $this->phaxio->sendFax($to, $filenames, $options);
    }

    public function receiveFax()
    {

    }
    
    public function createPhone($areaCode, $callbackUrl = null)
    {
        $response = $this->phaxio->provisionNumber($areaCode, $callbackUrl);
        
        return $this->_parseResponse($response);
    }
    
    public function deletePhone($phoneNumber)
    {
        $response = $this->phaxio->releaseNumber($phoneNumber);
    }
    
    private function _parseResponse(PhaxioOperationResult $response)
    {
        $this->response->setStatus($response->succeeded());
        $this->response->setMessage($response->getMessage());
        
        return $this->response;
    }
}