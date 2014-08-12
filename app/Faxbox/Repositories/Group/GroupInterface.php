<?php
/**
 * Created by Nick Verwymeren.
 *
 * Date: 2014-08-12
 *
 */
namespace Faxbox\Repositories\Group;

interface GroupInterface {

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($data);

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
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
     * Return a specific group by a given id
     *
     * @param  integer $id
     *
     * @return \Cartalyst\Sentry\Group
     */
    public function byId($id);

    /**
     * Return a specific group by a given name
     *
     * @param  string $name
     *
     * @return Group
     */
    public function byName($name);

    /**
     * Return all the registered groups
     *
     * @return stdObject Collection of groups
     */
    public function all();

    public function allWithUsers();

    public function allWithChecked($resource = null);
}