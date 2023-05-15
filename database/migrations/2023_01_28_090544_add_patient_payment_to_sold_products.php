<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPatientPaymentToSoldProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sold_products', function (Blueprint $table) {
            $table->string('patient_payment')->after('total_amount')->nullable();
            $table->string('approved_amount')->after('total_amount')->nullable();
            $table->string('insurance_payment')->after('total_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sold_products', function (Blueprint $table) {
            //
        });
    }
}
