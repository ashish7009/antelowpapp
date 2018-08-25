<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFilethumbnameToSeriesmediasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('seriesmedias', function($table) {
			$table->string('filethumbname')->nullable()->after('filename');
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
			$table->dropColumn('filethumbname');
		});
	}

}
