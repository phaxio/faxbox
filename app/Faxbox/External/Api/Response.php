<?php namespace Faxbox\External\Api;

class Response {
    
    protected $status;
    protected $message;
    protected $data;

    /**
     * @param bool $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
    
    public function isSuccess()
    {
        return $this->status;
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}