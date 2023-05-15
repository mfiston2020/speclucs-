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
            $table->foreignId('insurance_id')->nullable()->after('company_id')->constrained('insurances','id')->cascadeOnDelete();
            $table->enum('gender',['male','female'])->after('phone')->nullable();
            $table->string('dateOfBirth')->after('phone')->nullable();
            $table->string('insurance_card_number')->after('phone')->nullable();
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
            $table->dropColumn('gender');
            $table->dropColumn('dateOfBirth');
            $table->dropForeign('insurance_id');
            $table->dropColumn('insurance_card_number');
        });
    }
};
