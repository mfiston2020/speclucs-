<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('powers', function (Blueprint $table) {
            $table->id();
            $table->string('company_id');
            $table->unsignedBigInteger('product_id');
            $table->bigInteger('type_id');
            $table->bigInteger('index_id');
            $table->bigInteger('chromatics_id');
            $table->bigInteger('coating_id');
            $table->string('sphere',20);
            $table->string('cylinder',20);
            $table->string('axis',20);
            $table->string('add',20)->nullable();
            $table->string('eye',20)->nullable();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('powers');
    }
}
