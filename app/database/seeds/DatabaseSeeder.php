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
            'email'      => 'admin@admin.com',
            'first_name' => 'The',
            'last_name'  => 'Admin',
            'password'   => Hash::make('admin'),
            'permissions' => ['superuser' => 1]
        ], 1);
    }

}
