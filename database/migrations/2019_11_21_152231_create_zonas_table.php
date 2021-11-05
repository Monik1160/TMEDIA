<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zonas', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');


            $table->unsignedBigInteger('provincia_id')->nullable();
            $table->unsignedBigInteger('canton_id')->nullable();
            $table->unsignedBigInteger('distrito_id')->nullable();
            $table->unsignedBigInteger('barrio_id')->nullable();

            $table->foreign('provincia_id')->references('provincia_id')->on('geodatacr_provincias');
            $table->foreign('canton_id')->references('canton_id')->on('geodatacr_cantones');
            $table->foreign('distrito_id')->references('distrito_id')->on('geodatacr_distritos');
            $table->foreign('barrio_id')->references('barrio_id')->on('geodatacr_barrios');


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
        Schema::dropIfExists('zonas');
    }
}
