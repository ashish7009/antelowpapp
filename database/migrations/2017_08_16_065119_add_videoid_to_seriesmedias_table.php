<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVideoidToSeriesmediasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('seriesmedias', function($table) {
			$table->string('videoid')->nullable()->after('isfile');
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
			$table->dropColumn('videoid');
		});
	}

}
