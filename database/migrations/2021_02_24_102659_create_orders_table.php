<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_id');
            $table->foreignId('product_id')->nullable()->constrained('products','id')->cascadeOnDelete();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('coating_id');
            $table->unsignedBigInteger('index_id');
            $table->unsignedBigInteger('chromatic_id');
            $table->string('order_number');
            $table->string('patient_number');
            $table->string('prescription');
            $table->string('expected_delivery');
            $table->string('order_cost');
            $table->string('status');
            $table->string('production_date');
            $table->string('completed_date');
            $table->string('delivery_date');
            $table->string('invoice_date');
            $table->string('sphere_r')->nullable();
            $table->string('cylinder_r')->nullable();
            $table->string('axis_r')->nullable();
            $table->string('addition_r')->nullable();
            $table->string('sphere_l')->nullable();
            $table->string('cylinder_l')->nullable();
            $table->string('axis_l')->nullable();
            $table->string('addition_l')->nullable();
            $table->string('quantity');
            $table->string('cost');
            $table->timestamps();


            // $table->foreign('supplier_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company_information')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('lens_types')->onDelete('cascade');
            $table->foreign('coating_id')->references('id')->on('photo_coatings')->onDelete('cascade');
            $table->foreign('index_id')->references('id')->on('photo_indices')->onDelete('cascade');
            $table->foreign('chromatic_id')->references('id')->on('photo_chromatics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
