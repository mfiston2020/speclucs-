<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('company_id');
            $table->integer('user_id');
            $table->string('client_id')->nullable();
            $table->string('statement_id')->nullable();
            $table->string('emailState')->nullable();
            $table->string('reference_number');
            $table->string('total_amount',20);
            $table->string('status',20);
            $table->string('payment')->nullable();
            $table->string('due')->nullable();
            $table->string('client_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('tin_number')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
