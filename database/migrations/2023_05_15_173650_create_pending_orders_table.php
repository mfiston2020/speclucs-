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
            $table->foreignId('company_id')->constrained('company_information','id')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('company_information','id')->cascadeOnDelete();
            $table->foreignId('type_id')->constrained('lens_types','id')->cascadeOnDelete();
            $table->foreignId('coating_id')->constrained('photo_coatings','id')->cascadeOnDelete();
            $table->foreignId('index_id')->constrained('photo_indices','id')->cascadeOnDelete();
            $table->foreignId('chromatic_id')->constrained('photo_chromatics','id')->cascadeOnDelete();
            $table->string('firstname',100);
            $table->string('lastname',100);
            $table->string('gender',100);
            $table->string('date_of_birth',100);
            $table->string('patient_number');
            $table->string('quantity',5)->default('1');
            $table->string('order_cost',20)->default('0');
            $table->string('order_price',20)->default('0');
            $table->string('status')->default('pending');
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
