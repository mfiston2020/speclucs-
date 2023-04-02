<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProformasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proformas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('insurance_id');
            $table->string('patient_firstname')->nullable();
            $table->string('patient_lastname')->nullable();
            $table->string('patient_phone')->nullable();
            $table->string('patient_email')->nullable();
            $table->string('status',10)->default('pending');
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('insurance_id')->references('id')->on('insurances')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company_information')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proformas');
    }
}
