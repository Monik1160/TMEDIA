<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRutaBusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruta_bus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buses_id')->unsigned();
            $table->integer('rutas_id')->unsigned();

            $table->foreign('buses_id')->references('id')->on('buses');
            $table->foreign('rutas_id')->references('id')->on('rutas');

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
        Schema::dropIfExists('ruta_bus');
    }
}
