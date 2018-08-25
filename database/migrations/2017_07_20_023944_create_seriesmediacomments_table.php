<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesmediacommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seriesmediacomments', function(Blueprint $table)
		{
			$table->increments('seriesmediacommentid');
			$table->integer('seriesmediaid')->nullable()->unsigned()->comment('ref table: seriesmedias');
			$table->integer('userid')->nullable()->unsigned()->comment('ref table: users');
			$table->text('comment')->nullable();
			$table->tinyInteger('status')->default(0)->comment('0: inactive, 1: active');
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
		Schema::drop('seriesmediacomments');
	}

}
