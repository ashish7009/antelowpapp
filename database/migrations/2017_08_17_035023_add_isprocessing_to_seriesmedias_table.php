<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsprocessingToSeriesmediasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('seriesmedias', function($table) {
			$table->tinyInteger('isprocessing')->default(0)->after('publishdate');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('seriesmedias', function($table) {
			$table->dropColumn('isprocessing');
		});
	}

}
