<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('tipo_placa', ['','CL','PUBLI', 'AB', 'AP', 'CB', 'CP', 'GB', 'GP', 'HB', 'HP', 'LB', 'LP', 'PB', 'SJB']);
            $table->string('placa', 6);
            $table->enum('tipo_contrato',['Fijo','Consumo'])->nullable();
            $table->bigInteger('carroceria_id')->unsigned();
            $table->bigInteger('autobusero_id')->unsigned();

            $table->text('observaciones')->nullable();
            $table->boolean('activo')->nullable();
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
        Schema::dropIfExists('buses');
    }
}
