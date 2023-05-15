<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPercentageToSoldProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sold_products', function (Blueprint $table) {
            $table->foreignId('insurance_id')->nullable()->after('id')->constrained('insurances','id')->cascadeOnDelete();
            $table->string('is_private',3)->after('unit_price')->nullable();
            $table->string('percentage')->after('unit_price')->nullable();
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
