<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfluencerFieldsToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table) {
			$table->integer('likeinfluencer')->nullable()->after('issuperadmin');
			$table->integer('followerinfluencer')->nullable()->after('likeinfluencer');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table) {
			$table->dropColumn('likeinfluencer');
			$table->dropColumn('followerinfluencer');
		});
	}

}
