<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesmedialikesdislikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seriesmedialikesdislikes', function(Blueprint $table)
		{
			$table->increments('seriesmedialikesdislikeid');
			$table->integer('seriesmediaid')->nullable()->unsigned()->comment('ref table: seriesmedias');
			$table->integer('userid')->nullable()->unsigned()->comment('ref table: users');
			$table->tinyInteger('type')->default(1)->comment('0: dislike, 1: like');
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
		Schema::drop('seriesmedialikesdislikes');
	}

}
