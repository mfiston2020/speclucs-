<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('category_id');
            // $table->bigInteger('type_id')->nullable();
            // $table->bigInteger('index_id')->nullable();
            // $table->bigInteger('chromatics_id')->nullable();
            // $table->bigInteger('coating_id')->nullable();
            $table->string('product_name');
            $table->text('description');
            $table->string('stock');
            $table->string('deffective_stock');
            $table->string('price');
            $table->string('cost');
            $table->timestamps();
            // ========== Foreign key definition ==================
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
