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
        Schema::table('unavailable_products', function (Blueprint $table) {
            //
            $table->string('segment_h')->after('product_id')->nullable();
            $table->string('mono_pd')->after('product_id')->nullable();
            $table->string('location')->after('product_id')->nullable();
            $table->string('supplier_id')->after('product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unavailable_products', function (Blueprint $table) {
            //
            $table->dropColumn('mono_pd');
            $table->dropColumn('segment_h');
            $table->dropColumn('location');
            $table->dropColumn('supplier');
        });
    }
};
