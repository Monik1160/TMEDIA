<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignDesignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_design', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('image');
            $table->unsignedBigInteger('bus_id');
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('detail_id');
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
        Schema::dropIfExists('campaign_design');
    }
}
