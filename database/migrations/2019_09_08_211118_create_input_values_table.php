<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInputValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('input_values', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('input_id')->unsigned();
            $table->decimal('value', 12,3)->nullable();

            $table->foreign('input_id')->references('id')->on('inputs');

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
        Schema::dropIfExists('input_values');
    }
}
