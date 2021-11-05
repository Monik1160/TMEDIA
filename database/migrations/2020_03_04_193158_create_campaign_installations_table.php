<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignInstallationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_installations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_detail_id')->unsigned()->nullable();
            $table->integer('campaign_id')->unsigned()->nullable();
            $table->integer('bus_id')->unsigned()->nullable();
            $table->integer('zona_id')->unsigned()->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('installed_by')->unsigned()->nullable();
            $table->date('installed_at');
            $table->boolean('is_active')->default(false);
            $table->integer('required_desinstallation')->unsigned()->nullable();


            $table->foreign('campaign_detail_id')->references('id')->on('campaign_details');
            $table->foreign('campaign_id')->references('id')->on('campaings');
            $table->foreign('bus_id')->references('id')->on('buses');
            $table->foreign('zona_id')->references('id')->on('buses_zonas');
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
        Schema::dropIfExists('campaign_installations');
    }
}
