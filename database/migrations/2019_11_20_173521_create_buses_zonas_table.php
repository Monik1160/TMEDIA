<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusesZonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses_zonas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buses_id')->unsigned();
            $table->unsignedBigInteger('zonas_buses_id')->unsigned();
            $table->unsignedBigInteger('campaing_id')->nullable();
            $table->boolean('is_occupied')->default(false);

            $table->foreign('buses_id')->references('id')->on('buses');
//            $table->foreign('zonas_buses_id')->references('id')->on('zonas_buses');
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
        Schema::dropIfExists('buses_zonas');
    }
}
