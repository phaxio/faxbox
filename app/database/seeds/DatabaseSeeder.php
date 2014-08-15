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

        $this->call('DefaultSettings');
    }

}

class DefaultSettings extends Seeder {

    public function run()
    {
        DB::table('settings')->insert([
            [ 'name' => 'mailgunRouteId', 'value' => ''],
            [ 'name' => 'logo', 'value' => 'logo.png'],
            [ 'name' => 'name', 'value' => 'Faxbox'],
            [ 'name' => 'color1', 'value' => ''],
            [ 'name' => 'color2', 'value' => ''],
            [ 'name' => 'color3', 'value' => ''],
            [ 'name' => 'color4', 'value' => ''],
            [ 'name' => 'fax_api_public', 'value' => ''],
            [ 'name' => 'fax_api_secret', 'value' => ''],
            [ 'name' => 'installed', 'value' => '0'],
        ]);
    }

}
