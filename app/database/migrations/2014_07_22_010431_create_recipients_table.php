<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recipients', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('fax_id')->unsigned();
			$table->string('name')->nullable();
			$table->integer('number');
			$table->char('country_code', 2);
			$table->timestamps();

            $table->foreign('fax_id')
                  ->references('id')
                  ->on('faxes')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recipients');
	}

}
