<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('company_id');
            $table->string('credit_amount');
            $table->string('additional_amount')->default('0');
            $table->string('comment')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('company_information')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company_information')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_credits');
    }
}
