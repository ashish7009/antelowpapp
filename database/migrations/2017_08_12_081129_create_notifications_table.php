<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('notificationid');
			$table->tinyInteger('type')->default(1)->comment('1: like, 2: comment, 3: follow, 4: video posted');
			$table->integer('userid')->nullable()->unsigned()->comment('ref table: users');
			$table->integer('contentid')->nullable()->unsigned()->comment('ref table: for 1: seriesmedialikesdislikes/ for 2: seriesmediacomments/ for 3: users/ for 4: seriesmedias');
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
		Schema::drop('notifications');
	}

}
