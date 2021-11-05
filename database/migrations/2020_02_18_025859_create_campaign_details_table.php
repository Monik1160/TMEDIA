<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('zona_id')->nullable();
            $table->unsignedBigInteger('ruta_id')->nullable();
            $table->json('zona_publicitaria');
            $table->unsignedBigInteger('arte_id');
            $table->integer('bus_id')->unsigned()->nullable();
            $table->text('notes');

            $table->foreign('bus_id')->references('id')->on('buses');
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
        Schema::dropIfExists('campaign_details');
    }
}
