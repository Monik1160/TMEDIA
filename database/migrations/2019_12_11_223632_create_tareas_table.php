<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('notes');
            $table->text('cancel_message')->nullable();
            $table->text('decline_message')->nullable();
            $table->unsignedBigInteger('tarea_type_id');
            $table->unsignedBigInteger('campaing_id');
            $table->unsignedBigInteger('campaing_detail_id');
            $table->json('zonapublicitaria_id');
            $table->unsignedBigInteger('installer_id')->nullable();
            $table->unsignedBigInteger('bus_id');
            $table->json('montaje');
            $table->enum('status', [1, 2, 3, 4, 5, 6, 7])->default(1);
            $table->integer('status_changed')->nullable(); //Este campo se puede quitar
            $table->boolean('is_picked_up')->default(0); //Este campo se puede quitar
            $table->double('monto')->nullable(); //Este campo se puede quitar
            $table->boolean('approved')->default(null)->nullable();
            $table->date('is_picked_up_date')->nullable();
            $table->date('coordination_date')->nullable();
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
        Schema::dropIfExists('tareas');
    }
}
