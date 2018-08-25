<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowusersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('followusers', function(Blueprint $table)
		{
			$table->increments('followuserid');
			$table->integer('followerid')->nullable()->unsigned()->comment('ref table: users');
			$table->integer('userid')->nullable()->unsigned()->comment('ref table: users');
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
		Schema::drop('followusers');
	}

}
