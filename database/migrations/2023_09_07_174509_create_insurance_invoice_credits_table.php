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
        Schema::create('insurance_invoice_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_information', 'id')->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained('invoices', 'id')->cascadeOnUpdate();
            $table->string('amount')->default('0');
            $table->string('status', 10)->default('active');
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
        Schema::dropIfExists('insurance_invoice_credits');
    }
};
