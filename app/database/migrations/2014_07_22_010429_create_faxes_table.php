<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faxes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('phaxio_id')->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->enum('direction', ['sent', 'received']);
			$table->integer('pages')->nullable();
			$table->boolean('sent')->default(false);
			$table->boolean('in_progress')->default(false);
			$table->timestamps();

			$table->foreign('user_id')
				  ->references('id')
				  ->on('users')
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
		Schema::drop('faxes');
	}

}
