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
        Schema::create('request_detail', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id',255)->nullable();
            $table->string('request_type',255)->nullable();
            $table->string('timesheet_date',50)->nullable();
            $table->time('time')->nullable();
            $table->integer('timesheet_id')->nullable();
            $table->dateTime('request_time')->nullable();
            $table->dateTime('real_time')->nullable();
            $table->string('reason',255)->nullable();
            $table->dateTime('time_respond')->nullable();
            $table->string('status',15)->nullable();
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
        Schema::dropIfExists('request_detail');
    }
};
