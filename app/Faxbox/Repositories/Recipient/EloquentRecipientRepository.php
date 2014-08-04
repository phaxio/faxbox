<?php namespace Faxbox\Repositories\Recipient;

use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Recipient;

class EloquentRecipientRepository extends EloquentAbstractRepository implements RecipientInterface {

    public function __construct(Recipient $recipient)
    {
        $this->model = $recipient;
    }
    
    public function newInstance()
    {
        return $this->model->newInstance();
    }
    
} 