<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProformaProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proforma_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('proforma_id');
            $table->unsignedBigInteger('product_id');
            $table->string('quantity',50);
            $table->string('unit_price',50);
            $table->string('insurance_percentage',50);
            $table->string('total_amount',50);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('company_information')->onDelete('cascade');
            $table->foreign('proforma_id')->references('id')->on('proformas')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proforma_products');
    }
}
