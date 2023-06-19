<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_information', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('active');
            $table->string('logo')->default('speclucs.jpg');
            $table->string('company_name');
            $table->string('company_phone');
            $table->string('company_email');
            $table->string('company_street')->nullable();
            $table->string('company_tin_number')->nullable();
            $table->string('is_clinic',20)->nullable();
            $table->string('sms_quantity',20)->default('0');
            $table->string('can_send_sms',2)->nullable();
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
        Schema::dropIfExists('company_information');
    }
}
