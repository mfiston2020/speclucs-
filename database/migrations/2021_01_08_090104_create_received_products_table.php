<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('received_products', function (Blueprint $table) {
            $table->id();
            $table->string('company_id');
            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('product_id');
            $table->string('stock');
            $table->string('faulty_stock')->nullable();
            $table->string('cost')->nullable();
            $table->timestamps();
            
            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('cascade');
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
        Schema::dropIfExists('received_products');
    }
}
