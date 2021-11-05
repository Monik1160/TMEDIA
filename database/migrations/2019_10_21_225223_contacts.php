<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Contacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->nullable();
            $table->enum('contact_type', ['individual', 'company']);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('identification');
            $table->unsignedBigInteger('identification_type_id');

            $table->unsignedInteger('parent_id')->nullable();

            $table->boolean('is_client')->nullable();
            $table->boolean('is_agency')->nullable();

            $table->boolean('is_provider')->nullable();
            $table->enum('provider_type', ['General', 'Autobusero', 'Instalador'])->nullable()->default(null);

            $table->text('phone')->nullable();
            $table->text('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            $table->string('address')->nullable();
            $table->unsignedBigInteger('provincia_id')->nullable();
            $table->unsignedBigInteger('canton_id')->nullable();
            $table->unsignedBigInteger('distrito_id')->nullable();
            $table->unsignedBigInteger('barrio_id')->nullable();
            $table->string('post_office_box')->nullable();

            $table->text('observation')->nullable();
            $table->text('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_currency')->nullable();
            $table->text('policy_number')->nullable();

            $table->foreign('provincia_id')->references('provincia_id')->on('geodatacr_provincias');
            $table->foreign('canton_id')->references('canton_id')->on('geodatacr_cantones');
            $table->foreign('distrito_id')->references('distrito_id')->on('geodatacr_distritos');
            $table->foreign('barrio_id')->references('barrio_id')->on('geodatacr_barrios');

            $table->foreign('parent_id')->references('id')->on('contacts');
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
        //
    }
}
