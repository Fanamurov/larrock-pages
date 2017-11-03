<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('page', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('title');
			$table->text('description');
			$table->char('url', 191)->unique();
			$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('position')->default(0);
			$table->integer('active')->default(1);
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
		Schema::dropIfExists('page');
	}

}
