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
        Schema::create('pending_orders', function (Blueprint $table) {
            $table->id();
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
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('pending_orders');
    }
};
