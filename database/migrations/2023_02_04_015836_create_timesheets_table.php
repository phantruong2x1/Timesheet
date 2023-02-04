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
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('record_id')->nullable();
            $table->string('staff_id', 255)->nullable();
            $table->string('date', 20)->nullable();
            $table->bigInteger('first_checkin')->nullable();
            $table->bigInteger('last_checkout')->nullable();
            $table->bigInteger('working_hour')->nullable();
            $table->bigInteger('overtime')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('leave_status', 20)->nullable();
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
        Schema::dropIfExists('timesheets');
    }
};
