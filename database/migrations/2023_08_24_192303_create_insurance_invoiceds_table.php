<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_invoiceds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_information', 'id')->cascadeOnDelete();
            $table->string('invoice')->nullable();
            $table->string('total_amount')->nullable();
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
        Schema::dropIfExists('insurance_invoiceds');
    }
};
