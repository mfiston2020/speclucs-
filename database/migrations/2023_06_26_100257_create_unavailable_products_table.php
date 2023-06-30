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
        Schema::create('unavailable_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_information','id')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products','id')->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained('invoices','id')->cascadeOnDelete();
            $table->foreignId('type_id')->constrained('lens_types','id')->cascadeOnDelete();
            $table->foreignId('coating_id')->constrained('photo_coatings','id')->cascadeOnDelete();
            $table->foreignId('index_id')->constrained('photo_indices','id')->cascadeOnDelete();
            $table->foreignId('chromatic_id')->constrained('photo_chromatics','id')->cascadeOnDelete();
            $table->string('sphere')->nullable();
            $table->string('cylinder')->nullable();
            $table->string('axis')->nullable();
            $table->string('addition')->nullable();
            $table->string('quantity')->default('1');
            $table->string('eye')->default('any');
            $table->string('price')->default('0');
            $table->string('cost')->default('0');
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('unavailable_products');
    }
};
