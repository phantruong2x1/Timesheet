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
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->date('time')->nullable();
            $table->string('staff_id',50)->nullable();
            $table->bigInteger('working_date')->nullable();
            $table->bigInteger('overtime_hour')->nullable();
            $table->integer('last_checkin')->nullable();
            $table->integer('early_checkout')->nullable();
            $table->integer('day_off')->nullable();  
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
        Schema::dropIfExists('statistics');
    }
};
