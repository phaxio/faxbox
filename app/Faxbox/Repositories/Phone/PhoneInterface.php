<?php namespace Faxbox\Repositories\Phone;

interface PhoneInterface {

    public function all();

    public function byId($id);

    public function store($data);

    public function update($data);

    public function destroy($id);

    public function getAvailableAreaCodes();

    /**
     * Gets all phone numbers the user has access to.
     *
     * @param integer $userId
     *
     * @return array An array of phone numbers.
     */
    public function findByUserId($userId);

    public function findByNumber($number, $eloquent = false);
}