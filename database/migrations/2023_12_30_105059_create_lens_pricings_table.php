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
        Schema::create('lens_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('company_information')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('type_id')->references('id')->on('lens_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('coating_id')->references('id')->on('photo_coatings')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('index_id')->references('id')->on('photo_indices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('chromatic_id')->references('id')->on('photo_chromatics')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('sphere_from')->nullable();
            $table->string('sphere_to')->nullable();
            $table->string('cylinder_from')->nullable();
            $table->string('cylinder_to')->nullable();
            $table->string('addition_from')->nullable();
            $table->string('addition_to')->nullable();
            $table->string('cost')->nullable();
            $table->string('price')->nullable();
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
        Schema::dropIfExists('lens_pricings');
    }
};
