<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailPrefsToUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->enum('sent_notification', ['never', 'failed', 'always'])->default('always');
            $table->enum('received_notification', ['never', 'groups', 'mine', 'always'])->default('always');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('sent_notification');
            $table->dropColumn('received_notification');
		});
	}

}
