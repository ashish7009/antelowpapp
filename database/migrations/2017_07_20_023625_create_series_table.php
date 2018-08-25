<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('series', function(Blueprint $table)
		{
			$table->increments('seriesid');
			$table->tinyInteger('isongoing')->default(0)->comment('0: no, 1: yes');
			$table->string('title')->nullable();
			$table->string('slug')->nullable();
			$table->text('description')->nullable();
			$table->string('filename')->nullable();
			$table->integer('userid')->nullable()->unsigned()->comment('ref table: users');
			$table->tinyInteger('status')->default(1)->comment('1: active, 2: inactive');
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
		Schema::drop('series');
	}

}
