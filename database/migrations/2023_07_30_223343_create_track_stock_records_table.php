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
        Schema::create('track_stock_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_information', 'id')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products', 'id');
            $table->string('current_stock')->nullable();
            $table->string('incoming')->nullable();
            $table->string('change')->nullable();
            $table->string('operation')->nullable();
            $table->string('reason')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('track_stock_records');
    }
};
