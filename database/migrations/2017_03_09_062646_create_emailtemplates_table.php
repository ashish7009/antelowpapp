<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailtemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('emailtemplates', function(Blueprint $table)
		{
			$table->increments('emailtemplateid');
			$table->string('caption')->nullable();
			$table->string('mailto')->nullable();
			$table->string('mailtoname')->nullable();
			$table->string('mailfrom')->nullable();
			$table->string('mailfromname')->nullable();
			$table->string('mailsubject')->nullable();
			$table->longText('mailbody')->nullable();
			$table->string('replyto')->nullable();
			$table->string('replytoname')->nullable();
			$table->string('ccto')->nullable();
			$table->string('cctoname')->nullable();
			$table->string('bccto')->nullable();
			$table->string('bcctoname')->nullable();
			$table->tinyInteger('status')->default(1)->comment = '0: inactive, 1: active';
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
		Schema::drop('emailtemplates');
	}

}
