<?php namespace Faxbox\Repositories\User;

interface UserInterface {

    public function isAdmin($id);

    public function isActivated($id);

    public function hasLoggedIn($id);

    public function resetCode($id);

    public function allowedResourceIds($level, $resourceClass, $userId);

    public function loggedInUserId();

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($data);

    /**
     * Update the specified resource in storage.
     *
     * @param  array $data
     *
     * @return Response
     */
    public function update($data);

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id);

    /**
     * Attempt activation for the specified user
     *
     * @param  int    $id
     * @param  string $code
     *
     * @return bool
     */
    public function activate($id, $code);

    /**
     * Resend the activation email to the specified email address
     *
     * @param  Array $data
     *
     * @return Response
     */
    public function resend($data);

    /**
     * Handle a password reset request
     *
     * @param  Array $data
     *
     * @return Bool
     */
    public function forgotPassword($data);

    /**
     * Process the password reset request
     *
     * @param  int    $id
     * @param  string $code
     *
     * @return Array
     */
    public function resetPassword($data);

    /**
     * Process a change password request.
     * @return Array $data
     */
    public function changePassword($data);

    /**
     * Return a specific user from the given id
     *
     * @param  integer $id
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function byId($id);

    public function getIdByLoginName($username);

    /**
     * Return all the registered users
     *
     * @return stdObject Collection of users
     */
    public function all();
}