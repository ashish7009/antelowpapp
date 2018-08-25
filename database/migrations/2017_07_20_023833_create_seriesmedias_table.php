<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesmediasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seriesmedias', function(Blueprint $table)
		{
			$table->increments('seriesmediaid');
			$table->integer('seriesid')->nullable()->unsigned()->comment('ref table: series');
			$table->integer('userid')->nullable()->unsigned()->comment('ref table: users');
			$table->tinyInteger('isfile')->default(0)->comment('0: url, 1: file');
			$table->string('filename')->nullable();
			$table->string('fileurl')->nullable();
			$table->text('description')->nullable();
			$table->timestamp('publishdate')->nullable();
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
		Schema::drop('seriesmedias');
	}

}
