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
        Schema::table('track_order_records', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users','id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('track_order_records', function (Blueprint $table) {
            $table->dropForeign('user_id');
        });
    }
};
