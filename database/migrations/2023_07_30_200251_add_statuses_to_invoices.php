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
        Schema::table('invoices', function (Blueprint $table) {
            //
            $table->dateTime('set_price')->after('statement_id')->nullable();
            $table->dateTime('payment_approval')->after('statement_id')->nullable();
            $table->dateTime('sent_to_supplier')->after('statement_id')->nullable();
            $table->dateTime('receive_from_supplier')->after('statement_id')->nullable();
            $table->dateTime('sent_to_seller')->after('statement_id')->nullable();
            $table->dateTime('received_by_seller')->after('statement_id')->nullable();
            $table->dateTime('received_by_patient')->after('statement_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
            $table->dropColumn('set_price');
            $table->dropColumn('sent_to_seller');
            $table->dropColumn('payment_approval');
            $table->dropColumn('sent_to_supplier');
            $table->dropColumn('received_by_seller');
            $table->dropColumn('received_by_patient');
            $table->dropColumn('receive_from_supplier');
        });
    }
};
