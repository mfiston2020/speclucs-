<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_information','id')->cascadeOnDelete();
            $table->foreignId('file_id')->constrained('patient_files','id')->cascadeOnDelete();
            $table->string('medication')->nullable();
            $table->string('strength')->nullable();
            $table->string('route')->nullable();
            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable();
            $table->string('t_dosage')->nullable();
            $table->string('duration')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('medical_prescriptions');
    }
}
