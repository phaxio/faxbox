<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('DummyData');
    }

}

class DummyData extends Seeder {

    public function run()
    {
        Sentry::register([
            'email'      => 'nickv@makesomecode.com',
            'first_name' => 'Nick',
            'last_name'  => 'V',
            'password'   => '111111',
            'permissions' => ['superuser' => 1]
        ], 1);
    }

}
