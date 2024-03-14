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
        Schema::create('insurance_sumary_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_sumary_id')->constrained('insurance_invoice_sumaries','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('company_id')->constrained('company_information','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('invoice_id')->constrained('invoices','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('credit_amount')->default('0');
            $table->string('insurance_amount')->default('0');
            $table->string('total_amount')->default('0');
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
        Schema::dropIfExists('insurance_sumary_invoices');
    }
};
