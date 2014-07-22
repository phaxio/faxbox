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
			$table->integer('phaxio_id');
			$table->integer('user_id')->unsigned();
			$table->integer('recipient_id')->unsigned();
			$table->enum('direction', ['sent', 'received']);
			$table->integer('pages');
			$table->boolean('sent')->default(false);
			$table->boolean('in_progress')->default(false);
			$table->timestamps();

			$table->foreign('user_id')
				  ->references('id')
				  ->on('users')
				  ->onDelete('cascade')
				  ->onUpdate('cascade');

			$table->foreign('recipient_id')
				  ->references('id')
				  ->on('recipients')
				  ->onDelete('cascade')
				  ->onUpdate('cascade');
		});
		
		// Now that the faxes table is created we need to create the Recipient foreign key as well
		Schema::table('receipients', function(Blueprint $table)
		{
			$table->foreign('fax_id')
				  ->references('id')
				  ->on('faxes');
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
