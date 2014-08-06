<?php
/**
 * Created by Nick Verwymeren.
 *
 * Date: 2014-08-03
 *
 */
namespace Faxbox\External\Api;

interface FaxInterface {

    public function sendFax($to, $filenames = [], $options = []);

    public function status($id);

    public function receiveFax();

    public function createPhone($areaCode, $callbackUrl = null);

    public function deletePhone($phoneNumber);
}