<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZonasPublicitariasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zonas_publicitarias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('zonasbuses_id')->unsigned()->nullable();

            $table->foreign('zonasbuses_id')->references('id')->on('zonas_buses');
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
        Schema::dropIfExists('zonas_publicitarias');
    }
}
