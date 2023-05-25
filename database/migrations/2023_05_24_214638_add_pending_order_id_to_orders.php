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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('pending_order_id')->after('id')->nullable()->constrained('pending_orders','id')->cascadeOnDelete();
            $table->foreignId('product_id')->after('pending_order_id')->nullable()->constrained('products','id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('product_id');
            $table->dropForeign('pending_order_id');
        });
    }
};
