<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTasksColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('tareas', function (Blueprint $table) {
            
            $table->unsignedBigInteger('ruta_id')->nullable()->after('bus_id');
            $table->unsignedBigInteger('arte_id')->after('ruta_id');
            $table->json('desinstallion_data')->nullable()->after('arte_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn('ruta_id');
            $table->dropColumn('arte_id');
            $table->dropColumn('desinstallion_data');
        });
    }
}

