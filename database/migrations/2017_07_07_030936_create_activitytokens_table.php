<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitytokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activitytokens', function(Blueprint $table)
		{
			$table->increments('activitytokenid');
			$table->integer('userid')->unsigned()->comment('ref table: users');
			$table->string('token');
			$table->tinyInteger('type')->default(1)->comment('1: verify user registration, 2: verify users changed email, 3: user password reset');
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
		Schema::drop('activitytokens');
	}

}
