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
        Schema::create('insurance_invoice_sumaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_information','id')->cascadeOnDelete()->cascadeOnUpdate();
            // $table->foreignId('invoice_id')->constrained('invoices','id')->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('insurance_id')->constrained('insurances','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('insurance_invoice_sumaries');
    }
};
