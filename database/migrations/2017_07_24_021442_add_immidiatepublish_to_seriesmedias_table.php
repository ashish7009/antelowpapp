<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImmidiatepublishToSeriesmediasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('seriesmedias', function($table) {
			$table->tinyInteger('immidiatepublish')->default(1)->after('description')->comment('0: no, 1: yes');
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
			$table->dropColumn('immidiatepublish');
		});
	}

}
