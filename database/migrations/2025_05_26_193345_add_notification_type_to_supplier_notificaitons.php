<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('supplier_notifies', function (Blueprint $table) {
            $table->string('notification_type')->nullable();
            $table->foreignId('product_id')->after('id')->nullable()->constrained('products','id')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_notifies', function (Blueprint $table) {
            $table->dropColumn('notification_type');
            $table->dropForeign('product_id');
        });
    }
};
