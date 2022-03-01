<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSepEduTipoComiteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sep_edu_tipo_com', function(Blueprint $table)
		{
			$table->increments('edu_tipo_com_id');
			$table->string('edu_tipo_com_descripcion');
                        
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
		Schema::drop('sep_edu_tipo_com');
	}

}