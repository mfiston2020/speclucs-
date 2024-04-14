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
        Schema::table('invoice_statements', function (Blueprint $table) {
            $table->string('fromDate')->nullable()->after('all_invoice');
            $table->string('toDate')->nullable()->after('all_invoice');
            $table->string('source')->nullable()->after('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_statements', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
