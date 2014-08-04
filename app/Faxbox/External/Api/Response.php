<?php namespace Faxbox\External\Api;

class Response {
    
    protected $status;
    protected $message;

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

    /**
     * @param bool $status
     */
    public function setMessage($status)
    {
        $this->status = $status;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
}