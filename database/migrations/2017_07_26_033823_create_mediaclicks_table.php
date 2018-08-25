<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaclicksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mediaclicks', function(Blueprint $table)
		{
			$table->increments('mediaclickid');
			$table->integer('seriesmediaid')->nullable()->unsigned()->comment('ref table: seriesmedias');
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
		Schema::drop('mediaclicks');
	}

}
