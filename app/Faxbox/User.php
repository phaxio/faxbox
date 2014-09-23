<?php namespace Faxbox;

use Cartalyst\Sentry\Users\Eloquent\User as SentryModel;

class User extends SentryModel {

    public function faxes()
    {
        return $this->hasMany('Faxbox\Fax');
    }
}
