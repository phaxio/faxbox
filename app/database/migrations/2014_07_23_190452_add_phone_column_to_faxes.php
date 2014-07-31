<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneColumnToFaxes extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faxes',
        function (Blueprint $table)
        {
            $table->integer('phone_id')->after('phaxio_id')->unsigned()->nullable();

            $table->foreign('phone_id')
                ->references('id')
                ->on('phones')
                ->onDelete('set null')
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
        Schema::table('faxes',
        function (Blueprint $table)
        {
            $table->dropForeign('phone_id');
        });
    }

}
