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
        Schema::create('track_pricing_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_information','id')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users','id')->cascadeOnUpdate();
            $table->foreignId('product_id')->nullable()->constrained('products','id')->cascadeOnUpdate();
            $table->string('new')->nullable();
            $table->string('old')->nullable();
            $table->string('side',10)->nullable();
            $table->string('status',10)->nullable();
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
        Schema::dropIfExists('track_pricing_changes');
    }
};
