<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatementInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statement_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('company_id');
            $table->unsignedBigInteger('statement_id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('total_amount');
            $table->timestamps();

            $table->foreign('statement_id')->references('id')->on('invoice_statements')->onUpdate('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statement_invoices');
    }
}
