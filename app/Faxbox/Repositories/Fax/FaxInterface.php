<?php namespace Faxbox\Repositories\Fax;

interface FaxInterface {

    public function all();

    /**
     * Gets all the sent and received faxes that a user has access to.
     *
     * @param integer $userId
     *
     * @return array An array of faxes including the number, phone number, and user
     */
    public function findByUserId($userId);

    public function byId($id, $checkAccess = true);

    public function download($id, $type = 'l');

    public function store($data);

    public function update($data);

    public function createReceived($data);
}