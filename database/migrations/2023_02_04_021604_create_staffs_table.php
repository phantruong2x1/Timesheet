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
        Schema::create('staff', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('full_name', 255);
            $table->date('birthday')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('hometown', 255)->nullable();
            $table->string('tax_code', 15)->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->string('email', 255);
            $table->string('status', 10);
            $table->string('address', 255)->nullable();
            $table->string('email_company', 255)->nullable();
            $table->date('begin_time')->nullable();
            $table->date('end_time')->nullable();
            $table->date('official_time')->nullable();
            $table->string('type', 50)->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('position_id')->nullable();
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
        Schema::dropIfExists('staff');
    }
};
