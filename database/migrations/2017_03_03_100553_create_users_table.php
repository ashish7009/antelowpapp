<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('userid');
			$table->tinyInteger('usertype')->default(2)->comment('1: admin, 2: front user');
			$table->tinyInteger('issuperadmin')->default(0)->comment('0: not super admin, 1: super admin');
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->string('firstname')->nullable();
			$table->string('lastname')->nullable();
			$table->string('phoneno')->nullable();
			$table->string('address1')->nullable();
			$table->string('address2')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('country')->nullable();
			$table->string('pincode')->nullable();
			$table->string('imagefile')->nullable();
			$table->rememberToken();
			$table->tinyInteger('status')->default(1)->comment('0: inactive, 1: active');
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
		Schema::drop('users');
	}

}
