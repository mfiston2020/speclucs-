<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('history_id');
            $table->unsignedBigInteger('company_id');
            $table->string('value');
            $table->timestamps();

            $table->foreign('file_id')->references('id')->on('patient_files')->onDelete('cascade');
            $table->foreign('history_id')->references('id')->on('histories')->onDelete('cascade');
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
        Schema::dropIfExists('file_histories');
    }
}
