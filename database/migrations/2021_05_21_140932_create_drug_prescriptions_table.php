<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrugPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drug_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drug_id');
            $table->unsignedBigInteger('prescription_id');
            $table->timestamps();
            
            $table->foreign('drug_id')->references('id')->on('drugs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drug_prescriptions');
    }
}
