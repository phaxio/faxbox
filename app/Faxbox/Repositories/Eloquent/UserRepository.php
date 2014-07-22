<?php
namespace Faxbox\Repositories\Eloquent;

use Faxbox\User as User;

class UserRepository extends AbstractRepository {
    
    public function __construct(User $user)
    {
        $this->model = $user;
    }
} 