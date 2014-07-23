<?php namespace Faxbox\Repositories\Recipient;

use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Recipient;

class EloquentRecipientRepository extends EloquentAbstractRepository implements RecipientInterface {

    /**
     * Construct a new SentryUser Object
     */
    public function __construct(Recipient $recipient)
    {
        $this->model = $recipient;
    }
    
} 