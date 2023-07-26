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
        Schema::table('sold_products', function (Blueprint $table) {
            $table->foreignId('unavailable_product_id')->after('id')->nullable()->constrained('unavailable_products', 'id')->cascadeOnDelete();
            $table->string('segment_h')->after('product_id')->nullable();
            $table->string('mono_pd')->after('product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sold_products', function (Blueprint $table) {
            // $table->dropForeign('unavailable_product_id');
            $table->dropColumn('mono_pd');
            $table->dropColumn('segment_h');
            $table->dropColumn('unavailable_product_id');
        });
    }
};
