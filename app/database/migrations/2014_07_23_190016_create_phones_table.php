<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('phones', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('description');
            $table->string('number');
            $table->string('city');
            $table->string('state');
            $table->char('country_code', 2);
            $table->timestamps();
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('phones');
    }

}
